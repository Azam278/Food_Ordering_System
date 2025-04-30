<div>
    <div class="container-fluid mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark fs-4">Discover Restaurants</h5>
            
            <!-- Search and Filter Area -->
            <div class="d-flex gap-3">                
                <!-- Category Filter -->
                <div style="width: 200px;">
                    <select class="form-select" wire:model="selectedCategory">
                        <option value="">--Please Choose Category--</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-4">
    <!-- Restaurant Cards Section -->
    <div class="container-fluid">
        @if($restaurants->isEmpty())
            <div class="alert alert-info">
                No restaurants found. Try adjusting your filters.
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($restaurants as $restaurant)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $restaurant->logo) }}"  
                                    class="card-img-top" alt="{{ $restaurant->name }}" 
                                    style="height: 180px; object-fit: cover;">
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $restaurant->name }}</h5>
                                
                                <div class="mb-2">
                                    {{-- {{dd($restaurant->categories)}} --}}
                                    @foreach($restaurant->categories as $category)
                                        <span class="badge bg-info text-dark me-1">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                                
                                <p class="card-text small text-muted">{{ Str::limit($restaurant->description, 80) }}</p>
                            </div>
                            
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('cust.view-menu', Crypt::encryptString($restaurant->id)) }}" class="btn btn-primary w-100">
                                    View Menu
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{-- {{ $restaurants->links() }} --}}
            </div>
        @endif
    </div>
</div>
