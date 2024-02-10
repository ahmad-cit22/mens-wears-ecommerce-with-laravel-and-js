<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTrackingEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function adder() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order_sheet() {
        return $this->belongsTo(FacebookOrder::class, 'order_sheet_id');
    }
}
