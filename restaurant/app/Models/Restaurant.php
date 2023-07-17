<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'name',
        'desciption',
        'food_type',
        'price',
        'url',
        'tags',
        'status',
        'active'
    ];
    /**
     * Get the user that owns the restaurant.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function comments()
   {
       return $this->hasMany(Comment::class);
   }

   public function foodTypes()
   {
       return $this->belongsToMany(FoodType::class, 'food_type_restaurant')->using(FoodTypeRestaurant::class);
   }

   public function tags()
   {
       return $this->belongsToMany(Tag::class, 'tag_restaurant')->using(TagRestaurant::class);
   }
}
