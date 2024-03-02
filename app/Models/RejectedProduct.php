<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
    
    public function stock()
    {
    	return ProductStock::where('product_id', $this->product_id)->where('size_id', $this->size_id)->first();
    }

    public function size()
    {
    	return $this->belongsTo(Size::class);
    }

    public function created_by() {
        // return $this->hasOne(WorkTrackingEntry::class, 'rejected_product_id', 'id')->where('work_name', 'reject_product')->latest()->with('adder');
        return $this->hasOne(WorkTrackingEntry::class, 'rejected_product_id', 'id')->where(function ($query) {
            $query->where('work_name', 'reject_product')
                ->orWhere('work_name', 'reject_product_out');
        })->latest()->with('adder');
    }
}
