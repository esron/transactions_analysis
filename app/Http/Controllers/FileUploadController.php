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

    public function store(CSVUploadRequest $request)
    {
        $file = $request->validated()['file'];
        $csv_lines = $request->get('file');
        $name = $file->getClientOriginalName();
        $sizeInMb = $this->convertBytesToMegaBytes($file->getSize());
        Log::info("File name: $name, file size $sizeInMb mb");
        return redirect('/')->with('message', "File $name successfully uploaded!");
    }
}
