<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Restaurant::create([
            'user_id' => 2,
            'name' => 'Le Petit Bouchon',
            'description' => 'Le Petit Bouchon est un restaurant de cuisine française situé à Paris.
            Il est situé dans le 1er arrondissement, dans le quartier du Louvre,
            à proximité de la place Vendôme et de la rue de Rivoli.
            Il est dirigé par le chef étoilé Jean-François Piège.',
            'url' => 'le-petit-bouchon',
            'price' => '50-2000 €',
            'status' => 'Ouvert',
            'image' => 'images/restaurants/restaurantPlaceholder/resto1.jpg',
            'active' => true,
        ]);

        Restaurant::create([
            'user_id' => 2,
            'name' => 'Le Resto 2',
            'description' => 'Le Resto 2 est un restaurant.',
            'url' => 'le-resto2',
            'price' => '50-2000 €',
            'status' => 'Ouvert',
            'image' => 'images/restaurants/restaurantPlaceholder/resto2.jpg',
            'active' => false,
        ]);

        Restaurant::factory(10)->create();
        
    }
}
