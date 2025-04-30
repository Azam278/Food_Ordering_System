<div>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Restaurant Sales Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Total Sales</li>
        </ol>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="restaurantSelect">Select Restaurant</label>
                    <select wire:model="selectedRestaurantId" id="restaurantSelect" class="form-control">
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="dateRange">Date Range</label>
                    <select wire:model="dateRange" id="dateRange" class="form-control">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
            </div>
        </div>
        
        @if($dateRange === 'custom')
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="customStartDate">Start Date</label>
                    <input type="date" wire:model="customStartDate" id="customStartDate" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="customEndDate">End Date</label>
                    <input type="date" wire:model="customEndDate" id="customEndDate" class="form-control">
                </div>
            </div>
        </div>
        @endif
        
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-money-bill-wave me-1"></i>
                        Sales Summary
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Sales (All Time)</h5>
                                        <h2>RM{{ number_format($totalSales, 2) }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Today's Sales</h5>
                                        <h2>RM{{ number_format($todaySales, 2) }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-column me-1"></i>
                        Daily Sales ({{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }})
                    </div>
                    <div class="card-body">
                        <div wire:ignore>
                            <livewire:livewire-column-chart
                                key="{{ $columnChartModel->reactiveKey() }}"
                                :column-chart-model="$columnChartModel"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>