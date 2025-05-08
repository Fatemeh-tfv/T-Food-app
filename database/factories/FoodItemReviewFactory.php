<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FoodItem;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodItemReview>
 */
class FoodItemReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'food_item_id' => FoodItem::inRandomOrder()->first()->id ?? FoodItem::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'review' => $this->faker->sentence(),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}

