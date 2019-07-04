<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyzeController extends Controller
{
    
    /**
     * index
     * When we want to use the built in frontend
     */
    public static function index() {
        return view('pages.index');
    }

    /**
     * Accepts file upload and returns information about the contents.
     * 
     * @TODO: This works decently for smaller files, but if we want to handle
     *        really large files we need to implement a queue.
     */
    public static function parseText(Request $request)
    {

        $request->validate([
            'upload' => 'required',
        ]);

        $file_path = $request->upload->store('uploads');

        $file = TextFile::getFileInformation($file_path);

        $analysis = TextAnalysis::analyzeFile($file);

        TextFile::surroundMostCommonWordInFile($file, $analysis, 'foo', 'bar');

        $modified_text = Storage::get($file['file_modified']);

        $output = [
            'file' => $file,
            'analysis' => $analysis,
            'modified_text' => $modified_text,
        ];

        dd($output);
        return response()->json($output);
    }
}
