<?php

namespace App\Http\Livewire\Auth\RestaurantManager;

use Livewire\Component;

class RestaurantLogin extends Component
{
    public function render()
    {
        return view('livewire.auth.restaurant-manager.restaurant-login')->layout('layouts.app');
    }
}
