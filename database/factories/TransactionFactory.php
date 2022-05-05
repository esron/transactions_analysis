<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Import;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'origin_account_id' => Account::factory(),
            'destiny_account_id' => Account::factory(),
            'import_id' => Import::factory(),
            'amount' => $this->faker->randomNumber(5, false),
        ];
    }
}
