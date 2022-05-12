<?php

namespace Tests\Feature\app\Services;

use App\Services\CSVImportFileIterator;
use App\Services\XMLImportFileIterator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

class XMLImportFileIteratorTest extends TestCase
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
        $fileName = 'example.xml';
        $fileContent = $this->stubFileContent();
        Storage::fake($disk);
        Storage::disk($disk)
            ->put($fileName, $fileContent);
        $path = Storage::disk($disk)->path($fileName);
        $CSVImportValidator = new XMLImportFileIterator($path);
        $current = $CSVImportValidator->current();
        $this->assertTrue(is_array($current));
        $this->assertEquals($this->expectedArray(), $current);
    }

    public function testAForeachWorks()
    {
        $disk = 'temp';
        $fileName = 'example.xml';
        $fileContent = $this->stubFileContent();
        Storage::fake($disk);
        Storage::disk($disk)
            ->put($fileName, $fileContent);
        $path = Storage::disk($disk)->path($fileName);
        $CSVImportValidator = new XMLImportFileIterator($path);

        foreach ($CSVImportValidator as $key => $transaction) {
            $this->assertEquals($this->expectedArrayInPosition($key), $transaction);
        }
    }

    private function stubFileContent()
    {
        return <<<EOT
<transacoes>
  <transacao>
    <origem>
      <banco>BANCO DO BRASIL</banco>
      <agencia>0001</agencia>
      <conta>00001-1</conta>
    </origem>
    <destino>
      <banco>BANCO BRADESCO</banco>
      <agencia>0001</agencia>
      <conta>00001-1</conta>
    </destino>
    <valor>8000</valor>
    <data>2022-01-02T07:30:00</data>
  </transacao>
  <transacao>
    <origem>
      <banco>BANCO DO BRASIL</banco>
      <agencia>0001</agencia>
      <conta>00001-1</conta>
    </origem>
    <destino>
      <banco>BANCO BRADESCO</banco>
      <agencia>0001</agencia>
      <conta>00001-1</conta>
    </destino>
    <valor>8000</valor>
    <data>2022-01-02T07:30:00</data>
  </transacao>
</transacoes>
EOT;
    }

    private function expectedArray()
    {
        return explode(',', 'BANCO DO BRASIL,0001,00001-1,BANCO BRADESCO,0001,00001-1,8000,2022-01-02T07:30:00');
    }

    private function expectedArrayInPosition(int $key)
    {
        $arr = [
            explode(',', 'BANCO DO BRASIL,0001,00001-1,BANCO BRADESCO,0001,00001-1,8000,2022-01-02T07:30:00'),
            explode(',', 'BANCO DO BRASIL,0001,00001-1,BANCO BRADESCO,0001,00001-1,8000,2022-01-02T07:30:00'),
        ];

        return $arr[$key];
    }
}
