<div class="container-fluid">
    
    <h1 class="mb-4">Restaurant Manager Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Approved Restaurants</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $approvedRestaurants }}</h5>
                    <p class="card-text">Total restaurants approved by admin.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Pending Approvals</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $pendingRestaurants ?? 0 }}</h5>
                    <p class="card-text">Restaurants waiting for admin approval.</p>
                </div>
            </div>
        </div>
    </div>
</div>