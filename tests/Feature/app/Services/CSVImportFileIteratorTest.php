<?php

namespace Tests\Feature\app\Services;

use App\Services\CSVImportFileIterator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class CSVImportFileIteratorTest extends TestCase
{
    use RefreshDatabase;

    public function testThrowsExceptionIfInvalidPathIsProvided()
    {
        $this->expectException(RuntimeException::class);
        new CSVImportFileIterator('not valid');
    }

    public function testCurrentReturnsAnArray()
    {
        $disk = 'temp';
        $fileName = 'example.csv';
        $importDate = '2022-01-01';
        $fileContent = "BANCO DO BRASIL,0001,00001-1,BANCO BRADESCO,0001,00001-1,8000,{$importDate}T07:30:00";
        Storage::fake($disk);
        Storage::disk($disk)
            ->put($fileName, $fileContent);
        $path = Storage::disk($disk)->path($fileName);
        $CSVImportValidator = new CSVImportFileIterator($path);
        $current = $CSVImportValidator->current();
        $this->assertTrue(is_array($current));
        $this->assertEquals(explode(',', $fileContent), $current);
    }
}
