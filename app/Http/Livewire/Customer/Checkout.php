<?php

namespace App\Http\Livewire\Customer;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Payment\PaymentController;
use Illuminate\Contracts\Encryption\DecryptException;

class Checkout extends Component
{
    public $order;
    public $paymentMethod = 'paypal';
    
    public function mount($encryptedOrderId)
    {
        try {
            // Decrypt the order ID
            $orderId = Crypt::decryptString($encryptedOrderId);
            
            $this->order = Order::with(['restaurant', 'orderItems.foodItem'])
                ->where('id', $orderId)
                ->where('user_id', Auth::id())
                ->where('payment_status', 'pending')
                ->firstOrFail();
        } catch (DecryptException $e) {
            // Handle decryption error
            session()->flash('error', 'Invalid order information.');
            return redirect()->route('cust.home');
        }
    }
    
    public function processPayment()
    {
        $paymentController = app(PaymentController::class);
        
        $response = $paymentController->pay([
            'amount' => $this->order->total,
            'orderId' => $this->order->id,
            'description' => 'Order #' . $this->order->id
        ]);

        // Store payment amount in session
        session(['payment_amount' => $this->order->total]);

        // Redirect to PayPal (this will break out of Livewire's XHR)
        return redirect($response);
    }
    
    public function render()
    {
        return view('livewire.customer.checkout')->layout('layouts.customer');
    }
}