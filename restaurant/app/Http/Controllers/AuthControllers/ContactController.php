<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{


    public function show()
    {
        if (request()->session()->has('popups')) {
            $popups = request()->session()->get('popups');
            return view('auth.contacts.show', compact('popups'));
        } elseif (request()->session()->has('popup')) {
            $popup = request()->session()->get('popup');
            return view('auth.contacts.show', compact('popup'));
        }
        return view('auth.contacts.show');
    }


    public static function update($id, $editForm)
    {
        if (
            $editForm['message']['value'] == null
            || strlen($editForm['message']['value']) < 3
            || strlen($editForm['message']['value']) > 255
        ) {
            $popup = new \App\Core\Popup('Modification de message', 'error');
            $popup->addMainMessage('Le contenu du message doit contenir entre 3 et 255 caractères');
            return $popup;
        }
        $contact = Contact::find($id);
        $contact->message = $editForm['message']['value'];
        $contact->save();

        $popup = new \App\Core\Popup('Modification de message', 'success');
        $popup->addMainMessage(
            \App\Core\Popup::createMessage('Message modifié avec succès !', 'success')
        );
        return $popup;
    }


    public static function delete($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        $popup = new \App\Core\Popup('Suppression de message', 'success');
        $popup->addMainMessage(
            \App\Core\Popup::createMessage('Message supprimé avec succès !', 'success')
        );
        return redirect()->route('contacts.show')->with('popup', $popup);
    }



    public static function getCrud()
    {
        return [
            'columns' => [
                'id' => '',
                'message' => 'Message',
                'updated_at' => 'Date de modification',
            ],
            'canEdit' => true,
            'isEditRemote' => false,
            'columnsAllowedToEdit' => [
                'message',
            ],
            'sortableColumns' => [
                'updated_at',
            ],
            'canDelete' => true,
        ];
    }


    public static function editForm($id)
    {
        $contact = Contact::find($id);
        return [
            'message' => [
                'type' => 'textarea',
                'value' => $contact->message,
            ],
        ];
    }
}
