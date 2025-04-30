<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class RestaurantApproval extends Component
{
    public $restaurantApproval;
    public $selectedRestaurant;

    public function render()
    {
        return view('livewire.admin.restaurant-approval')->layout('layouts.admin');
    }

    public function mount(){
        $this->restaurantApproval = Restaurant::where('is_approved', false)->get();
    }

    public function modalApprovalRestaurant($restaurantId){
        $this->selectedRestaurant = Restaurant::find($restaurantId);
        $this->emit('showModal', 'modalApprovalRestaurant');
    }

    public function approveRestaurant()
    {
        try {
            DB::beginTransaction();
            
            // Find and update the restaurant
            Restaurant::where('id', $this->selectedRestaurant->id)
                ->update(['is_approved' => true, 'is_active' => true]);
            
            DB::commit();

            $this->emit('swal:alert',[
                'position' => 'top',
                'icon' => 'success',
                'title' => "Successfully Approved Restaurant: \n" . $this->selectedRestaurant->name,
                'timerProgressBar' => true,
            ]);
            
            $this->restaurantApproval = Restaurant::where('is_approved', false)->get();
            
            $this->emit('hideModal', 'modalApprovalRestaurant');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit(event: 'swal:alert', params: [
                'position' => 'top',
                'icon' => 'error',
                'title' => 'Failed to approve restaurant: ' . $e->getMessage(),
                'timerProgressBar' => true,
            ]);
        }
    }
}
