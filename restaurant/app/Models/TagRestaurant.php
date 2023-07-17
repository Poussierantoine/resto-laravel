<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TagRestaurant extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'tag_id',
        'restaurant_id'
    ];

    protected $table = 'tag_restaurant';

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
