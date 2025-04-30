<?php

namespace App\Http\Livewire\Restaurant;

use Carbon\Carbon;
use App\Models\Order;
use Livewire\Component;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class TotalSales extends Component
{
    public $restaurants;
    public $selectedRestaurantId;
    public $totalSales;
    public $todaySales;
    public $dateRange = 'week';
    public $startDate;
    public $endDate;
    public $customStartDate;
    public $customEndDate;

    public function mount()
    {
        // Get restaurants managed by the current user
        $this->restaurants = Restaurant::where('user_id', Auth::id())->get();
        
        // Set the first restaurant as default if any exists
        if ($this->restaurants->count() > 0) {
            $this->selectedRestaurantId = $this->restaurants->first()->id;
        }
        
        // Set default date range (current week)
        $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        $this->customStartDate = $this->startDate;
        $this->customEndDate = $this->endDate;
        
        // Calculate initial sales data
        $this->calculateSales();
    }
    
    public function calculateSales()
    {
        if (!$this->selectedRestaurantId) {
            return;
        }
        
        // Calculate total sales (all time)
        $this->totalSales = Order::where('restaurant_id', $this->selectedRestaurantId)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->sum('total');
            
        // Calculate today's sales
        $this->todaySales = Order::where('restaurant_id', $this->selectedRestaurantId)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total');
    }
    
    public function updatedSelectedRestaurantId()
    {
        $this->calculateSales();
    }
    
    public function updatedDateRange()
    {
        switch ($this->dateRange) {
            case 'today':
                $this->startDate = Carbon::today()->format('Y-m-d');
                $this->endDate = Carbon::today()->format('Y-m-d');
                break;
            case 'week':
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                $this->startDate = $this->customStartDate;
                $this->endDate = $this->customEndDate;
                break;
        }
    }
    
    public function updatedCustomStartDate()
    {
        $this->dateRange = 'custom';
        $this->startDate = $this->customStartDate;
    }
    
    public function updatedCustomEndDate()
    {
        $this->dateRange = 'custom';
        $this->endDate = $this->customEndDate;
    }
    
    public function getColumnChartModel()
    {
        $orders = Order::where('restaurant_id', $this->selectedRestaurantId)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$this->startDate.' 00:00:00', $this->endDate.' 23:59:59'])
            ->get();
            
        $columnChartModel = (new ColumnChartModel())
            ->setTitle('Daily Sales')
            ->setAnimated(true)
            ->withoutLegend()
            ->setColors(['#3490dc']);
            
        $salesByDate = $orders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('Y-m-d');
        })->map(function ($dateOrders) {
            return $dateOrders->sum('total');
        });
        
        // Create a date range to ensure all dates in range are included
        $period = Carbon::parse($this->startDate)->daysUntil(Carbon::parse($this->endDate)->addDay());
        
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $columnChartModel->addColumn(
                $date->format('M d'), 
                $salesByDate[$dateStr] ?? 0, 
                '#3490dc'
            );
        }
        
        return $columnChartModel;
    }
    
    public function getPieChartModel()
    {
        $orders = Order::where('restaurant_id', $this->selectedRestaurantId)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$this->startDate.' 00:00:00', $this->endDate.' 23:59:59'])
            ->get();
            
        $pieChartModel = (new PieChartModel())
            ->setTitle('Sales by Payment Method')
            ->setAnimated(true)
            ->setColors(['#f6ad55', '#fc8181', '#90cdf4', '#66DA26']);
            
        $salesByPaymentMethod = $orders->groupBy(function ($order) {
            return $order->payment ? $order->payment->payment_method : 'unknown';
        })->map(function ($methodOrders) {
            return $methodOrders->sum('total');
        });
        
        foreach ($salesByPaymentMethod as $method => $total) {
            $pieChartModel->addSlice($method, $total, $this->getRandomColor($method));
        }
        
        return $pieChartModel;
    }
    
    public function getLineChartModel()
    {
        $orders = Order::where('restaurant_id', $this->selectedRestaurantId)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$this->startDate.' 00:00:00', $this->endDate.' 23:59:59'])
            ->get();
            
        $lineChartModel = (new LineChartModel())
            ->setTitle('Sales Trend')
            ->setAnimated(true)
            ->setColors(['#3490dc'])
            ->withoutLegend();
            
        $salesByDate = $orders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('Y-m-d');
        })->map(function ($dateOrders) {
            return $dateOrders->sum('total');
        });
        
        // Create a date range to ensure all dates in range are included
        $period = Carbon::parse($this->startDate)->daysUntil(Carbon::parse($this->endDate)->addDay());
        
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $lineChartModel->addPoint(
                $date->format('M d'), 
                $salesByDate[$dateStr] ?? 0
            );
        }
        
        return $lineChartModel;
    }
    
    private function getRandomColor($seed = null)
    {
        $colors = ['#f6ad55', '#fc8181', '#90cdf4', '#66DA26', '#FFA07A', '#20B2AA', '#DDA0DD', '#FF6347'];
        
        if ($seed) {
            $index = crc32($seed) % count($colors);
            return $colors[$index];
        }
        
        return $colors[array_rand($colors)];
    }
    
    public function render()
    {
        return view('livewire.restaurant.total-sales', [
            'columnChartModel' => $this->getColumnChartModel(),
            'pieChartModel' => $this->getPieChartModel(),
            'lineChartModel' => $this->getLineChartModel(),
        ])->layout('layouts.restaurant');
    }
}
