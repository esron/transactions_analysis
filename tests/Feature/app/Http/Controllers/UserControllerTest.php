<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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

        $user = User::factory()->create();
        $response = $this->actingAs($this->user)
            ->put("/users/{$user->id}", [
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
        Mail::fake();
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

        Mail::assertSent(function (UserCreated $mail) use ($userData) {
            return $mail->hasTo($userData->email);
        });
    }

    public function testCanSeeTheUserEditForm()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($this->user)
            ->get("/users/{$user->id}/edit");

        $response->assertStatus(200)
            ->assertSee('EDITAR USUÁRIO')
            ->assertSee('Salvar');
    }

    /**
     * @dataProvider validationErrorsProvider
     */
    public function testEditUserValidations(array $data, array $errors)
    {
        $user = User::factory()->create();
        $response = $this->actingAs($this->user)
            ->put("/users/{$user->id}", $data);

        $response->assertStatus(302)
            ->assertInvalid($errors);
    }

    public function testReturnsErrorIfTryToEditTheAdminUser()
    {
        $adminUser = User::factory()->create(['email' => env('ADMIN_EMAIL')]);
        $response = $this->actingAs($this->user)
            ->put("/users/{$adminUser->id}", [
                'name' => 'New name',
                'email' => 'email@test.com',
            ]);

        $response->assertStatus(403)
            ->assertSee("O usuário $adminUser->name não pode ser editado");

            $response = $this->actingAs($this->user)
            ->delete("/users/{$adminUser->id}");

        $response->assertStatus(403)
            ->assertSee("O usuário $adminUser->name não pode ser excluído");
    }

    public function testCanEditAnUser()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'New name',
            'email' => $this->faker->safeEmail(),
        ];
        $response = $this->actingAs($this->user)
            ->put("/users/{$user->id}", $data);

        $response->assertStatus(302)
            ->assertValid();

        $this->assertDatabaseHas('users', $data);
    }
}
