<?php

namespace Database\Factories;

use App\Models\FoodType;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodTypeRestaurant>
 */
class FoodTypeRestaurantFactory extends Factory
{


    /**
     * Liste des associations restaurant/food_type deja faites 
     * @var Illuminate\Support\Collection
     */
    protected static $uniqueCombinaison;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /**
         * initialisation de la liste des associations restaurant/food_type deja faites
         */
        if (self::$uniqueCombinaison == null) {
            self::$uniqueCombinaison = new Collection();
        }
        /**
         * on recupere les association de la DB et on les ajoute a la liste des associations deja faites si elles n'y sont pas deja
         */
        $db_datas = DB::table('food_type_restaurant')->select(['restaurant_id', 'food_type_id'])->get();
        foreach ($db_datas as $db_data) {
            if(!self::$uniqueCombinaison->contains([$db_data->restaurant_id, $db_data->food_type_id])){
                self::$uniqueCombinaison->add([$db_data->restaurant_id, $db_data->food_type_id]);
            }
        }

        /**
         * on recupere les id des restaurants et food_types dans des array qu'on melange aleatoirement
         * si une table est vide, on créera un restaurant ou un food_type aleatoirement pour l'association
         */
        $numberOfRestaurants = DB::table('restaurants')->count();

        if ($numberOfRestaurants > 0) {
            $available_restaurants = range(1, $numberOfRestaurants);
            shuffle($available_restaurants);
        } else {
            $available_restaurants = [Restaurant::factory()->create()->id];
        }


        $numberOfFoodTypes = DB::table('food_types')->count();

        if ($numberOfFoodTypes > 0) {
            $available_foodTypes = range(1, $numberOfFoodTypes);
            shuffle($available_foodTypes);
        } else {
            $available_foodTypes = [FoodType::factory()->create()->id];
        }

        /**
         * on cherche une association restaurant/food_type qui n'existe pas encore dans la table (en recherchant dans self::$uniqueCombinaison)
         * on itere sur les array des id des restaurants / foodtypes possibles
         * si aucune association n'est trouvée cela signifie que toutes les associations sont faites, l'iteration s'arrete
         * les array sont melanges aleatoirement donc on peut pop() sans que ca soit ordonné
         */
        $associationFound = false;

        while (!$associationFound) {
            if (count($available_restaurants) == 0) {
                break;
            } else {
                $restaurant_id = array_pop($available_restaurants);
            }
            //creation d'une copie de l'array des food_types
            $available_foodTypesCopy = $available_foodTypes;
            while (!$associationFound && count($available_foodTypesCopy) > 0) {
                if (count($available_foodTypesCopy) == 0) {
                    break;
                } else {
                    $foodTypeId = array_pop($available_foodTypesCopy);
                }

                if (!self::$uniqueCombinaison->contains([$restaurant_id, $foodTypeId])) {
                    $associationFound = true;
                }
            }
        }
        
        /**
         * si aucune association n'a été trouvée on en cree une aleatoirement
         * en créant soit un nouveau restaurant soit un nouveau food_type et en associant l'autre a un restaurant/foodtype existant
         */
        if (!$associationFound) {
            $restaurant_OR_foodType = rand(0, 1);
            if ($restaurant_OR_foodType == 0) {
                $restaurant_id = Restaurant::factory()->create()->id;
                $foodTypeId = rand(1, $numberOfFoodTypes);
            } else {
                $foodTypeId = FoodType::factory()->create()->id;
                $restaurant_id = rand(1, $numberOfRestaurants);
            }
        }
        
        /**
         * on ajoute l'association a la liste des associations deja faites
         */
        self::$uniqueCombinaison->add([$restaurant_id, $foodTypeId]);

        return [
            'restaurant_id' => $restaurant_id,
            'food_type_id' => $foodTypeId,
        ];
    }
}
