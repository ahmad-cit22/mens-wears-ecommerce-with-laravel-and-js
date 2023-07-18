<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function suppliers()
    {
    	return $this->hasMany(ProductionSupplier::class);
    }

    public function costs()
    {
    	return $this->hasMany(ProductionCost::class);
    }

    public function accessories()
    {
    	return $this->hasMany(ProductionAccessory::class);
    }
}
