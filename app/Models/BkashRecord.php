<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkashRecord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function bkash_business() {
        return $this->belongsTo(BkashNumber::class, 'bkash_business_id');
    }

    public function bkash_purpose() {
        return $this->belongsTo(BkashRecordPurpose::class, 'tr_purpose_id');
    }
     
    public function created_by() {
        return $this->hasOne(WorkTrackingEntry::class, 'bkash_record_id', 'id')->where('work_name', 'bkash_record')->with('adder');
    }
}
