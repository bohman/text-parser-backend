<?php

namespace App\TextParser;

use Illuminate\Support\Facades\Storage;

class TextFile
{
    /**
     * 
     */
    public static function getFileInformation($file_path = '')
    {
        return [
            'file_path' => $file_path,
            'file_location' => Storage::path($file_path),
            'file_modified' => $file_path . '_modified',
            'file_url' => Storage::url($file_path),
            'mime_type' => Storage::mimeType($file_path),
            'size' => Storage::size($file_path),
            //'content_raw' => Storage::get($file_path),
        ];
    }

    /**
     * This might seem unnecessary compared to writing a simple regexp to replace all
     * occurrences of a substring. Here's the resoning behind the approach:
     * 
     * If there's anything I've learned from studying linguistics it's that language is incredibly
     * irregular. Rules, sure, but they're not always followed and is considered a description
     * at best. That means it's tricky to write a regexp that can accurately surround words with
     * characters since there's always a risk that we're catching common substrings in words
     * ("the" in "brother") or forget spacing, interpunctuation and capitalization (" The ", " the!", " the.")
     * and other edge cases we can't even dream of. However, since PHP can pretty accurately map all
     * occurrences of a word, we're using that to bypass the majority of this problem.
     * 
     * @TODO: PHP is not the best tool for input/output. We could offload this to
     *        bash with sed, for instance.
     */
    public static function surroundMostCommonWordInFile($file = [], $analysis = [], $left = '', $right = '')
    {
        $handle = fopen($file['file_location'], "r");

        if(!$handle || !$analysis['word_count']) {
            Storage::put($file['file_modified'], '');
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
            file_put_contents(Storage::path($file['file_modified']), $line, FILE_APPEND); // Laravels Storage::append is too slow
        }

        fclose($handle);

        return true;
    }
}
