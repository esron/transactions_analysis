<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCantAccessProtectedRouteWithoutAuth()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function testCanSeeAuthForm()
    {
        $response = $this->get('/login');
        $response->assertStatus(200)
            ->assertSee('SISTEMA DE ANÁLISE DE TRANSAÇÕES')
            ->assertSee('form')
            ->assertSee('Email')
            ->assertSee('Senha');
    }

    public function testCanAuthenticate()
    {
        $password = '123456';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        $this->assertGuest();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
}
