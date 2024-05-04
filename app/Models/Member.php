<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function card() {
        return $this->belongsTo(MembershipCard::class, 'membership_card_id');
    }
}
