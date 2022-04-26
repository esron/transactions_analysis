<?php

namespace Tests\Feature\app\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateDefaultUserCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanCreateDefaultUser()
    {
        $this->artisan('create:default-user')
            ->expectsOutput('User Admin created')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@email.com.br',
            'name' => 'Admin',
        ]);
    }
}
