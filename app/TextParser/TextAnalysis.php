<?php

namespace App\TextParser;

use Illuminate\Support\Facades\Storage;

class TextAnalysis
{
    /**
     * A super simple function that normalizes our
     * text. We need to do this one way or another to get useful data
     * when we run our analysis.
     * 
     * @TODO: This is not enough to do a proper text analysis.
     *        Had this been a serious project, I would need to
     *        research this algorithms couple of hours and implement
     *        a proper algorithm.
     */
    public static function normalizeString($string = '')
    {
        $string = strtolower($string); // Lowercase
        $string = preg_replace('/[^a-z0-9]/', ' ', $string); // remove everything but letters and numbers
        $string = preg_replace('!\s+!', ' ', $string); // Replace multiple whitespaces with a single space
        $string = trim($string); // Remove trailing whitespaces
        return $string;
    }

    /**
     * 
     */
    public static function analyzeFile($file = [])
    {
        $file_content = Storage::disk('public')->get($file['file_path']);
        $content_normalized = Self::normalizeString($file_content);
        $all_words = str_word_count($content_normalized, 1);
        $unique_words = array_unique($all_words);
        $word_frequency = array_count_values($all_words);
        arsort($word_frequency);
        return [
            //'all_words' => $all_words,
            //'unique_words' => $unique_words,
            //'word_frequency' => $word_frequency,
            'word_count' => count($all_words),
            'unique_word_count' => count($unique_words),
            'most_common_word' => array_key_first($word_frequency),
        ];
    }
}
