<?php

namespace App\Http\Controllers\AdminControllers;

use App\Core\Popup;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends AdminController
{
    public function show()
    {
        $tags = Tag::all();
        if (request()->session()->has('popups')) {
            $popups = request()->session()->get('popups');
            return view('admin.tags.show', compact('popups'));
        } elseif (request()->session()->has('popup')) {
            $popup = request()->session()->get('popup');
            return view('admin.tags.show', compact('popup'));
        }
        return view('admin.tags.show');
    }


    public static function update($id, $editForm)
    {
        $tag = Tag::find($id);
        $tag->active = $editForm['active']['value'];
        $tag->save();

        $popup = new Popup('activation/desactivation tag', 'success');
        $popup->addMainMessage(
            Popup::createMessage('tag activé/désactivé avec succès !', 'success')
        );
        return $popup;
    }


    public static function delete($id)
    {
        $tag = Tag::find($id);
        $tag->delete();

        $popup = new Popup('suppression tag', 'success');
        $popup->addMainMessage(
            Popup::createMessage('tag supprimé avec succès !', 'success')
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
            'canDelete' => true,
        ];
    }


    public static function editForm($id)
    {
        $tag = Tag::find($id);
        return [
            'active' => [
                'type' => 'select',
                'value' => "$tag->active",
                'options' => [
                    ['value' => 1, 'text' => 'Oui'],
                    ['value' => 0, 'text' => 'Non']
                ]
            ]
        ];
    }
}
