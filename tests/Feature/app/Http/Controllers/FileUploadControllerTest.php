<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Import;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testCanSeeFileUploadForm()
    {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertStatus(200)
            ->assertSee('form')
            ->assertSee('Selecionar o arquivo para realizar upload')
            ->assertSee('Importar');
    }

    public function testFileUploadValidationsFileIsRequired()
    {
        $response = $this->actingAs($this->user)->post('/file-upload');
        $response->assertStatus(302)
            ->assertInvalid([
                'file' => 'The file field is required.'
            ]);
    }

    public function testFileUploadValidationsFileIsCsvOrTxt()
    {
        $file = UploadedFile::fake()->create('virus.exe', 1000);
        $response = $this->actingAs($this->user)->post('/file-upload', [
            'file' => $file,
        ]);
        $response->assertStatus(302)
            ->assertInvalid([
                'file' => 'The file must be a file of type: txt, csv.'
            ]);
    }

    public function testFileUploadValidationInvalidFormatForDate()
    {
        $disk = 'temp';
        Storage::fake($disk);
        $file = UploadedFile::fake()->createWithContent('virus.csv', 'Hello world!');
        $response = $this->actingAs($this->user)->post('/file-upload', [
            'file' => $file,
        ]);
        Storage::disk($disk)->assertExists($file->hashName());
        $response->assertStatus(302)
            ->assertInvalid([
                'file' => 'Invalid format for date on line 1'
            ]);
    }

    public function testFileUploadValidationImportForThisDateAlreadyImported()
    {
        $disk = 'temp';
        $fileName = 'example.csv';
        $importDate = '2022-01-01';
        $fileContent = "BANCO DO BRASIL,0001,00001-1,BANCO BRADESCO,0001,00001-1,8000,{$importDate}T07:30:00";
        Import::factory()->create([
            'transactions_date' => Carbon::parse($importDate),
        ]);
        Storage::fake($disk);
        $file = UploadedFile::fake()->createWithContent($fileName, $fileContent);
        $response = $this->actingAs($this->user)->post('/file-upload', [
            'file' => $file,
        ]);
        Storage::disk($disk)->assertExists($file->hashName());
        $response->assertStatus(302)
            ->assertInvalid([
                'file' => 'Transactions for 2022-01-01 already imported'
            ]);
    }

    public function testFileUploadCreatesAccountsTransactionsAndImports()
    {
        $disk = 'temp';
        $fileName = 'example.csv';
        $fileContent = $this->stubCsvFileContent();
        Storage::fake($disk);
        $file = UploadedFile::fake()->createWithContent($fileName, $fileContent);
        $response = $this->actingAs($this->user)->post('/file-upload', [
            'file' => $file,
        ]);
        Storage::disk($disk)->assertExists($file->hashName());
        $response->assertStatus(302)
            ->assertValid();
        $this->assertDatabaseHas('accounts', [
            'bank_name' => 'BANCO DO BRASIL',
            'branch' => '0001',
            'number' => '00001-1',
        ]);
        $this->assertDatabaseHas('transactions', [
            'amount' => '8000',
        ]);
        $this->assertDatabaseHas('imports', [
            'transactions_date' => '2022-01-01 00:00:00',
        ]);
    }

    private function stubCsvFileContent(): string
    {
        return <<<EOT
BANCO DO BRASIL,0001,00001-1,BANCO BRADESCO,0001,00001-1,8000,2022-01-01T07:30:00
BANCO SANTANDER,0001,00001-1,BANCO BRADESCO,0001,00001-1,210,2022-01-01T08:12:00
EOT;
    }
}
