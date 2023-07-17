<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /**
         * compte des data dans les tables pour les utiliser si elles existent et en créer sinon
         */
        $numberOfUsers = DB::table('users')->where('role', 'user')->count();
        $numberOfRestaurants = DB::table('restaurants')->count();
        return [
            'user_id' => ($numberOfUsers>0) ?
            User::where('role', 'user')->select(['id'])->get()->random()
            : User::factory(),
            'restaurant_id' => ($numberOfRestaurants>0) ? Restaurant::all()->random() : Restaurant::factory(),
            'content' => fake()->text(),
        ];
    }
}
