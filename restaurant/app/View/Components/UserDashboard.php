<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class UserDashboard extends Component
{


    public bool $hasRestaurant;
    public $FirstsRestaurants;
    public bool $hasComments;
    public bool $hasContacts;
    public string $userName;


    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $user = User::find(Auth::id());
        $this->userName = $user->name;
        $restaurants = $user->restaurants();
        if ($restaurants->count() > 0) {
            $this->hasRestaurant = true;
            $this->FirstsRestaurants = $restaurants->take(3)->get();
        } else {
            $this->hasRestaurant = false;
        }
        $this->hasComments = $user->comments()->count() > 0;
        $this->hasContacts = $user->contacts()->count() > 0;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-dashboard');
    }
}
