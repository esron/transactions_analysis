<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    private function convertBytesToMegaBytes(float|int $megabytes): float
    {
        return $megabytes / (64.0);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:txt|max:2048',
        ]);
        $validatedData['file']->store('public/files');
        $name = $validatedData['file']->getClientOriginalName();
        $sizeInMb = $this->convertBytesToMegaBytes($validatedData['file']->getSize());
        Log::info("File name: $name, file size $sizeInMb megabytes");
        return "File name: $name, file size $sizeInMb megabytes";
    }
}
