<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookOrder extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function status() {
        return $this->belongsTo(FacebookOrderStatus::class, 'order_status_id');
    }

    public function special_status() {
        return $this->belongsTo(OrderSpecialStatus::class, 'special_status_id');
    }

    public function courier() {
        return $this->belongsTo(CourierName::class, 'courier_id');
    }

    public function bkash_business() {
        return $this->belongsTo(BkashNumber::class, 'bkash_business_id');
    }

    public function order_product() {
        return $this->hasMany(FacebookOrderProduct::class, 'order_id');
    }

    public function bkash_record() {
        return $this->hasOne(BkashRecord::class, 'order_sheet_id');
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
    public function created_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'order_sheet_id', 'id')->where('work_name', 'create_order_sheet')->with('adder');
    }
}
