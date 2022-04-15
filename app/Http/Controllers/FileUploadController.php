<?php

namespace App\Http\Controllers;

use App\Http\Requests\CSVUploadRequest;
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

    public function store(CSVUploadRequest $request)
    {
        $file = $request->validated()['file'];
        $name = $file->getClientOriginalName();
        $sizeInMb = $this->convertBytesToMegaBytes($file->getSize());
        Log::info("File name: $name, file size $sizeInMb mb");
        $this->readCsvFile($file->path());
        return redirect('/')->with('message', "File $name successfully uploaded!");
    }
}
