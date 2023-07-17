<?php

namespace App\Http\Controllers\AdminControllers;

use App\Core\Popup;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends AdminController
{
    
    public function show()
    {
        if(request()->session()->has('popups')){
            $popups = request()->session()->get('popups');
            return view('admin.contacts.show', compact('popups'));
        }elseif(request()->session()->has('popup')){
            $popup = request()->session()->get('popup');
            return view('admin.contacts.show', compact('popup'));
        }
        return view('admin.contacts.show');
    }



    public static function delete($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        $popup = new Popup('suppression contact', 'success');
        $popup->addMainMessage(
            Popup::createMessage('contact supprimé avec succès !', 'success')
        );
        return redirect()->route('admin.contacts.show')->with('popup', $popup);
    }


    public static function getCrud()
    {
        return [
            'columns' => [
                'id' => '',
                'name' => 'Nom',
                'email' => 'Email',
                'message' => 'Message',
                'updated_at' => 'Date de modification',
            ],
            'canEdit' => false,
            'canDelete' => true,
        ];
    }
}
