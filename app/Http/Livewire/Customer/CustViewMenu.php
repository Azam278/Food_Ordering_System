<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Crypt;

class CustViewMenu extends Component
{
    public $restaurant;
    public $restaurantId;
    public $foodItems = [];
    public $cartCount = 0;
    public $cartItems = [];
    
    public function render()
    {
        return view('livewire.customer.cust-view-menu')->layout('layouts.customer');
    }

    public function mount($restaurantId)
    {
        $this->restaurantId = $restaurantId;

        try {
            $decryptedId = Crypt::decryptString($restaurantId);
            $this->restaurant = Restaurant::with('foodItems','categories')->findOrFail($decryptedId);
            $this->foodItems = $this->restaurant->foodItems;
            
            // Initialize cart data from session if exists
            $this->cartItems = session()->get('cartItems', []);
            $this->cartCount = count($this->cartItems);
            
            // Share cart count with other components
            $this->emit('updateCartCount', $this->cartCount);
            
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Restaurant not found');
        }
    }
    
    public function addToCart($foodItemId)
    {
        $foodItem = FoodItem::find($foodItemId);
        
        if (!$foodItem) {
            session()->flash('error', 'Food item not found!');
            return;
        }
        
        // Check if item already exists in cart
        $existingItem = false;
        foreach ($this->cartItems as $key => $item) {
            if ($item['id'] == $foodItemId) {
                $this->cartItems[$key]['quantity']++;
                $existingItem = true;
                break;
            }
        }
        
        // If item doesn't exist in cart, add it
        if (!$existingItem) {
            $this->cartItems[] = [
                'id' => $foodItem->id,
                'name' => $foodItem->name,
                'price' => $foodItem->price,
                'quantity' => 1,
                'image' => $foodItem->image,
                'restaurant_id' => $foodItem->restaurant_id
            ];
        }
        
        // Save cart to session
        session()->put('cartItems', $this->cartItems);
        
        // Update cart count
        $this->cartCount = count($this->cartItems);
        
        // Notify other components
        $this->emit('updateCartCount', $this->cartCount);
        
        // Flash success message
        session()->flash('success', $foodItem->name . ' added to cart!');
    }
    
    public function updateCartCount($count)
    {
        $this->cartCount = $count;
    }
}
