<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Asian',
            'Western',
            'Italian',
            'Chinese',
            'Japanese',
            'Indian',
            'Mexican',
            'Fast Food',
            'Vegetarian',
            'Vegan',
            'Desserts',
            'Beverages',
            'Seafood',
            'Breakfast',
            'Lunch Special',
            'Dinner',
            'Halal',
            'Kosher',
            'Gluten-Free',
            'Organic',
            'Street Food',
            'BBQ',
            'Pizza',
            'Burger',
            'Pasta',
            'Sushi',
            'Thai',
            'Korean',
            'Middle Eastern',
            'Mediterranean'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category
            ]);
        }
    }
}
