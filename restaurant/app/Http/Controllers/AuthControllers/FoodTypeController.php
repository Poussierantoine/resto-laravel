<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\FoodType;
use App\Core\Popup;
use Illuminate\Http\Request;

class FoodTypeController extends Controller
{


    /**
     * demande l'ajout d'un nouveau foodtype et le crée avec $active = false
     * @param string $name
     * @return array popup a afficher dans le redirect
     */
    public static function newFoodTypeRequest($name)
    {
        $name = ucfirst(strtolower($name));
        if (FoodType::where('name', $name)->exists()) {
            $popup = Popup::createMessage(
                'Le type de cuisine demandé existe déjà, vous avez été affecté à ce type de cuisine',
                'warnings'
            );
        } else {
            $foodtype = new FoodType();
            $foodtype->name = $name;
            $foodtype->active = false;
            $foodtype->save();
            

            $popup = Popup::createMessage(
                'Votre demande a bien été prise en compte, vous serez affecté à ce type de cuisine dès que possible',
                'success'
            );
        }
        return $popup;
    }


    /**
     * rend un foodtype actif
     * @param int $foodtype_id
     * @return array popup a afficher dans le redirect
     */
    public static function activation($foodtype_id)
    {
        $foodtype = FoodType::find($foodtype_id);
        if ($foodtype) {
            $foodtype->active = true;
            $foodtype->save();
            $popup = Popup::createMessage('Le type de cuisine a bien été activé', 'success');
        } else {
            $popup = Popup::createMessage('Le type de cuisine n\'existe pas', 'error');
        }
        return $popup;
    }
}
