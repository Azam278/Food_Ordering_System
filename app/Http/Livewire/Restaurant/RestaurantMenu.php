<?php

namespace App\Http\Livewire\Restaurant;

use App\Models\Category;
use App\Models\CategoryRestaurant;
use App\Models\FoodItem;
use App\Models\Restaurant;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class RestaurantMenu extends Component
{
    use WithFileUploads;

    public $restaurant;
    public $restaurantId;
    public $foodItems = [];

    public $foodMenu = [
        'name' => '',
        'description' => '',
        'price' => '',
        'foodImage' => null,
        'is_available' => false,
    ];

    public $categoryFood = [
        'name' => '',
    ];

    public $category;
    public $foodCategory;
    public $selectedRestaurant;

    public function mount($restaurantId)
    {
        $this->restaurantId = $restaurantId;

        try {
            $decryptedId = Crypt::decryptString($restaurantId);
            $this->restaurant = Restaurant::with('foodItems','categories')->findOrFail($decryptedId);
            $this->foodItems = $this->restaurant->foodItems;
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Restaurant not found');
        }
    }

    public function modalAddFoodItem()
    {
        $this->selectedRestaurant = $this->restaurant;
        $this->foodCategory = Category::all(['id', 'name']);
        $this->emit('showModal', 'modalAddFoodItem');
    }

    public function modalAddFoodCategory()
    {
        $this->selectedRestaurant = $this->restaurant;
        $this->emit('showModal', 'modalAddFoodCategory');
    }

    public function addMenuItem()
    {
        $this->validate([
            'foodMenu.name' => 'required|string|max:255',
            'category' => 'required|numeric',
            'foodMenu.description' => 'nullable|string',
            'foodMenu.price' => 'required|numeric|min:0',
            'foodMenu.foodImage' => 'required|image|max:1024',
        ]);

        try {
            DB::beginTransaction();
            $decryptedId = Crypt::decryptString($this->restaurantId);

            $imagePath = null;
            if ($this->foodMenu['foodImage']) {
                $imageName = time().'_'.$this->foodMenu['foodImage']->getClientOriginalName();
                $imagePath = $this->foodMenu['foodImage']->storeAs('img_food', $imageName, 'public');
            }

            // Ensure the category is linked
            CategoryRestaurant::firstOrCreate([
                'restaurant_id' => $decryptedId,
                'category_id' => $this->category,
            ]);

            // Create food item
            FoodItem::create([
                'restaurant_id' => $decryptedId,
                'name' => $this->foodMenu['name'],
                'description' => $this->foodMenu['description'],
                'price' => $this->foodMenu['price'],
                'image' => $imagePath,
                'is_available' => true,
            ]);

            DB::commit();

            // Refresh food items
            $this->restaurant = Restaurant::with('foodItems','categories')->findOrFail($decryptedId);
            $this->foodItems = $this->restaurant->foodItems;

            $this->reset('foodMenu', 'category');
            $this->emit('hideModal', 'modalAddFoodItem');
            $this->emit('swal:alert', [
                'position' => 'top',
                'icon' => 'success',
                'title' => 'Successfully added: ' . $this->foodMenu['name'],
                'timerProgressBar' => true,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->emit('swal:alert', [
                'position' => 'top',
                'icon' => 'error',
                'title' => 'Error: ' . $e->getMessage(),
                'timerProgressBar' => true,
            ]);
        }
    }

    public function addCategoryItem(){
        $this->validate([
            'categoryFood.name' => 'required|string|max:255',
        ]);

        try{
            DB::beginTransaction();

            Category::firstOrCreate(['name' => $this->categoryFood['name']]);

            DB::commit();

            $this->emit('swal:alert', [
                'position' => 'top',
                'icon' => 'success',
                'title' => 'Successfully added: ' . $this->foodMenu['name'],
                'timerProgressBar' => true,
            ]);
        } catch(QueryException $e){
            DB::rollBack();

            $this->emit('swal:alert', [
                'position' => 'top',
                'icon' => 'error',
                'title' => 'Error: ' . $e->getMessage(),
                'timerProgressBar' => true,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.restaurant.restaurant-menu')->layout('layouts.restaurant');
    }
}