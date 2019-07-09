<?php

namespace App\TextParser;

use Illuminate\Support\Facades\Storage;

class TextFile
{
    /**
     * Returns useful information about a file by using
     * Laravel's built in Storage function. Just utility, really.
     */
    public static function getFileInformation($file_path = '')
    {
        return [
            'file_path' => $file_path,
            'file_location' => Storage::disk('public')->path($file_path),
            'file_url' => Storage::disk('public')->url($file_path),
            'mime_type' => Storage::disk('public')->mimeType($file_path),
            'size' => Storage::disk('public')->size($file_path),
            'file_modified' => $file_path . '_modified',
            'file_modified_url' => Storage::disk('public')->url($file_path . '_modified'),
            //'content_raw' => Storage::get($file_path),
        ];
    }

    /**
     * Streams a file, finds the occurrences of $analysis['most_common_word'] line by line,
     * surrounds it with $left and $right, and writes the line to a new file.
     * 
     * Why streaming, you might ask? Because if we try to hold it in memory we time out on
     * larger files.
     * 
     * @TODO: PHP is not the best tool for input/output. We could offload this to
     *        bash with sed, for instance.
     */
    public static function surroundMostCommonWordInFile($file = [], $analysis = [], $left = '', $right = '')
    {
        $handle = fopen($file['file_location'], "r");

        if(!$handle || !$analysis['word_count']) {
            Storage::disk('public')->put($file['file_modified'], '');
            return false;
        }

        while(($line = fgets($handle)) !== false) {
            $word_map = str_word_count(strtolower($line), 2);
            foreach(array_reverse($word_map, 1) as $start => $word) {
                if($word !== $analysis['most_common_word']) {
                    continue;
                }
                $word_length = strlen($word);
                $original_word = substr($line, $start, $word_length);
                $replacement = $left . $original_word . $right;
                $line = substr_replace($line, $replacement, $start, $word_length);
            }
            file_put_contents(Storage::disk('public')->path($file['file_modified']), $line, FILE_APPEND); // Laravels Storage::append is too slow
        }

        fclose($handle);

        return $left . $analysis['most_common_word'] . $right;
    }
}
