<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public function payments()
    {
    	return $this->hasMany(SupplierPayment::class);
    }

    public function productions()
    {
    	return $this->hasMany(ProductionSupplier::class);
    }
}
