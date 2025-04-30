<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
        'payment_details',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
