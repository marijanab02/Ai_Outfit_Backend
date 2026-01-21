<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClothingItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'category',
        'color',
        'image_url',
        'season'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
