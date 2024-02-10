<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockHistory extends Model
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
        return $this->hasOne(WorkTrackingEntry::class, 'product_stock_history_id', 'id')->where('work_name', 'add_stock')->latest()->with('adder');
    }
}
