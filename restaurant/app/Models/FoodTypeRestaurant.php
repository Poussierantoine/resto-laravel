<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FoodTypeRestaurant extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'food_type_id',
        'restaurant_id'
    ];

    protected $table = 'food_type_restaurant';
}
