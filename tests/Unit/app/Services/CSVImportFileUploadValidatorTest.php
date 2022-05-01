<?php

namespace Tests\Unit\app\Services;

use App\Services\CSVImportFileUploadValidator;
use App\Services\Exceptions\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class CSVImportFileUploadValidatorTest extends TestCase
{
    public function testThrowsExceptionIfInvalidPathIsProvided()
    {
        $this->expectException(FileNotFoundException::class);
        $CSVImportValidator = new CSVImportFileUploadValidator('not valid');
        $CSVImportValidator->readFile();
    }
}
