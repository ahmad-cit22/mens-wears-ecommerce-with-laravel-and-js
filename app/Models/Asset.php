<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    public function bank()
    {
    	return $this->belongsTo(Bank::class);
    }

    public function deductions()
    {
    	return $this->hasMany(AssetDeduction::class);
    }
}
