<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(VendorTransaction::class);
    }

    public function vendor_products()
    {
        return $this->hasMany(VendorProduct::class)->where('is_active', 1)->where('is_approved', 1);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orders_report($fromDate = null, $toDate = null)
    {
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::now()->startOfMonth();
        $toDate = $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::now()->endOfMonth();

        return $this->hasMany(Order::class)
            ->where('is_final', 1)
            ->where('order_status_id', '!=', 5)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->orderBy('id', 'DESC');
    }

    public function vat_entries()
    {
        return $this->hasMany(VatEntry::class);
    }

    public function vat_entries_reports($fromDate = null, $toDate = null)
    {
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::now()->startOfMonth();
        $toDate = $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::now()->endOfMonth();

        return $this->hasMany(VatEntry::class)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->orderBy('id', 'DESC');
    }

    public function expense_types()
    {
        return $this->hasMany(Expense::class);
    }

    public function expense_entries()
    {
        return $this->hasMany(ExpenseEntry::class);
    }

    public function expense_entries_reports($fromDate = null, $toDate = null)
    {
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::now()->startOfMonth();
        $toDate = $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::now()->endOfMonth();

        return $this->hasMany(ExpenseEntry::class)
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->orderBy('id', 'DESC');
    }

    public function bank_transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }

    public function other_incomes_report($fromDate = null, $toDate = null)
    {
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::now()->startOfMonth();
        $toDate = $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::now()->endOfMonth();

        return $this->hasMany(BankTransaction::class)
            ->where('other_income', 1)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate);
    }
}
