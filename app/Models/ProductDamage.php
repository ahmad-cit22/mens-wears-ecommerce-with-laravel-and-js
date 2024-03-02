<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDamage extends Model
{
    use HasFactory;

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function size()
    {
    	return $this->belongsTo(Size::class);
    }

    public function created_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'damaged_product_id', 'id')->where('work_name', 'damage_product')->latest()->with('adder');
    }
}
