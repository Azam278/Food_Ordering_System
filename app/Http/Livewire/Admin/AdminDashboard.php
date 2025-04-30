<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminDashboard extends Component
{
    protected $listeners = ['logout'];

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
