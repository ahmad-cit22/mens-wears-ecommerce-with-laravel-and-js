<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderProduct extends Model {
    use HasFactory;

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function stock() {
        if (Auth::user()) {
            if (!Auth::user()->vendor) {
                return ProductStock::where('product_id', $this->product_id)->where('size_id', $this->size_id)->where('vendor_id', null)->first();
            } else {
                return ProductStock::where('product_id', $this->product_id)->where('size_id', $this->size_id)->where('vendor_id', Auth::user()->vendor->id)->first();
            }
        } else {
            return ProductStock::where('product_id', $this->product_id)->where('size_id', $this->size_id)->first();
        }
    }

    public function size() {
        return $this->belongsTo(Size::class);
    }
}
