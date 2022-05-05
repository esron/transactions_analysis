<?php

namespace Tests\Feature\app\Services;

use App\Models\Import;
use App\Services\CSVImportFileUploadValidator;
use App\Services\Exceptions\AlreadyImportedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToReadFile;
use Tests\TestCase;

class CSVImportFileUploadValidatorTest extends TestCase
{
    use RefreshDatabase;

    public function testThrowsExceptionIfInvalidPathIsProvided()
    {
        $this->expectException(UnableToReadFile::class);
        $CSVImportValidator = new CSVImportFileUploadValidator('not valid');
        $CSVImportValidator->readFile();
    }

    public function testThrowsExceptionIfFileDateAlreadyImported()
    {
        $this->expectException(AlreadyImportedException::class);
        $disk = 'temp';
        $fileName = 'example.csv';
        $importDate = '2022-01-01';
        $fileContent = "BANCO DO BRASIL,0001,00001-1,BANCO BRADESCO,0001,00001-1,8000,{$importDate}T07:30:00";
        Import::factory()->create([
            'transactions_date' => Carbon::parse($importDate),
        ]);
        Storage::fake($disk);
        Storage::disk($disk)
            ->put($fileName, $fileContent);
        $CSVImportValidator = new CSVImportFileUploadValidator($fileName);
        $CSVImportValidator->readFile();
    }
}
