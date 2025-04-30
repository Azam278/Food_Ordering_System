<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\RestaurantAnalytic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\View;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'phone',
        'logo',
        'is_approved',
        'is_active',
    ];
    
    protected $casts = [
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function foodItems()
    {
        return $this->hasMany(FoodItem::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function analytics()
    {
        return $this->hasMany(RestaurantAnalytic::class);
    }
    
    // Scope for approved restaurants
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
    
    // Scope for active restaurants
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
