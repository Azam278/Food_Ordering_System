<div class="container py-4">
    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Payment & Confirmation</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Order #{{ $order->id }}</h5>
                        <div class="text-muted">{{ $order->created_at->format('F j, Y - g:i A') }}</div>
                    </div>

                    <div class="mb-4">
                        <h6>Order from: {{ $order->restaurant->name }}</h6>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-geo-alt me-2"></i>
                            {{ $order->restaurant->address }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>{{ $order->delivery_method === 'delivery' ? 'Delivery' : 'Pickup' }} Details:</h6>
                        @if($order->delivery_method === 'delivery')
                            <div class="d-flex align-items-start mt-2">
                                <i class="bi bi-house me-2 mt-1"></i>
                                <div>
                                    <strong>Delivery Address:</strong><br>
                                    {{ $order->delivery_address }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-clock me-2"></i>
                                <div>
                                    <strong>Estimated Delivery:</strong> 30-45 minutes
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-shop me-2"></i>
                                <div>
                                    <strong>Pickup Location:</strong> {{ $order->restaurant->name }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <i class="bi bi-clock me-2"></i>
                                <div>
                                    <strong>Ready for Pickup:</strong> Approximately 20-30 minutes
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($order->notes)
                        <div class="mb-4">
                            <h6>Special Instructions:</h6>
                            <div class="p-2 bg-light rounded">{{ $order->notes }}</div>
                        </div>
                    @endif

                    <h6>Order Items:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->foodItem->name }}</td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end">RM {{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">RM {{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>RM {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (6%):</span>
                        <span>RM {{ number_format($order->tax, 2) }}</span>
                    </div>
                    @if($order->delivery_fee > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee:</span>
                            <span>RM {{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total Amount:</strong>
                        <strong>RM {{ number_format($order->total, 2) }}</strong>
                    </div>

                    <form wire:submit.prevent="processPayment">
                        <div class="mb-4">
                            <h6>Payment Method:</h6>
                            <div class="payment-method-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paypal" value="paypal" wire:model="paymentMethod" checked disabled>
                                    <label class="form-check-label d-flex align-items-center" for="paypal">
                                        <i class="bi bi-paypal me-2 text-primary"></i> PayPal
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="processPayment">
                                    Pay with PayPal - RM {{ number_format($order->total, 2) }}
                                </span>
                                <span wire:loading wire:target="processPayment">
                                    <i class="bi bi-arrow-repeat spinner-border spinner-border-sm"></i> Processing...
                                </span>
                            </button>
                        </div>
                    </form>

                    @if($paymentMethod !== 'cash')
                        <div class="text-center mt-3">
                            <img src="{{ asset('images/payment-secure.png') }}" alt="Secure Payment" style="height: 24px;">
                            <p class="text-muted small mt-2 mb-0">Your payment information is secure and encrypted</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>    
</div>