<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function status()
    {
    	return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function order_product()
    {
    	return $this->hasMany(OrderProduct::class);
    }

    public function customer()
    {
    	return $this->belongsTo(User::class, 'customer_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
