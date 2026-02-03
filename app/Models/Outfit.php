<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outfit extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'weather_temp',
        'weather_condition',
    ];

    public function items()
    {
        return $this->belongsToMany(
            ClothingItem::class,
            'outfit_items',
            'outfit_id',
            'clothing_item_id'
        );
    }
}