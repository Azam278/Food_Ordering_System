<?php

namespace App\Http\Livewire\Customer;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class OrderConfirmation extends Component
{
    public $order;
    
    public function mount($encryptedOrderId)
    {
        try {
            $orderId = Crypt::decryptString($encryptedOrderId);

            $this->order = Order::with([
                'restaurant', 
                'orderItems.foodItem', 
                'payment', 
                'loyaltyTransaction'
            ])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        } catch (DecryptException $e) {
            session()->flash('error', 'Invalid order information.');
            return redirect()->route('customer.cart');
        }
    }
    
    public function render()
    {
        return view('livewire.customer.order-confirmation')
            ->layout('layouts.customer');
    }
}
