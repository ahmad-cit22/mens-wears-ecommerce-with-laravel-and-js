<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProductStock extends Model
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

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }
}
