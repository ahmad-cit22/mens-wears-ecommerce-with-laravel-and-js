<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model {
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

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function variations() {
        if (Auth::user()) {
            if (!Auth::user()->vendor) {
                return $this->hasMany(ProductStock::class);
            } else {
                return $this->hasMany(ProductStock::class)->where('vendor_id', Auth::user()->vendor->id);
            }
        } else {
            return $this->hasMany(ProductStock::class);
        }
    }

    public function total_stock() {
        $stock = 0;

        foreach ($this->variations as $item) {
            $stock += $item->qty;
        }

        return $stock;
    }

    public function variation() {
        return $this->hasOne(ProductStock::class);
        // if (!Auth::user()->vendor) {
        // } else {
        //     return $this->hasOne(ProductStock::class)->where('vendor_id', Auth::user()->vendor->id);
        // }
    }

    public function product_image() {
        return $this->hasMany(ProductImage::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    public function created_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'product_id', 'id')->where('work_name', 'create_product')->latest()->with('adder');
    }

    public function vendor_product() {
        return $this->hasOne(VendorProduct::class);
    }
}
