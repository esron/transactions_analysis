<?php

namespace App\Http\Requests;

use App\Models\Import;
use App\Services\CSVImportFileIterator;
use App\Services\XMLImportFileIterator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class ImportFileUploadRequest extends FormRequest
{
    protected $fileDisk = 'temp';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|file|mimes:txt,csv|max:2048',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if ($this->file === null) {
                return false;
            }
            Storage::disk($this->fileDisk)
                ->put($this->file->hashName(), $this->file->get());
            $validationResponse = $this->validateFile(
                $this->file->getClientOriginalExtension(),
                $this->file->path(),
                $validator
            );
            if ($validationResponse === false) {
                $validator->errors()
                    ->add('file', "Failed processing {$this->file->getClientOriginalName()}");
                return;
            }
            $this->merge(['file' => $validationResponse]);
        });
    }

    /**
     * Return the csv file as an array or false if it fails to.
     * Adds the validation errors to $validator
     */
    private function validateFile(string $type, string $path, Validator $validator): array|bool
    {
        dump($type);
        $csvFileIterator = match ($type) {
            'csv' => new CSVImportFileIterator($path),
            'xml' => new XMLImportFileIterator($path),
            default => null,
        };
        if ($csvFileIterator == null) {
            return false;
        }
        $firstLine = $csvFileIterator->current();
        $lineNumber = 1;
        $date = $this->getDateFromLine($firstLine, $validator, $lineNumber);
        if ($date === false) {
            return false;
        }
        if ($this->dateAlreadyImported($date)) {
            $validator->errors()->add('file', "Transactions for {$date->format('Y-m-d')} already imported");
        }
        $this->merge(['date' => $date]);
        $lines = [$firstLine];
        $csvFileIterator->next();
        foreach ($csvFileIterator as $line) {
            $lineNumber++;
            $date = $this->getDateFromLine($line, $validator, $lineNumber);
            $isSameDay = $date->isSameDay($this->date);
            if ($date === false) {
                return false;
            }
            if ($this->isLineComplete($line) === false || $isSameDay === false) {
                Log::debug("Request Date: $this->date, Ignoring:", $line);
                continue;
            }
            $lines[] = $line;
            Log::info(join(',', $line));
        }
        return $lines;
    }

    private function getDateFromLine(array $line, Validator $validator, int $lineNumber): Carbon|bool
    {
        try {
            return Carbon::parse(last($line));
        } catch (InvalidFormatException) {
            Log::error("InvalidFormat for date");
            $validator->errors()->add('file', "Invalid format for date on line $lineNumber");
            return false;
        }
    }

    private function isLineComplete(array $line): bool
    {
        return ((bool) array_search("", $line)) === false;
    }

    private function dateAlreadyImported(Carbon $date): bool
    {
        return Import::firstWhere('transactions_date', $date->startOfDay()) !== null;
    }
}
