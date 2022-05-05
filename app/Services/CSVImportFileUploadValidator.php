<?php

namespace App\Services;

use App\Models\Import;
use App\Services\Exceptions\AlreadyImportedException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

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
        $fileContent = Storage::disk('temp')->get($this->filePath);
        $transactionLines = $this->transform($fileContent);
        $firstLine = $transactionLines->shift();
        $fileDate = $this->getDateFromLine($firstLine);
        if ($this->isDateAlreadyImported($fileDate)) {
            throw new AlreadyImportedException();
        }
        $this->lines = [$firstLine];
        foreach ($transactionLines as $line) {
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
    }

    public function getTransactions(): array
    {
        return $this->lines;
    }

    private function getDateFromLine(array $line): Carbon
    {
        return Carbon::parse(last($line));
    }

    private function isDateAlreadyImported(Carbon $date): bool
    {
        return Import::firstWhere('transactions_date', $date->startOfDay()) !== null;
    }

    private function transform(string $content)
    {
        $lines = explode("\n", $content);
        $transactions = collect();
        foreach ($lines as $line) {
            $transactions[] = explode(',', $line);
        }
        return $transactions;
    }
}
