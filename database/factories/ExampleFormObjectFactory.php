<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExampleFormObject>
 */
class ExampleFormObjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'full_name' => fn (array $attributes) => trim("{$attributes['first_name']} {$attributes['last_name']}"),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'avatar' => null,
            'department' => fake()->randomElement(['it', 'hr', 'finance', 'marketing']),
            'skills' => [],
            'salary' => fake()->numberBetween(20000, 150000),
            'start_date' => fake()->dateTimeBetween('now', '+1 year'),
            'documents' => [],
            'status' => fake()->randomElement(['active', 'inactive', 'suspended']),
            'note' => null,
        ];
    }
}
