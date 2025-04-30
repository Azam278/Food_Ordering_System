<?php

namespace App\Http\Livewire\Auth\RestauantManager;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RestaurantManagerLogin extends Component
{
    public $email;
    public $password;

    public function render()
    {
        return view('livewire.auth.restauant-manager.restaurant-manager-login');
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
            return redirect()->route('manager.dashboard');
        } else {
            $this->addError('email', 'Invalid login credentials.');
            $this->addError('password', 'Invalid login credentials.');
        }
    }
}
