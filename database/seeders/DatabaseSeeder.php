<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\FoodItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Restaurant::factory(3)->create()->each(function ($restaurant) {
            // For each restaurant, create 3 menu categories
            MenuCategory::factory(3)->create(['restaurant_id' => $restaurant->id])->each(function ($category) {
                // For each category, create 5 food items
                FoodItem::factory(5)->create(['menu_category_id' => $category->id]);
            });
        });
        // $restaurant = Restaurant::factory()->create();
        // dd($restaurant);

        // $category = MenuCategory::factory()->create(['restaurant_id' => $restaurant->id]);
        // dd($category);

        // $foodItem = FoodItem::factory()->create(['menu_category_id' => $category->id]);
        // dd($foodItem);
    }
}
