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
}
