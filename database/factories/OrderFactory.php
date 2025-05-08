<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Restaurant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'restaurant_id' => Restaurant::factory(),
            'total_price' => $this->faker->randomFloat(2, 20, 200),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'delivered']),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
