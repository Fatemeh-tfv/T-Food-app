<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FoodItemReview;

class FoodItemReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Make sure there are users and food items before seeding reviews
        \App\Models\User::factory(10)->create();
        \App\Models\FoodItem::factory(10)->create();

        // Generate 30 random reviews
        FoodItemReview::factory(30)->create();
    }
}
