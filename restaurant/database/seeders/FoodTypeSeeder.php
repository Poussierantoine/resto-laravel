<?php

namespace Database\Seeders;

use App\Models\FoodType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foodType = ['vegan', 'snack', 'fast-food', 'gastronomy', 'bio', 'italian_food', 'indian_food', 'tapas'];

        $dbFoodTypes = FoodType::all()->pluck('name')->toArray();

        $foodType = array_diff($foodType, $dbFoodTypes);

        if (empty($foodType)) {
            FoodType::factory()->count(10)->create();
        } else {
            foreach ($foodType as $food_type) {
                FoodType::create([
                    'name' => $food_type,
                    'active' => true,
                ]);
            }
        }
    }
}
