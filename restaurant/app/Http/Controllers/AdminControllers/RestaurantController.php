<?php

namespace App\Http\Controllers\AdminControllers;

use App\Core\Popup;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends AdminController
{
    

    public function show()
    {
        if (request()->session()->has('popups')) {
            $popups = request()->session()->get('popups');
            return view('admin.restaurants.show', compact('popups'));
        } elseif (request()->session()->has('popup')) {
            $popup = request()->session()->get('popup');
            return view('admin.restaurants.show', compact('popup'));
        }
        return view('admin.restaurants.show');
    }


    public static function update($id, $editForm)
    {
        $restaurant = Restaurant::find($id);
        $restaurant->active = $editForm['active']['value'];
        $restaurant->save();

        $popup = new Popup('activation/desactivation restaurant', 'success');
        $popup->addMainMessage(
            Popup::createMessage('restaurant activé/désactivé avec succès !', 'success')
        );
        return $popup;
    }


    public static function getCrud()
    {
        return [
            'columns' => [
                'id' => '',
                'name' => 'Nom',
                'description' => 'Description',
                'image' => 'Image',
                'tags' => 'Tags',
                'foodTypes' => 'Types de plats',
                'status' => 'Statut',
                'updated_at' => 'Dernière modification',
                'active' => 'Activé',
            ],
            'sortableColumns' => [
                'name',
                'updated_at',
                'status',
                'active'
            ],
            'canEdit' => true,
            'isEditRemote' => false,
            'columnsAllowedToEdit' => ['active'],
            'canDelete' => false,
            'modelLinks' => [
                'tags' => [
                    'columnToDisplay' => 'name',
                    'methodName' => 'tags',
                ],
                'foodTypes' => [
                    'columnToDisplay' => 'name',
                    'methodName' => 'foodTypes',
                ],
            ],
            'imagesColumns' => [
                'image',
            ],
            
        ];
    }


    public static function editForm($id)
    {
        $restaurant = Restaurant::find($id);
        return [
            'active' => [
                'type' => 'select',
                'value' => $restaurant->active,
                'options' => [
                    ['value' => 1, 'text' => 'Oui'],
                    ['value' => 0, 'text' => 'Non']
                ]
            ],
        ];
    }
}
