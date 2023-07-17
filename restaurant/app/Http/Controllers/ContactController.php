<?php

namespace App\Http\Controllers;

use App\Core\Popup;
use App\Models\Contact;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create(Guard $auth)
    {
        if ($auth->check()) {
            $role = $auth->user()->role;
            $name = $auth->user()->name;
            $email = $auth->user()->email;
        } else {
            $role = '';
            $name = '';
            $email = '';
        }

        if (request()->session()->has('popup')) {
            $popup = request()->session()->get('popup');
            return view('contacts.create', compact('name', 'email', 'role', 'popup'));
        } elseif (request()->session()->has('popups')) {
            $popups = request()->session()->get('popups');
            return view('contacts.create', compact('name', 'email', 'role', 'popups'));
        }
        return view('contacts.create', compact('name', 'email', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->message = $request->message;
        $contact->save();

        $popup = new \App\Core\Popup('Envoi de message', 'success');
        $popup->addMainMessage(
            Popup::createMessage('Votre message a bien été envoyé !', 'success')
        );

        return redirect()->route('home')->with(['popup' => $popup]);
    }
}
