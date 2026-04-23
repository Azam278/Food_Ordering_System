<div wire:ignore.self class="modal fade" id="modalAddFoodCategory" tabindex="-1" role="dialog"
aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title w-100 text-center" id="info-header-modalLabel">
                    {{ __("Add Food Category") }}
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($selectedRestaurant)
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center mb-4">{{ $selectedRestaurant->name }}</h3>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Food Category</label>
                                <input type="text" id="name" wire:model="categoryFood.name" class="form-control" placeholder="Food Category Name">
                                @error('categoryFood.name') <small class="text-danger">{{ $message }}</small> @enderror
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
                <button type="button" class="btn btn-primary" wire:click="addCategoryItem">Add Category</button>
            </div>
        </div>
    </div>
</div>