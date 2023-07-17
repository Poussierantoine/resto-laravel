<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{

    protected $status = ['Ouvert', 'Fermé'];
    /**
     * crée les parametres à rentrer lors de la création d'un restaurant
     * le champ image correspond aux images placeholder sous la forme resto1.jpg, resto2.jpg, etc...
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        /**
         * recuperation du nombre de user créés,
         * il y aura 1 chance sur 4 pour que le restaurant utilise un user, 3/4 qu'il en crée un nouveau
         */
        $numberOfUsers = DB::table('users')->where('role', 'user')->count();


        /**
         * creation du titre du restaurant
         */
        $title = fake()->company();
        return [
            'user_id' => ($numberOfUsers > 0 && random_int(1, 2) === 1) ?
                User::where('role', 'user')->select(['id'])->get()->random() : User::factory(),
            'name' => $title,
            'description' => fake()->paragraph(10),
            'url' => str_replace([',', ' '], ['', '-'], $title),
            'price' => random_int(1, 50) . '-' . random_int(51, 100) . ' €',
            'status' => $this->status[random_int(0, count($this->status) - 1)],
            'image' => "public/images/restaurants/restaurantPlaceholder/resto" . random_int(1, 8) . ".jpg",
            'active' => random_int(0, 1),
        ];
    }
}
