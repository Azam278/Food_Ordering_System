<div wire:ignore.self class="modal fade" id="modalApprovalRestaurant" tabindex="-1" role="dialog"
aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-info">
                <h4 class="modal-title" id="info-header-modalLabel">
                    {{ __("Restaurant Approval") }}
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($selectedRestaurant)
                <div class="row">
                    <div class="col-md-4">
                        @if($selectedRestaurant->logo)
                        <img src="{{ asset('storage/' . $selectedRestaurant->logo) }}" alt="Restaurant Logo" class="img-fluid rounded">
                        @else
                        <div class="bg-light p-5 text-center rounded">No Logo</div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3>{{ $selectedRestaurant->name }}</h3>
                        
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p>{{ $selectedRestaurant->description }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Address:</strong>
                            <p>{{ $selectedRestaurant->address }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Phone:</strong>
                            <p>{{ $selectedRestaurant->phone }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Registered on:</strong>
                            <p>{{ $selectedRestaurant->created_at->format('d M Y, h:i A') }}</p>
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
                @if($selectedRestaurant)
                    <button type="button" class="btn btn-success" wire:click="approveRestaurant">Approve</button>
                @endif
            </div>
        </div>
    </div>
</div>