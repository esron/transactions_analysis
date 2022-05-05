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

    public function getUserEmail(): string
    {
        return $this->user->email;
    }

    public function testCanSeeTheUsersView()
    {
        $response = $this->actingAs($this->user)
            ->get('/users');

        $response->assertStatus(200)
            ->assertSee('USUÁRIOS CADASTRADOS')
            ->assertViewHas('users');
    }

    public function testReturnsErrorsIfEmailAlreadyInUser()
    {
        $response = $this->actingAs($this->user)
            ->post('/users', [
                'name' => 'Real Name',
                'email' => $this->user->email,
            ]);

        $response->assertStatus(302)
            ->assertInvalid([
                'email' => 'The email has already been taken.',
            ]);
    }

    /**
     * @dataProvider validationErrorsProvider
     */
    public function testCreateUserValidations(array $data, array $errors)
    {
        $response = $this->actingAs($this->user)
            ->post('/users', $data);

        $response->assertStatus(302)
            ->assertInvalid($errors);
    }

    public function validationErrorsProvider(): array
    {
        return [
            'is required' => [
                [],
                [
                    'name' => 'The name field is required',
                    'email' => 'The email field is required',
                ]
            ],
            'email invalid' => [
                [
                    'name' => 'Esron',
                    'email' => 'not a valid email',
                ],
                [
                    'email' => 'The email must be a valid email address.',
                ],
            ],
        ];
    }

    public function testCanSeeTheUserCreationForm()
    {
        $response = $this->actingAs($this->user)
            ->get('/users/create');

        $response->assertStatus(200)
            ->assertSee('CADASTRAR NOVO USUÁRIO')
            ->assertSee('Cadastrar');
    }

    public function testCanCreateAnUser()
    {
        $userData = User::factory()->make();
        $response = $this->actingAs($this->user)
            ->post('/users', [
                'name' => $userData->name,
                'email' => $userData->email,
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => $userData->name,
            'email' => $userData->email,
        ]);
    }
}
