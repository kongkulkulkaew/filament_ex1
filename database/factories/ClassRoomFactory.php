<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    protected $model = ClassRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grades = ['ม.1', 'ม.2', 'ม.3', 'ม.4', 'ม.5', 'ม.6'];
        $rooms = ['1', '2', '3', '4', '5'];
        $grade = fake()->randomElement($grades);
        $room = fake()->randomElement($rooms);

        return [
            'name' => "{$grade}/{$room}",
            'code' => strtoupper(str_replace(['ม.', '/'], ['M', '-'], "{$grade}/{$room}")).'-'.fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
