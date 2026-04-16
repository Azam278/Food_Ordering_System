<?php

namespace App\Http\Livewire\Admin;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminDashboard extends Component
{
    protected $listeners = ['logout'];

    public $totalApproved, $totalPendingApproval;

    public function mount(){
        $this->totalApproved = Restaurant::where('is_approved', true)->count();
        $this->totalPendingApproval = Restaurant::where('is_approved', false)->count();
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')->layout('layouts.admin');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }
}
