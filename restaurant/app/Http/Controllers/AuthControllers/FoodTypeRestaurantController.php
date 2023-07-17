<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\FoodType;
use App\Models\Restaurant;
use App\Core\Popup;
use Illuminate\Http\Request;

class FoodTypeRestaurantController extends Controller
{


    public static function newFoodTypeRestaurantAssociation($foodtype_id, $restaurant_id)
    {
        $foodtype = FoodType::find($foodtype_id);
        $restaurant = Restaurant::find($restaurant_id);
        if ($foodtype && $restaurant) {
            if ($foodtype->restaurants()->where('restaurant_id', $restaurant_id)->exists()) {
                $popup = Popup::createMessage('Le type de cuisine est déjà associé à ce restaurant', 'warnings');
            } else {
                $foodtype->restaurants()->attach($restaurant_id);
                $popup = Popup::createMessage('Le type de cuisine a bien été associé au restaurant', 'success');
            }
        } else {
            $popup = Popup::createMessage('Le type de cuisine ou le restaurant n\'existe pas', 'error');
        }
        return $popup;
    }

    public static function deleteFoodTypeRestaurantAssociation($foodtype_id, $restaurant_id)
    {
        $foodtype = FoodType::find($foodtype_id);
        $restaurant = Restaurant::find($restaurant_id);
        if ($foodtype && $restaurant) {
            if ($foodtype->restaurants()->where('restaurant_id', $restaurant_id)->exists()) {
                $foodtype->restaurants()->detach($restaurant_id);
                $popup = Popup::createMessage('Le type de cuisine a bien été dissocié du restaurant', 'success');
            } else {
                $popup = Popup::createMessage('Le type de cuisine n\'est pas associé à ce restaurant', 'warnings');
            }
        } else {
            $popup = Popup::createMessage('Le type de cuisine ou le restaurant n\'existe pas', 'error');
        }
        return $popup;
    }
}
