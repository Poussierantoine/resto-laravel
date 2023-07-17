<?php

namespace App\Http\Controllers\AdminControllers;

use App\Core\Popup;
use App\Http\Controllers\Controller;
use App\Models\FoodType;
use Illuminate\Http\Request;

class FoodTypeController extends AdminController
{


    public function show()
    {
        if (request()->session()->has('popups')) {
            $popups = request()->session()->get('popups');
            return view('admin.foodTypes.show', compact('popups'));
        } elseif (request()->session()->has('popup')) {
            $popup = request()->session()->get('popup');
            return view('admin.foodTypes.show', compact('popup'));
        }
        return view('admin.foodTypes.show');
    }


    public static function update($id, $editForm)
    {
        $foodType = FoodType::find($id);
        $foodType->active = $editForm['active']['value'];
        $foodType->save();

        $popup = new Popup('activation/desactivation foodType', 'success');
        $popup->addMainMessage(
            Popup::createMessage('foodType activé/désactivé avec succès !', 'success')
        );
        return $popup;
    }


    public static function getCrud()
    {
        return [
            'columns' => [
                'id' => '',
                'name' => 'Nom',
                'active' => 'Activé',
            ],
            'sortableColumns' => [
                'name',
            ],
            'canEdit' => true,
            'isEditRemote' => false,
            'columnsAllowedToEdit' => ['active'],
            'canDelete' => false,
        ];
    }


    public static function editForm($id)
    {
        $foodType = FoodType::find($id);
        return [
            'active' => [
                'type' => 'select',
                'value' => $foodType->active,
                'options' => [
                    ['value' => 1, 'text' => 'Oui'],
                    ['value' => 0, 'text' => 'Non']
                ]
            ]
        ];
    }
}
