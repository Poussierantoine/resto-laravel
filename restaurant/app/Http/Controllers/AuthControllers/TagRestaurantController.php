<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Tag;
use App\Core\Popup;
use Illuminate\Http\Request;

class TagRestaurantController extends Controller
{

    public static function newTagRestaurantAssociation($tag_id, $restaurant_id)
    {
        $tag = Tag::find($tag_id);
        $restaurant = Restaurant::find($restaurant_id);
        if ($tag && $restaurant) {
            if ($tag->restaurants()->where('restaurant_id', $restaurant_id)->exists()) {
                $popup = Popup::createMessage('Le tag est déjà associé à ce restaurant', 'warnings');
            } else {
                $tag->restaurants()->attach($restaurant_id);
                $popup = Popup::createMessage('Le tag a bien été associé au restaurant', 'success');
            }
        } else {
            $popup = Popup::createMessage('Le tag ou le restaurant n\'existe pas', 'error');
        }
        return $popup;
    }

    public static function deleteTagRestaurantAssociation($tag_id, $restaurant_id)
    {
        $tag = Tag::find($tag_id);
        $restaurant = Restaurant::find($restaurant_id);
        if ($tag && $restaurant) {
            if ($tag->restaurants()->where('restaurant_id', $restaurant_id)->exists()) {
                $tag->restaurants()->detach($restaurant_id);
                $popup = Popup::createMessage('Le tag a bien été dissocié du restaurant', 'success');
            } else {
                $popup = Popup::createMessage('Le tag n\'est pas associé à ce restaurant', 'warnings');
            }
        } else {
            $popup = Popup::createMessage('Le tag ou le restaurant n\'existe pas', 'error');
        }
        return $popup;
    }
}
