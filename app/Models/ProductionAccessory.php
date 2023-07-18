<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionAccessory extends Model
{
    use HasFactory;

    public function production()
    {
    	return $this->belongsTo(Production::class);
    }

    public function accessory()
    {
    	return $this->belongsTo(Accessory::class);
    }
}
