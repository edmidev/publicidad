<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'names' => fake()->name() . ' ' . fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'ethnic_group' => 'Not Defined',
            'sex' => 'M',
            'birthday' => fake()->date(),
            'religion' => 'Not Defined',
            'created_at' => fake()->dateTimeBetween('-8 month ', 'now'),
        ];
    }
}
