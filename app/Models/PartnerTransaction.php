<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerTransaction extends Model
{
    use HasFactory;

    public function partner()
    {
    	return $this->belongsTo(Partner::class);
    }

    public function bank()
    {
    	return $this->belongsTo(Bank::class);
    }
}
