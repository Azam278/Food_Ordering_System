<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;

class CartCounter extends Component
{
    public $cartCount = 0;
    
    protected $listeners = ['updateCartCount'];
    
    public function mount()
    {
        $cartItems = session()->get('cartItems', []);
        $this->cartCount = count($cartItems);
    }
    
    public function render()
    {
        return view('livewire.customer.cart-counter');
    }
    
    public function updateCartCount($count)
    {
        $this->cartCount = $count;
    }
}
