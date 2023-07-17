<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active'
    ];



    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'tag_restaurant')->using(TagRestaurant::class);
    }
}
