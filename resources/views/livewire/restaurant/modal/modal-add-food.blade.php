<div wire:ignore.self class="modal fade" id="modalAddFoodItem" tabindex="-1" role="dialog"
aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title w-100 text-center" id="info-header-modalLabel">
                    {{ __("Add Food Item") }}
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($selectedRestaurant)
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center mb-4">{{ $selectedRestaurant->name }}</h3>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Food Name</label>
                                <input type="text" id="name" wire:model="foodMenu.name" class="form-control" placeholder="Food Name">
                                @error('foodMenu.name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select id="category" wire:model="category" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach($foodCategory as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" wire:model="foodMenu.description" class="form-control" rows="3" 
                                    placeholder="Enter food description"></textarea>
                                @error('foodMenu.description') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="foodImage" class="form-label">Food Image</label>
                                <input type="file" class="form-control @error('foodMenu.foodImage') is-invalid @enderror" 
                                       wire:model="foodMenu.foodImage" id="foodImage" accept="image/*">
                                @error('foodMenu.foodImage') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            
                                {{-- Image Preview --}}
                                @if (isset($foodMenu['foodImage']))
                                    <div class="mt-2">
                                        <img src="{{ $foodMenu['foodImage']->temporaryUrl() }}"
                                             class="img-thumbnail" 
                                             style="max-height: 200px" 
                                             alt="Food Image Preview">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price (RM)</label>
                                <input type="number" step="0.01" id="price" wire:model="foodMenu.price" class="form-control" 
                                    placeholder="0.00">
                                @error('foodMenu.price') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        No restaurant selected or data not available.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click="addMenuItem">Add Item</button>
            </div>
        </div>
    </div>
</div>