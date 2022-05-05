<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Import;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testCanSeeTheTransactionsTable()
    {
        $import = Import::factory()
            ->has(Transaction::factory()->count(3))
            ->create();
        $response = $this->actingAs($this->user)
            ->get("/transactions/{$import->id}");

        $response->assertStatus(200);
    }
}
