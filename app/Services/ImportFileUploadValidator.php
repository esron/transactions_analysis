<?php

namespace App\Services;

interface ImportFileUploadValidator
{
    /**
     * Reads the file in the path passed in the constructor
     */
    public function readFile(): void;

    public function getTransactions(): array;
}
