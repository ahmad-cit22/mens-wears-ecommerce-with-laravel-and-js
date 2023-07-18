<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    use HasFactory;

    public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }

    public function bank()
    {
    	return $this->belongsTo(Bank::class);
    }
}
