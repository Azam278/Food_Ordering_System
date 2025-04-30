<?php

namespace App\Http\Livewire\Customer;

use App\Models\Order;
use Livewire\Component;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ViewCart extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $tax = 0;
    public $deliveryFee = 5.00; // Default delivery fee
    public $total = 0;
    public $notes = '';
    public $deliveryMethod = 'pickup'; // Default to pickup
    public $deliveryAddress = '';
    public $restaurant = null;
    
    protected $listeners = ['updateCart'];
    
    protected $rules = [
        'deliveryMethod' => 'required|in:pickup,delivery',
        'deliveryAddress' => 'required_if:deliveryMethod,delivery',
        'notes' => 'nullable|string|max:255'
    ];
    
    public function mount()
    {
        $this->cartItems = session()->get('cartItems', []);
        
        if (!empty($this->cartItems)) {
            // Get restaurant info for the first item in cart
            // Assumes all items are from the same restaurant
            $firstItem = reset($this->cartItems);
            $this->restaurant = Restaurant::find($firstItem['restaurant_id']);
            
            // Load user's default address if available
            $this->deliveryAddress = Auth::user()->address ?? '';
        }
        
        $this->calculateTotals();
    }
    
    public function render()
    {
        return view('livewire.customer.view-cart')->layout('layouts.customer');
    }
    
    public function updateCart()
    {
        $this->cartItems = session()->get('cartItems', []);
        $this->calculateTotals();
    }
    
    public function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->cartItems as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        
        // Calculate tax (6% of subtotal)
        $this->tax = round($this->subtotal * 0.06, 2);
        
        // Calculate total
        $this->total = $this->subtotal + $this->tax;
        
        // Add delivery fee if applicable
        if ($this->deliveryMethod === 'delivery') {
            $this->total += $this->deliveryFee;
        }
    }
    
    public function removeItem($index)
    {
        unset($this->cartItems[$index]);
        $this->cartItems = array_values($this->cartItems);
        session()->put('cartItems', $this->cartItems);
        
        // If cart is empty, redirect to home
        if (empty($this->cartItems)) {
            redirect()->route('customer.dashboard');
            return;
        }
        
        // Notify other components
        $this->emit('updateCartCount', count($this->cartItems));
        $this->calculateTotals();
    }
    
    public function updateQuantity($index, $change)
    {
        $newQuantity = $this->cartItems[$index]['quantity'] + $change;
        
        if ($newQuantity > 0) {
            $this->cartItems[$index]['quantity'] = $newQuantity;
        } else {
            $this->removeItem($index);
            return;
        }
        
        session()->put('cartItems', $this->cartItems);
        $this->calculateTotals();
    }
    
    public function updatedDeliveryMethod()
    {
        $this->calculateTotals();
    }
    
    public function proceedToCheckout()
    {
        $this->validate();
        
        // Create order record
        $order = Order::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $this->restaurant->id,
            'status' => 'pending',
            'delivery_method' => $this->deliveryMethod,
            'delivery_address' => $this->deliveryMethod === 'delivery' ? $this->deliveryAddress : null,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'delivery_fee' => $this->deliveryMethod === 'delivery' ? $this->deliveryFee : 0,
            'total' => $this->total,
            'payment_status' => 'pending',
            'notes' => $this->notes,
        ]);
        
        foreach ($this->cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }        
        
        $encryptedOrderId = Crypt::encryptString($order->id);

        // Store order ID in session for payment process
        session()->put('pending_order_id', $order->id);
        
        // Clear cart after checkout
        session()->forget('cartItems');
        
        // Redirect to checkout page
        return redirect()->route('cust.checkout', $encryptedOrderId);
    }
}
