<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RestaurantSeeder::class,
            CommentSeeder::class,
            ContactSeeder::class,
            FoodTypeSeeder::class,
            TagSeeder::class,
            TagRestaurantSeeder::class,
            FoodTypeRestaurantSeeder::class,
        ]);
    }
}
