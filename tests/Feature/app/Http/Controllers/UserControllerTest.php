<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testCanSeeTheUsersView()
    {
        $response = $this->actingAs($this->user)
            ->get('/users');

        $response->assertStatus(200)
            ->assertSee('USUÁRIOS CADASTRADOS')
            ->assertViewHas('users');
    }
}
