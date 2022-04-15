<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    private function convertBytesToMegaBytes(float|int $megabytes): float
    {
        return $megabytes / (1e6);
    }

    private function readCsvFile(string $path): void
    {
        $handler = fopen($path, 'r');
        while (($line = fgetcsv($handler, 1000)) !== false) {
            $lineString = join(',', $line);
            Log::info($lineString);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:txt,csv|max:2048',
        ]);
        $name = $validatedData['file']->getClientOriginalName();
        $sizeInMb = $this->convertBytesToMegaBytes($validatedData['file']->getSize());
        Log::info("File name: $name, file size $sizeInMb mb");
        $this->readCsvFile($validatedData['file']->path());
        return "File name: $name, file size $sizeInMb mb";
    }
}
