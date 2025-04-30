<?php

namespace App\Http\Livewire\Auth\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class AdminRegister extends Component
{
    public $admin = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];

    protected $rules = [
        'admin.name' => 'required|string|min:3',
        'admin.email' => 'required|email|unique:users,email',
        'admin.password' => 'required|min:8',
    ];

    public function render()
    {
        return view('livewire.auth.admin.admin-register')->layout('layouts.app');
    }

    public function register()
    {
        $this->validate();
        
        User::create([
            'name' => $this->admin['name'],
            'email' => $this->admin['email'],
            'email_verified_at' => now(),
            'password' => Hash::make($this->admin['password']),
            'usr_role' => '3', 
        ]);
        
        return redirect()->route('admin.login');
    }
}
