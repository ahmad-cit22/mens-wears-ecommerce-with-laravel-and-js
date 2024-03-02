<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkashNumber extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function current_balance()
    {
        $balance = $this->opening_balance;
        $incoming = BkashRecord::where('bkash_business_id', $this->id)->where('tr_type', 'CASH IN')->sum('amount');
        $outgoing = BkashRecord::where('bkash_business_id', $this->id)->where('tr_type', '!=', 'CASH IN')->sum('amount');
        
        $balance = $balance + $incoming - $outgoing;
        // foreach ($records as $item) {
        //     if ($item->tr_type == 'CASH IN') {
        //         $balance += $item->amount;
        //     } else {
        //         $balance -= $item->amount;
        //     }
        // }

        return $balance;
    }
}
