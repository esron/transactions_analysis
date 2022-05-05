<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testCanSeeTheHomePage()
    {
        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200)
            ->assertSee('IMPORTAÇÕES REALIZADAS')
            ->assertSee('Selecionar o arquivo para realizar upload')
            ->assertSee('IMPORTAR TRANSAÇÕES');
    }
}
