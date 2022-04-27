<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FileUploadControllerTest extends TestCase
{
    use RefreshDatabase;

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

    public function testFileUploadValidations()
    {
        $response = $this->actingAs($this->user)->post('/file-upload');

        $response->assertStatus(422);
    }
}
