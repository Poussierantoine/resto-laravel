<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;


class RestaurantController extends Controller
{


    public function index()
    {
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            $thumbnailPath = explode('/', $restaurant->image);
            $name = $thumbnailPath[count($thumbnailPath) - 1];
            $thumbnailPath[count($thumbnailPath) - 1] = "thumbnail/thumb_" . $name;
            $thumbnailPath = implode('/', $thumbnailPath);
            $restaurant->thumbnail = $thumbnailPath;
        }

        if (FacadesRequest::session()->has('popup')) {
            $popup = FacadesRequest::session()->get('popup');
            return view('welcome', compact('restaurants', 'popup'));
        } elseif (FacadesRequest::session()->has('popups')) {
            $popup = FacadesRequest::session()->get('popups');
            return view('welcome', compact('restaurants', 'popups'));
        }
        return view('restaurants.restaurants', compact('restaurants'));
    }

    public function show(Request $request, $url)
    {
        $restaurant = Restaurant::where('url', $url)->first();
        $restaurantName = $restaurant->name;
        $restaurantDescription = $restaurant->description;
        $restaurantId = $restaurant->id;
        $restaurantImage = $restaurant->image;
        $owner = $restaurant->user->name;
        $tags = $restaurant->tags()->get();
        $foodTypes = $restaurant->foodTypes()->get();
        $comments = [];
        foreach ($restaurant->comments()->select('content', 'user_id')->get() as $comment) {
            $comments[] = ["name" => $comment->user->name, "content" => $comment->content];
        }
        
        $role = (auth()->check())? User::find(auth()->user()->id)->role : 'guest';
        

        if ($request->session()->has('popup')) {
            $popup = $request->session()->get('popup');
            return view(
                'restaurants.restaurant',
                compact(
                    'restaurantName',
                    'restaurantDescription',
                    'restaurantId',
                    'tags',
                    'foodTypes',
                    'owner',
                    'comments',
                    'role',
                    'popup',
                    'restaurantImage'
                )
            );
        }
        return view(
            'restaurants.restaurant',
            compact(
                'restaurantName',
                'restaurantDescription',
                'restaurantId',
                'tags',
                'foodTypes',
                'owner',
                'comments',
                'role',
                'restaurantImage'
            )
        );
    }
}
