<?php

namespace Tests\Feature\app\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanSeeAuthForm()
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertSee('SISTEMA DE ANÁLISE DE TRANSAÇÕES')
            ->assertSee('form')
            ->assertSee('Email')
            ->assertSee('Senha');
    }
}
