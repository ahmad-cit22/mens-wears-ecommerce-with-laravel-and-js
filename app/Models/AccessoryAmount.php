<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryAmount extends Model
{
    use HasFactory;

    public function accessory()
    {
    	return $this->belongsTo(Accessory::class);
    }

    public function bank()
    {
    	return $this->belongsTo(Bank::class);
    }
}
