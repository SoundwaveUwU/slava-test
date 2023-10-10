<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadExcelRequest;
use App\Jobs\ProcessExcelFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function create() {
        return view('upload');
    }

    public function store(UploadExcelRequest $request) {
        $relativePath = $request->file('excel_file')->store('excel_files', ['disk' => 'private']);

        $uniqueKey = Str::random();

        dispatch(new ProcessExcelFile(Storage::disk('private')->path($relativePath), $uniqueKey));

        return view('success', compact('uniqueKey'));
    }
}
