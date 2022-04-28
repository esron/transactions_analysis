<?php

namespace App\Http\Requests;

use App\Models\Import;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class CSVUploadRequest extends FormRequest
{
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
            $name = $this->file->getClientOriginalName();
            $validationResponse = $this->validateCSV($this->file->path(), $validator);
            if ($validationResponse === false) {
                $validator->errors()->add('file', "Failed processing $name");
                return;
            }
            $this->merge(['file' => $validationResponse]);
        });
    }

    /**
     * Return the csv file as an array or false if it fails to.
     * Adds the validation errors to $validator
     */
    private function validateCSV(string $path, Validator $validator): array|bool
    {
        if (($handler = fopen($path, 'r')) === false) {
            Log::error("Failed to open file in $path");
            return false;
        }
        if (($firstLine = fgetcsv($handler, 1000)) === false) {
            return false;
        }
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
        while (($line = fgetcsv($handler, 1000)) !== false) {
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
        fclose($handler);
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
        return Import::firstWhere('transactions_date', $date->format('Y-m-d')) !== null;
    }
}
