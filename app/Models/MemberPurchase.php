<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberPurchase extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function member() {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
