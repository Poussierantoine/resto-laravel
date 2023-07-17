<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Navbar extends Component
{
    public string $role;
    public string $userName = '';
    public string $userEmail = '';
    public string $profilePic = '';

    public function mount()
    {
        $user = auth()->user();
        $this->role = $user->role ?? 'guest';
        if ($this->role !== 'guest') {
            $this->userName = $user->name;
            $this->profilePic = $user->profile_photo_url;
            $this->userEmail = $user->email;
        }
    }

    /**
     * The component's listeners.
     *
     * @var array
     */
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.navbar');
    }
}
