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
                'date' => 'The date field is required.',
            ]);

        $response = $this->actingAs($this->user)->post('/suspect-transactions', [
            'date' => 'invalid format',
        ]);

        $response->assertStatus(302)
            ->assertInvalid([
                'date' => 'The date is not a valid date.',
            ]);
    }

    // public function testWithoutAnyTransactionsShowsTheCorrectMessage()
    // {
    //     $response = $this->actingAs($this->user)->
    // }
}
