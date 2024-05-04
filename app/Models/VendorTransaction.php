<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }

    public function bank() {
        return $this->belongsTo(Bank::class);
    }
}
