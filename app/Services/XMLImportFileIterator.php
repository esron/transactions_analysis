<?php

namespace App\Services;

use Iterator;
use SimpleXMLIterator;

class XMLImportFileIterator implements Iterator
{
    private SimpleXMLIterator $transactions;
    private int $currentKey;

    public function __construct(string $path)
    {
        $fileContents = file_get_contents($path);
        $this->transactions = (new SimpleXMLIterator($fileContents))->transacao;
        $this->currentKey = 0;
    }

    public function current(): mixed
    {
        $transaction = $this->transactions[$this->currentKey];
        return array_merge(
            $this->extractAccount($transaction, 'origem'),
            $this->extractAccount($transaction, 'destino'),
            [(string) $transaction->valor,
            (string) $transaction->data]
        );
    }

    public function next(): void
    {
        ++$this->currentKey;
    }

    public function key(): int
    {
        return $this->currentKey;
    }

    public function rewind(): void
    {
        $this->currentKey = 0;
    }

    public function valid(): bool
    {
        return $this->currentKey < count($this->transactions);
    }

    private function extractAccount(SimpleXMLIterator $element, string $type)
    {
        return [
            (string) $element->$type->banco,
            (string) $element->$type->agencia,
            (string) $element->$type->conta,
        ];
    }
}
