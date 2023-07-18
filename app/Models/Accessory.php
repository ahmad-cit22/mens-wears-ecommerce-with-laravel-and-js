<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    public function stock()
    {
    	return $this->hasMany(AccessoryAmount::class);
    }

    public function production_accessory()
    {
    	return $this->hasMany(ProductionAccessory::class);
    }
}
