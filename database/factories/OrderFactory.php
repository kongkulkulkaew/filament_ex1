<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'order_number' => 'ORD-' . str_pad((string) $this->faker->unique()->numberBetween(100000, 999999), 6, '0', STR_PAD_LEFT),
            'status' => $this->faker->randomElement(OrderStatus::cases()),
            'shipping_address' => $this->faker->address(),
            'notes' => $this->faker->optional()->sentence(),
            'subtotal' => 0,
            'total' => 0,
        ];
    }
}
