<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TextParser\TextFile;
use App\TextParser\TextAnalysis;
use Illuminate\Support\Facades\Storage;

class AnalysisController extends Controller
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
    public static function create(Request $request)
    {

        $request->validate([
            'upload' => 'required',
        ]);

        $file_path = $request->upload->store('uploads', ['disk' => 'public']);

        $file = TextFile::getFileInformation($file_path);

        $analysis = TextAnalysis::analyzeFile($file);

        $changes = TextFile::surroundMostCommonWordInFile($file, $analysis, 'foo', 'bar');

        $output = [
            'file' => $file,
            'analysis' => $analysis,
            'modified_text' => Storage::disk('public')->get($file['file_modified']),
            'modified_changes' => $changes,
        ];

        return response()->json($output);
    }
}
