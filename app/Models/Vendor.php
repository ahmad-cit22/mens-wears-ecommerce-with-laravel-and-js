<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions() {
        return $this->hasMany(VendorTransaction::class);
    }

    public function vendor_products() {
        return $this->hasMany(VendorProduct::class);
    }
}
