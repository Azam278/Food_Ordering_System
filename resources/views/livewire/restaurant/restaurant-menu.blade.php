<div class="container">
    @include('livewire.restaurant.modal.modal-add-food')

    <h2 class="mb-4">{{ $restaurant->name }} Menu</h2>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __("Manager - Menu") }}</h3>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <button class="btn btn-primary" wire:click.prevent="modalAddFoodItem">Add Food Item</button>
            </div>

            @if($foodItems->isEmpty())
                <div class="alert alert-warning">
                    No menu items found. Please add your first food item.
                </div>                
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __("Bil") }}</th>
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Description") }}</th>
                            <th>{{ __("Category") }}</th>
                            <th>{{ __("Image") }}</th>
                            <th>{{ __("Price") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($foodItems as $foodItem)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $foodItem->name }}</td>
                                <td>{{ $foodItem->description }}</td>
                                <td>
                                    @foreach($restaurant->categories as $category)
                                        <span class="badge bg-primary">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <img src="{{ Storage::url($foodItem->image) }}" 
                                         alt="{{ $foodItem->name }}" 
                                         class="img-thumbnail" 
                                         style="max-height: 100px; width: auto;"
                                         onerror="this.src='{{ asset('images/no-image.png') }}'">
                                </td>
                                <td>RM {{ number_format($foodItem->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>                    
                </table>
            @endif
        </div>
    </div>
</div>