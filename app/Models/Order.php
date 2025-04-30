<?php

namespace App\Models;

use App\Models\User;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'status',
        'delivery_method',
        'delivery_address',
        'subtotal',
        'tax',
        'delivery_fee',
        'total',
        'payment_id',
        'payment_status',
        'notes',
    ];
    
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    
    // Change 'items' to 'orderItems' to match the view
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    
    public function loyaltyTransaction()
    {
        return $this->hasOne(LoyaltyTransaction::class);
    }
}
