<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    public function area() {
        return $this->belongsTo(Area::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function status() {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function order_product() {
        return $this->hasMany(OrderProduct::class);
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vat_entry() {
        return $this->hasOne(VatEntry::class, 'order_id', 'id');
    }

    public function sold_amount() {
        $amount = $this->order_product->sum(function ($t) {
            $qty = $t->qty - $t->return_qty;
            return $t->price * $qty;
        });
        return $amount ? $amount : 0;
    }

    public function created_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'order_id', 'id')->where('work_name', 'create_order')->latest()->with('adder');
    }

    public function printed_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'order_id', 'id')->where('work_name', 'print_memo')->latest()->with('adder');
    }

    public function packaged_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'order_id', 'id')->where('work_name', 'packaging')->latest()->with('adder');
    }

    public function order_paid_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'order_id', 'id')->where('work_name', 'order_paid')->latest()->with('adder');
    }

    public function order_returned_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'order_id', 'id')->where('work_name', 'order_return')->latest()->with('adder');
    }

    public function add_loss_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'order_id', 'id')->where('work_name', 'add_loss')->latest()->with('adder');
    }

    public function apply_cod_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'order_id', 'id')->where('work_name', 'apply_cod')->latest()->with('adder');
    }
}
