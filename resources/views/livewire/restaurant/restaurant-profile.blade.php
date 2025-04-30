<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $restaurantId ? 'Edit Restaurant' : 'Create Restaurant' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <form wire:submit.prevent="saveRestaurant" enctype="multipart/form-data">
        <div class="row">
            {{-- Restaurant Name --}}
            <div class="mb-3 col-md-6">
                <label class="form-label">Restaurant Name</label>
                <input type="text" class="form-control @error('restaurantProfile.name') is-invalid @enderror" 
                    wire:model.defer="restaurantProfile.name" placeholder="Enter restaurant name">
                @error('restaurantProfile.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Phone --}}
            <div class="mb-3 col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control @error('restaurantProfile.phone') is-invalid @enderror" 
                    wire:model.defer="restaurantProfile.phone" placeholder="Enter phone number">
                @error('restaurantProfile.phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            {{-- Address --}}
            <div class="mb-3 col-12">
                <label class="form-label">Address</label>
                <input type="text" class="form-control @error('restaurantProfile.address') is-invalid @enderror" 
                    wire:model.defer="restaurantProfile.address" placeholder="Enter address">
                @error('restaurantProfile.address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control @error('restaurantProfile.description') is-invalid @enderror" 
                wire:model.defer="restaurantProfile.description" rows="4" placeholder="Enter description (optional)"></textarea>
            @error('restaurantProfile.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Logo Upload --}}
        <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file" class="form-control @error('logo') is-invalid @enderror" wire:model="logo">
            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror

            {{-- Preview uploaded logo --}}
            @if ($logo)
                <div class="mt-2">
                    <p>Logo Preview:</p>
                    <img src="{{ $logo->temporaryUrl() }}" alt="Logo Preview" class="img-thumbnail" style="max-height: 150px;">
                </div>
            @elseif ($logoPreview)
                <div class="mt-2">
                    <p>Current Logo:</p>
                    <img src="{{ $logoPreview }}" alt="Current Logo" class="img-thumbnail" style="max-height: 150px;">
                </div>
            @endif
        </div>

        {{-- Active Status --}}
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" wire:model.defer="restaurantProfile.is_active" id="activeSwitch">
                <label class="form-check-label" for="activeSwitch">Active</label>
            </div>
        </div>

        {{-- Approval Status (Read-only) --}}
        <div class="mb-4">
            <label class="form-label">Approval Status</label>
            <div>
                @if(!empty($restaurantProfile['is_approved']) && $restaurantProfile['is_approved'])
                    <span class="badge bg-success">Approved</span>
                @else
                    <span class="badge bg-warning text-dark">Pending Approval</span>
                    <small class="text-muted d-block">Your restaurant is awaiting admin approval.</small>
                @endif
            </div>
        </div>

        {{-- Save Button --}}
        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                {{ $restaurantId ? 'Update Restaurant' : 'Create Restaurant' }}
            </button>
        </div>
    </form>
</div>