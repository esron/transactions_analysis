<?php

namespace App\Services;

use Iterator;
use SplFileObject;

class CSVImportFileIterator implements Iterator
{
    private SplFileObject $fileHandler;
    private string $separator = ',';

    public function __construct(string $path)
    {
        $this->fileHandler = new SplFileObject($path);
    }

    public function current(): array
    {
        return explode($this->separator, $this->fileHandler->current());
    }

    public function next(): void
    {
        $this->fileHandler->next();
    }

    public function key(): int
    {
        return $this->fileHandler->key();
    }

    public function rewind(): void
    {
        $this->fileHandler->rewind();
    }

    public function valid(): bool
    {
        return $this->fileHandler->valid();
    }
}
