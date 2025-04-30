<?php

namespace App\Http\Livewire\Auth\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminLogin extends Component
{
    public $email;
    public $password;

    public function render()
    {
        return view('livewire.auth.admin.admin-login')->layout('layouts.app');  
    }

    public function login()
    {
        
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if(Auth::attempt(['email' => $this->email,'password' => $this->password])){
            return redirect()->route('admin.dashboard');
        } else {
            $this->addError('email', 'Invalid login credentials.');
            $this->addError('password', 'Invalid login credentials.');
        }
    }
}
