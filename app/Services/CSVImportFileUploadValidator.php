<?php

namespace App\Services;

use App\Services\Exceptions\FileNotFoundException;

class CSVImportFileUploadValidator implements ImportFileUploadValidator
{
    private $lines = [];

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function readFile(): void
    {
        $lineNumber = 1;
        if (file_exists($this->filePath) === false) {
            throw new FileNotFoundException();
        }
        if (($handler = fopen($this->filePath, 'r')) === false) {
            Log::error("Failed to open file in {$this->filePath}");
        }
        if (($firstLine = fgetcsv($handler, 1000)) === false) {
            return;
        }
        $this->lines[] = $firstLine;
        while (($line = fgetcsv($handler, 1000)) !== false) {
            $lineNumber++;
            $date = $this->getDateFromLine($line, $this->validator, $lineNumber);
            $isSameDay = $date->isSameDay($this->date);
            if ($this->isLineComplete($line) === false || $isSameDay === false) {
                Log::debug("Request Date: $this->date, Ignoring:", $line);
                continue;
            }
            $this->lines[] = $line;
            Log::info(join(',', $line));
        }
        fclose($handler);
    }

    public function getTransactions(): array
    {
        return $this->lines;
    }

    private function getDateFromLine(array $line): Carbon
    {
        return Carbon::parse(last($line));
    }
}
