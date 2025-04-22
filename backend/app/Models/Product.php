<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'description',
        'price',
        'quantity',
        'image',
    ];

    protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getImageUrlAttribute()
    {
        // Ensure the full URL is generated
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}