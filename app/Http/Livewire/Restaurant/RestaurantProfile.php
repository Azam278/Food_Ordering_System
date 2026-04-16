<?php

namespace App\Http\Livewire\Restaurant;

use Livewire\Component;
use App\Models\Restaurant;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RestaurantProfile extends Component
{
    use WithFileUploads;

    public $restaurantId;
    public $restaurantProfile = [];
    public $logo;
    public $logoPreview;

    public function mount()
    {
        $user = auth()->user();
        $restaurant = Restaurant::where('user_id', $user->id)->first();

        if ($restaurant) {
            $this->restaurantId = $restaurant->id;
            $this->restaurantProfile = [
                'name' => $restaurant->name,
                'description' => $restaurant->description,
                'address' => $restaurant->address,
                'phone' => $restaurant->phone,
                'is_active' => $restaurant->is_active,
                'is_approved' => $restaurant->is_approved,
            ];

            $this->logoPreview = asset('storage/' . $restaurant->logo);
        } else {
            // Default for new restaurant
            $this->restaurantProfile = [
                'name' => '',
                'description' => '',
                'address' => '',
                'phone' => '',
                'is_active' => true,
                'is_approved' => false,
            ];
        }
    }

    public function updatedLogo()
    {
        $this->logoPreview = $this->logo->temporaryUrl();
    }

    public function saveRestaurant()
    {
        try {
            $validatedData = $this->validate([
                'restaurantProfile.name' => 'required|string|max:255',
                'restaurantProfile.description' => 'nullable|string',
                'restaurantProfile.address' => 'required|string|max:255',
                'restaurantProfile.phone' => 'required|string|max:20',
                'restaurantProfile.is_active' => 'boolean',
            ]);

            DB::beginTransaction();

            $logoPath = null;
            if ($this->logo) {
                $logoPath = $this->logo->store('logos', 'public');
            }

            $dataToSave = array_merge($validatedData['restaurantProfile'], [
                'user_id' => auth()->id(),
            ]);

            if ($logoPath) {
                $dataToSave['logo'] = $logoPath;
            }

            if ($this->restaurantId) {
                // Update existing restaurant
                $restaurant = Restaurant::findOrFail($this->restaurantId);
                $restaurant->update($dataToSave);
            } else {
                // Create new restaurant
                $restaurant = Restaurant::create($dataToSave);
                $this->restaurantId = $restaurant->id;
            }

            DB::commit();
            $this->emit('swal:alert', [
                'position' => 'top',
                'icon' => 'success',
                'title' => "Restaurant saved successfully.",
                'timerProgressBar' => true,
            ]);
            return redirect()->route('manager.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit('swal:alert', [
                'position' => 'top',
                'icon' => 'error',
                'title' => "Something went wrong",
                'timerProgressBar' => true,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.restaurant.restaurant-profile')->layout('layouts.restaurant');
    }
}
