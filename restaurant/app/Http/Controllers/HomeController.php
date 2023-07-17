<?php

namespace App\Http\Controllers;

use App\Core\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Livewire\Request as LivewireRequest;

class HomeController extends Controller
{

    public function index()
    {
        $restaurants = \App\Models\Restaurant::where('id', '<', 5)->get();
        if (FacadesRequest::session()->has('popup')) {
            $popup = FacadesRequest::session()->get('popup');
            return view('welcome', compact('restaurants', 'popup'));
        } elseif (FacadesRequest::session()->has('popups')) {
            $popup = FacadesRequest::session()->get('popups');
            return view('welcome', compact('restaurants', 'popups'));
        }
        return view('welcome', compact('restaurants'));
    }
}
