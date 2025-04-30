<div class="container py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order Confirmation</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="mb-1">{{ $order->restaurant->name }}</h5>
                            <p class="text-muted mb-0">{{ $order->restaurant->address }}</p>
                        </div>
                        <div>
                            <span class="badge bg-success">{{ $order->status }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">Order ID:</small> {{ $order->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><small class="text-muted">Date:</small> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 50%">Item</th>
                                    <th style="width: 15%">Quantity</th>
                                    <th style="width: 15%">Price</th>
                                    <th style="width: 20%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $item->foodItem->name }}</h6>
                                            @if($item->special_instructions)
                                                <small class="text-muted">{{ $item->special_instructions }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>RM {{ number_format($item->price, 2) }}</td>
                                        <td>RM {{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row">
                        <!-- Order Details -->
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Order Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <p class="mb-1"><strong>Delivery Method:</strong> {{ ucfirst($order->delivery_method) }}</p>
                                        
                                        @if($order->delivery_method === 'delivery')
                                            <p class="mb-1"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                                        @endif
                                        
                                        @if($order->notes)
                                            <p class="mb-1"><strong>Notes:</strong> {{ $order->notes }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-0">
                                        <h6>Payment Information</h6>
                                        <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($order->payment->payment_method) }}</p>
                                        <p class="mb-1"><strong>Transaction ID:</strong> {{ $order->payment->transaction_id }}</p>
                                        <p class="mb-0"><strong>Status:</strong> <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($order->loyaltyTransaction)
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Loyalty Program</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0"><i class="bi bi-star-fill text-warning me-2"></i> You earned <strong>{{ $order->loyaltyTransaction->points }} points</strong> with this order!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>RM {{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax:</span>
                                        <span>RM {{ number_format($order->tax, 2) }}</span>
                                    </div>
                                    @if($order->delivery_method === 'delivery')
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Delivery Fee:</span>
                                            <span>RM {{ number_format($order->delivery_fee, 2) }}</span>
                                        </div>
                                    @endif
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <strong>Total:</strong>
                                        <strong>RM {{ number_format($order->total, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="{{ route('cust.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Go to the HomePage
        </a>
    </div>
</div>