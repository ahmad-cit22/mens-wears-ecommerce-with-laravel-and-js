<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_id',
        'sub_category_id',
        'brand_id',
        'price',
        'discount_price',
        'code',
        'image',
        'type',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category:: class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand:: class);
    }

    public function variations()
    {
        return $this->hasMany(ProductStock:: class);
    }

    public function variation()
    {
        return $this->hasOne(ProductStock:: class);
    }

    public function product_image()
    {
        return $this->hasMany(ProductImage:: class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating:: class);
    }
}
