<div class="container py-4">
    @if(count($cartItems) > 0)
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Your Cart</h4>
                    </div>
                    <div class="card-body">
                        @if($restaurant)
                            <div class="mb-3">
                                <h5>Order from: {{ $restaurant->name }}</h5>
                            </div>
                        @endif
                        
                        <!-- Cart Items -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Image</th>
                                        <th style="width: 30%">Item</th>
                                        <th style="width: 15%">Price</th>
                                        <th style="width: 20%">Quantity</th>
                                        <th style="width: 15%">Subtotal</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $index => $item)
                                        <tr>
                                            <td>
                                                <img src="{{ Storage::url($item['image']) }}" 
                                                    alt="{{ $item['name'] }}" 
                                                    class="img-thumbnail" 
                                                    style="height: 60px; width: 60px; object-fit: cover;"
                                                    onerror="this.src='{{ asset('images/no-image.png') }}'">
                                            </td>
                                            <td>
                                                <h6 class="mb-0">{{ $item['name'] }}</h6>
                                            </td>
                                            <td>RM {{ number_format($item['price'], 2) }}</td>
                                            <td>
                                                <div class="input-group">
                                                    <button class="btn btn-sm btn-outline-secondary" wire:click="updateQuantity({{ $index }}, -1)">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <span class="form-control text-center">{{ $item['quantity'] }}</span>
                                                    <button class="btn btn-sm btn-outline-secondary" wire:click="updateQuantity({{ $index }}, 1)">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>RM {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" wire:click="removeItem({{ $index }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary and Checkout -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Delivery Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="deliveryMethod" id="pickupOption" value="pickup" wire:model="deliveryMethod">
                                <label class="form-check-label" for="pickupOption">
                                    <strong>Pickup</strong> - Collect from restaurant
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="deliveryMethod" id="deliveryOption" value="delivery" wire:model="deliveryMethod">
                                <label class="form-check-label" for="deliveryOption">
                                    <strong>Delivery</strong> - RM {{ number_format($deliveryFee, 2) }} fee
                                </label>
                            </div>
                        </div>
                        
                        @if($deliveryMethod === 'delivery')
                            <div class="mb-3">
                                <label for="deliveryAddress" class="form-label">Delivery Address</label>
                                <textarea class="form-control @error('deliveryAddress') is-invalid @enderror" id="deliveryAddress" rows="3" wire:model="deliveryAddress"></textarea>
                                @error('deliveryAddress')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Special Instructions</label>
                            <textarea class="form-control" id="notes" rows="2" wire:model="notes" placeholder="Any special requests or instructions..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>RM {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (6%):</span>
                            <span>RM {{ number_format($tax, 2) }}</span>
                        </div>
                        @if($deliveryMethod === 'delivery')
                            <div class="d-flex justify-content-between mb-2">
                                <span>Delivery Fee:</span>
                                <span>RM {{ number_format($deliveryFee, 2) }}</span>
                            </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>RM {{ number_format($total, 2) }}</strong>
                        </div>
                        
                        <button type="button" class="btn btn-primary w-100" wire:click="proceedToCheckout">
                            Confirm Order & Proceed to Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size: 4rem;"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <p class="text-muted">Add some delicious items to your cart and they'll appear here!</p>
            <a href="{{ route('cust.dashboard') }}" class="btn btn-primary mt-3">Browse Restaurants</a>
        </div>
    @endif
</div>