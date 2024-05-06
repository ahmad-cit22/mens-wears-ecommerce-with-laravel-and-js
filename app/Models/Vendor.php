<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions() {
        return $this->hasMany(VendorTransaction::class);
    }

    public function vendor_products() {
        return $this->hasMany(VendorProduct::class)->where('is_active', 1)->where('is_approved', 1);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function orders_report() {
        return $this->hasMany(Order::class)
            ->where('is_final', 1)
            ->where('order_status_id', '!=', 5)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year);
    }

    public function vat_entries() {
        return $this->hasMany(VatEntry::class);
    }

    public function vat_entries_reports() {
        return $this->hasMany(VatEntry::class)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('id', 'DESC');
    }

    public function expense_types() {
        return $this->hasMany(Expense::class);
    }

    public function expense_entries() {
        return $this->hasMany(ExpenseEntry::class);
    }

    public function expense_entries_reports() {
        return $this->hasMany(ExpenseEntry::class)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->orderBy('id', 'DESC');
    }

    public function bank_transactions() {
        return $this->hasMany(BankTransaction::class);
    }

    public function other_incomes_report() {
        return $this->hasMany(BankTransaction::class)
            ->where('other_income', 1)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year);
    }
}
