<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRestaurant extends Model
{
    use HasFactory;

    protected $table = 'category_restaurant';
    protected $primaryKey = 'id';
    public $guarded = ["id"];

    protected $connection = 'mysql';

    protected $fillable = [
        'restaurant_id',
        'category_id',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }   
}
