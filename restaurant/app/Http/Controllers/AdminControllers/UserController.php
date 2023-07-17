<?php

namespace App\Http\Controllers\AdminControllers;

use App\Core\Popup;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends AdminController
{


    public function show()
    {
        if (request()->session()->has('popups')) {
            $popups = request()->session()->get('popups');
            return view('admin.users.show', compact('popups'));
        } elseif (request()->session()->has('popup')) {
            $popup = request()->session()->get('popup');
            return view('admin.users.show', compact('popup'));
        }
        return view('admin.users.show');
    }


    public static function update($id, $editForm)
    {
        $user = User::find($id);
        $user->role = $editForm['role']['value'];
        $user->save();

        $popup = new Popup('activation/desactivation user', 'success');
        $popup->addMainMessage(
            Popup::createMessage('user activé/désactivé avec succès !', 'success')
        );
        return $popup;
    }


    public static function getCrud()
    {
        return [
            'columns' => [
                'id' => '',
                'name' => 'Nom',
                'email' => 'Email',
                'role' => 'Role',
            ],
            'sortableColumns' => [
                'name',
            ],
            'canEdit' => true,
            'isEditRemote' => false,
            'columnsAllowedToEdit' => ['role'],
            'canDelete' => false,
        ];
    }


    public static function editForm($id)
    {
        $user = User::find($id);
        return [
            'role' => [
                'type' => 'select',
                'options' => [
                    ['value' => 'admin', 'text' => 'Admin'],
                    ['value' => 'user', 'text'=> 'User'],
                ],
                'value' => $user->role,
            ],
        ];
    }










}
