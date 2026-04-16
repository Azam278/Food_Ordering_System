<?php

namespace App\Http\Livewire\Auth\RestauantManager;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class RestaurantManagerRegister extends Component
{
    public $managerRestaurant = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];

    protected $rules = [
        'managerRestaurant.name' => 'required|string|min:3',
        'managerRestaurant.email' => 'required|email|unique:users,email',
        'managerRestaurant.password' => 'required|min:8',
    ];

    public function render()
    {
        return view('livewire.auth.restauant-manager.restaurant-manager-register')->layout('layouts.app');
    }

    public function register()
    {
        $this->validate();
        
        try{
            User::where('email', $this->managerRestaurant['email'])->firstOrFail();
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
                    'name' => $this->managerRestaurant['name'],
                    'email' => $this->managerRestaurant['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($this->managerRestaurant['password']),
                    'usr_role' => '2', 
                ]);

                Restaurant::create([
                    'user_id' => User::where('email', $this->managerRestaurant['email'])->first()->id,
                    'name' => $this->managerRestaurant['name'],
                ]);

                DB::commit();
                $this->emit('swal:alert',[
                    'position' => 'top',
                    'icon' => 'success',
                    'title' => "Successfully registered",
                    'timerProgressBar' => true,
                ]);
                return redirect()->route('manager.login');
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
