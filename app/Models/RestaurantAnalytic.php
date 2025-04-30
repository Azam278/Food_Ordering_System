<?php

namespace App\Models;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'date',
        'orders_count',
        'total_sales',
        'rejected_orders',
    ];
    
    protected $casts = [
        'date' => 'date',
        'total_sales' => 'decimal:2',
    ];
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
