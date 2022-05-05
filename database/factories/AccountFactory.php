<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bank_name' => $this->faker->company(),
            'branch' => $this->faker->numerify('#####'),
            'number' => $this->faker->numerify('#####-#'),
        ];
    }
}
