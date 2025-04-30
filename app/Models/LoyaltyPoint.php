<?php

namespace App\Models;

use App\Models\User;
use App\Models\LoyaltyTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'lifetime_points',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(LoyaltyTransaction::class, 'user_id', 'user_id');
    }
}
