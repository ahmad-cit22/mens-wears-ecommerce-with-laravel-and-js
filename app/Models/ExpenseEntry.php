<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseEntry extends Model
{
    use HasFactory;

    public function expense()
    {
    	return $this->belongsTo(Expense::class);
    }

    public function bank()
    {
    	return $this->belongsTo(Bank::class);
    }

    public function created_by() {
        // return $this->hasOne(WorkTrackingEntry::class, 'expense_entry_id', 'id')->where('work_name', 'expense_entry')->orWhere('work_name', 'add_loss')->latest()->with('adder');
        return $this->hasOne(WorkTrackingEntry::class, 'expense_entry_id', 'id')->where(function ($query) {
            $query->where('work_name', 'expense_entry')
                ->orWhere('work_name', 'add_loss');
        })->latest()->with('adder');
    }
}
