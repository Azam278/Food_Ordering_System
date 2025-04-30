<div>
    <div class="container py-4">
        <!-- Restaurant Info -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            @if($restaurant->logo)
                                <img src="{{ Storage::url($restaurant->logo) }}" 
                                    alt="{{ $restaurant->name }}" 
                                    class="img-thumbnail me-3" 
                                    style="height: 60px; width: 60px; object-fit: cover;"
                                    onerror="this.src='{{ asset('images/no-image.png') }}'">
                            @endif
                            <div>
                                <h2 class="mb-1">{{ $restaurant->name }}</h2>
                                <p class="text-muted mb-0">{{ $restaurant->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Food Items -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($foodItems as $foodItem)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="text-center p-3">
                            <img src="{{ Storage::url($foodItem->image) }}" 
                                alt="{{ $foodItem->name }}" 
                                class="img-fluid rounded" 
                                style="height: 200px; object-fit: cover;"
                                onerror="this.src='{{ asset('images/no-image.png') }}'">
                        </div>                    
                        <div class="card-body text-center">                        
                            <h5 class="card-title fw-bold">{{ $foodItem->name }}</h5>                     
                            <p class="card-text">{{ $foodItem->description }}</p>
                            <p class="card-text fw-bold">RM {{ number_format($foodItem->price, 2) }}</p>
                        </div>                    
                        <div class="card-footer bg-white border-top-0 text-center">
                            <button wire:click="addToCart({{ $foodItem->id }})" class="btn btn-primary w-75">
                                Order
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Toast Notification -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            @if (session()->has('success'))
                <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        const toastElement = document.getElementById('liveToast');
                        const toast = new bootstrap.Toast(toastElement);
                        toast.hide();
                    }, 3000);
                </script>
            @endif
        </div>
    </div>
</div>