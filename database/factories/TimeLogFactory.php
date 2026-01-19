<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->time('H:i:s');
        $endTime = fake()->boolean(70) ? fake()->time('H:i:s', $startTime) : null;

        return [
            'classroom_id' => \App\Models\ClassRoom::factory(),
            'log_date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }
}
