<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSupplier extends Model
{
    use HasFactory;

    public function production()
    {
    	return $this->belongsTo(Production::class);
    }

    public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }
}
