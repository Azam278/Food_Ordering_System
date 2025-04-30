<?php

namespace App\Http\Livewire\Auth\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CustLogin extends Component
{
    public $email;
    public $password;

    public function render()
    {
        return view('livewire.auth.customer.cust-login')->layout('layouts.app');
    }

    public function login()
    {        
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if(Auth::attempt(['email' => $this->email,'password' => $this->password])){
            $this->emit('swal:alert',[
                'position' => 'top',
                'icon' => 'success',
                'title' => "Successfully login",
                'timerProgressBar' => true,
            ]);
            return redirect()->route('cust.dashboard');
        } else {
            $this->addError('email', 'Invalid login credentials.');
            $this->addError('password', 'Invalid login credentials.');
        }
    }
}
