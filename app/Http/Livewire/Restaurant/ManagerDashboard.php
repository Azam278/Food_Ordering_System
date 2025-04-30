<?php

namespace App\Http\Livewire\Restaurant;

use Livewire\Component;
use App\Models\Restaurant;

class ManagerDashboard extends Component
{
    public $approvedRestaurants, $pendingRestaurants;

    public function render()
    {
        return view('livewire.restaurant.manager-dashboard')->layout('layouts.restaurant');
    }

    public function mount()
    {
        $this->approvedRestaurants = Restaurant::where('is_approved', true)->count();
        $this->pendingRestaurants = Restaurant::where('is_approved', false)->count();
    }
}
