<?php

namespace App\Http\Livewire\Auth\Customer;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class CustRegister extends Component
{
    public $cust = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];

    protected $rules = [
        'cust.name' => 'required|string|min:3',
        'cust.email' => 'required|email|unique:users,email',
        'cust.password' => 'required|min:8',
    ];

    public function render()
    {
        return view('livewire.auth.customer.cust-register')->layout('layouts.app');
    }

    public function register()
    {
        $this->validate();
        
        try{
            User::where('email', $this->cust['email'])->firstOrFail();
            $this->emit('swal:alert',[
                'position' => 'top',
                'icon' => 'error',
                'title' => " Email already exists",
                'timerProgressBar' => true,
            ]);
            return;
        } catch (\Exception $e) {
            // Email does not exist, proceed with registration
            try{
                DB::beginTransaction();
                
                User::create([
                    'name' => $this->cust['name'],
                    'email' => $this->cust['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($this->cust['password']),
                    'usr_role' => '1', 
                ]);

                DB::commit();
                $this->emit('swal:alert',[
                    'position' => 'top',
                    'icon' => 'success',
                    'title' => "Successfully registered",
                    'timerProgressBar' => true,
                ]);
                return redirect()->route('cust.login');
            } catch(QueryException $e) {
                DB::rollBack();
                $this->emit('swal:alert',[
                    'position' => 'top',
                    'icon' => 'error',
                    'title' => " Registration failed",
                    'timerProgressBar' => true,
                ]);
                return;
            }
        }
    }
}
