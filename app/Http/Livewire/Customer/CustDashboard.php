<?php

namespace App\Http\Livewire\Customer;

use App\Models\Category;
use Livewire\Component;
use App\Models\Restaurant;

class CustDashboard extends Component
{
    public $restaurants;
    public $categories;
    public $selectedCategory = '';

    public function mount()
    {
        $this->categories = Category::all(['id', 'name']);
    }

    public function render()
    {
        $query = Restaurant::with('foodItems', 'categories')
            ->where('is_approved', true)
            ->where('is_active', true);

        if (!empty($this->selectedCategory)) {
            $query->whereHas('categories', function ($q) {
                $q->where('categories.id', $this->selectedCategory);
            });
        }

        $this->restaurants = $query->get();

        return view('livewire.customer.cust-dashboard')
            ->layout('layouts.customer');
    }
}
