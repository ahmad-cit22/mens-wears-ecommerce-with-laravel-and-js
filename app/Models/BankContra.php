<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankContra extends Model
{
    use HasFactory;

    public function from()
    {
    	return $this->belongsTo(Bank::class, 'from_id');
    }

    public function to()
    {
    	return $this->belongsTo(Bank::class, 'to_id');
    }
}
