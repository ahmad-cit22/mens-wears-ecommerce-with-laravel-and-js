<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }

    public function customer()
    {
    	return $this->belongsTo(User::class, 'customer_id');
    }
}
