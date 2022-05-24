<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SuspectTransactionsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testCanSeeTheTransactionsAnalysisForm()
    {
        $response = $this->actingAs($this->user)->get('/suspect-transactions');

        $response->assertStatus(200)
            ->assertSee('ANÁLISE DE TRANSAÇÕES SUSPEITAS')
            ->assertSee('form')
            ->assertSee('Selecione o mês para analisar as transações')
            ->assertSee('Nenhuma transação encontrada')
            ->assertSee('Selecione o mês para analisar as transações')
            ->assertSee('Selecione o ano para analisar as transações');
    }

    public function testAnalysisFormValidationsWorks()
    {
        $response = $this->actingAs($this->user)->post('/suspect-transactions');

        $response->assertStatus(302)
            ->assertInvalid([
                'month' => 'The month field is required.',
                'year' => 'The year field is required.',
            ]);

        $response = $this->actingAs($this->user)->post('/suspect-transactions', [
            'month' => 'invalid format',
            'year' => 'invalid format'
        ]);

        $response->assertStatus(302)
            ->assertInvalid([
                'month' => 'The month does not match the format m.',
                'year' => 'The year does not match the format Y.',
            ]);
    }

    public function testCanSeeSuspectTransactions()
    {
        $response = $this->actingAs($this->user)->post('/suspect-transactions', [
            'month' => '01',
            'year' => '2021',
        ]);

        $response->assertStatus(200)
            ->assertValid();
    }
}
