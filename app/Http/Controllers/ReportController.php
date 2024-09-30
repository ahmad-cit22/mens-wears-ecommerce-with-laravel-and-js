<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Accessory;
use App\Models\AccessoryAmount;
use App\Models\Asset;
use App\Models\AssetDeduction;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Expense;
use App\Models\ExpenseEntry;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\Partner;
use App\Models\PartnerTransaction;
use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\VatEntry;
use App\Models\Vendor;
use Carbon\Carbon;

class ReportController extends Controller {
    public function income_statement() {
        $date_from = '';
        $date_to = '';

        if (auth()->user()->can('report.income')) {
            if (!Auth::user()->vendor) {
                $orders = Order::where('vendor_id', null)->where('is_final', 1)->where('order_status_id', '!=', 5)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
                $other_income = BankTransaction::where('vendor_id', null)->where('other_income', 1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
                $expenses = ExpenseEntry::where('vendor_id', null)->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->orderBy('id', 'DESC')->get();
                $expense_types = Expense::orderBy('type', 'ASC')->where('vendor_id', null)->get();
                $vat_entries = VatEntry::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->where('vendor_id', null)->with('order')->latest()->get();
                $vendors = Vendor::orderBy('name', 'ASC')->where('is_active', 1)->with('vat_entries_reports', 'expense_entries_reports', 'other_incomes_report')->get();
            } else {
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('is_final', 1)->where('order_status_id', '!=', 5)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
                $other_income = BankTransaction::where('vendor_id', Auth::user()->vendor->id)->where('other_income', 1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
                $expenses = ExpenseEntry::where('vendor_id', Auth::user()->vendor->id)->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->orderBy('id', 'DESC')->get();
                $expense_types = Expense::orderBy('type', 'ASC')->where('vendor_id', Auth::user()->vendor->id)->get();
                $vat_entries = VatEntry::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->where('vendor_id', Auth::user()->vendor->id)->with('order')->latest()->get();
                $vendors = null;
            }
            // $vat_outstanding = $vat_entries->where('is_paid', 0)->sum('vat_amount');
            // $vat_paid = $vat_entries->where('is_paid', 1)->sum('vat_amount');
            $vat_amount = $vat_entries->sum('vat_amount');

            $production_cost = 0;
            $retail_production_cost = 0;
            $wholesale_production_cost = 0;

            $retail_order_amount = $orders->where('source', '!=', 'Wholesale')->sum('price');
            $wholesale_order_amount = $orders->where('source', 'Wholesale')->sum('price');
            // $order_amount = $retail_order_amount + $wholesale_order_amount;
            $order_amount = $orders->sum('price');

            foreach ($orders as $order) {
                if ($order->source != 'Wholesale') {
                    $retail_production_cost += $order->order_product->sum(function ($t) {
                        $qty = $t->qty - $t->return_qty;
                        return $t->production_cost * $qty;
                    });
                } else {
                    $wholesale_production_cost += $order->order_product->sum(function ($t) {
                        $qty = $t->qty - $t->return_qty;
                        return $t->production_cost * $qty;
                    });
                }
                $production_cost += $order->order_product->sum(function ($t) {
                    $qty = $t->qty - $t->return_qty;
                    return $t->production_cost * $qty;
                });
            }
            // $production_cost = $retail_production_cost + $wholesale_production_cost;

            return view('admin.report.income-statement', compact('order_amount', 'production_cost', 'retail_order_amount', 'wholesale_order_amount', 'retail_production_cost', 'wholesale_production_cost', 'other_income', 'expenses', 'expense_types', 'date_from', 'date_to', 'vat_amount', 'vendors'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function income_statement_search(Request $request) {

        if (auth()->user()->can('setting.index')) {
            $date_from = '';
            $date_to = '';

            if (!empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->whereBetween('created_at', [$start_date, $end_date])->get();
                $other_income = BankTransaction::where('other_income', 1)->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
                $expenses = ExpenseEntry::whereBetween('date', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
                $vat_entries = VatEntry::whereBetween('created_at', [$start_date, $end_date])->where('vendor_id', null)->with('order')->latest()->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            } else {
                $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
                $other_income = BankTransaction::where('other_income', 1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
                $expenses = ExpenseEntry::whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->orderBy('id', 'DESC')->get();
                $vat_entries = VatEntry::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->where('vendor_id', null)->with('order')->latest()->get();
            }

            if (!Auth::user()->vendor) {
                $orders = $orders->where('vendor_id', null);
                $other_income = $other_income->where('vendor_id', null);
                $expenses = $expenses->where('vendor_id', null);
                $vat_entries = $vat_entries->where('vendor_id', null);
                $expense_types = Expense::orderBy('type', 'ASC')->where('vendor_id', null)->get();
                $vendors = Vendor::orderBy('name', 'ASC')->where('is_active', 1)->with('orders', 'orders.order_product', 'vat_entries', 'expense_types', 'expense_entries', 'bank_transactions')->get();
            } else {
                $orders = $orders->where('vendor_id', Auth::user()->vendor->id);
                $other_income = $other_income->where('vendor_id', Auth::user()->vendor->id);
                $expenses = $expenses->where('vendor_id', Auth::user()->vendor->id);
                $vat_entries = $vat_entries->where('vendor_id', Auth::user()->vendor->id);
                $expense_types = Expense::orderBy('type', 'ASC')->where('vendor_id', Auth::user()->vendor->id)->get();
                $vendors = null;
            }

            // $vat_outstanding = $vat_entries->where('is_paid', 0)->sum('vat_amount');
            // $vat_paid = $vat_entries->where('is_paid', 1)->sum('vat_amount');
            $vat_amount = $vat_entries->sum('vat_amount');

            $production_cost = 0;
            $retail_production_cost = 0;
            $wholesale_production_cost = 0;

            $retail_order_amount = $orders->where('source', '!=', 'Wholesale')->sum('price');
            $wholesale_order_amount = $orders->where('source', 'Wholesale')->sum('price');
            // $order_amount = $retail_order_amount + $wholesale_order_amount;
            $order_amount = $orders->sum('price');

            foreach ($orders as $order) {
                if ($order->source != 'Wholesale') {
                    $retail_production_cost += $order->order_product->sum(function ($t) {
                        $qty = $t->qty - $t->return_qty;
                        return $t->production_cost * $qty;
                    });
                } else {
                    $wholesale_production_cost += $order->order_product->sum(function ($t) {
                        $qty = $t->qty - $t->return_qty;
                        return $t->production_cost * $qty;
                    });
                }
                $production_cost += $order->order_product->sum(function ($t) {
                    $qty = $t->qty - $t->return_qty;
                    return $t->production_cost * $qty;
                });
            }
            // $production_cost = $retail_production_cost + $wholesale_production_cost;
            // $expense_types = Expense::all();

            return view('admin.report.income-statement', compact('order_amount', 'production_cost', 'retail_order_amount', 'wholesale_order_amount', 'retail_production_cost', 'wholesale_production_cost', 'other_income', 'expenses', 'expense_types', 'date_from', 'date_to', 'vat_amount', 'vendors'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function income_statement_vendor($id) {
        $date_from = '';
        $date_to = '';

        if (auth()->user()->can('report.income')) {
            $vendor = Vendor::find($id);

            $orders = Order::where('vendor_id', $vendor->id)->where('is_final', 1)->where('order_status_id', '!=', 5)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
            $other_income = BankTransaction::where('vendor_id', $vendor->id)->where('other_income', 1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
            $expenses = ExpenseEntry::where('vendor_id', $vendor->id)->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->orderBy('id', 'DESC')->get();
            $expense_types = Expense::orderBy('type', 'ASC')->where('vendor_id', $vendor->id)->get();
            $vat_entries = VatEntry::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->where('vendor_id', $vendor->id)->with('order')->latest()->get();
            $vendors = null;
            // $vat_outstanding = $vat_entries->where('is_paid', 0)->sum('vat_amount');
            // $vat_paid = $vat_entries->where('is_paid', 1)->sum('vat_amount');
            $vat_amount = $vat_entries->sum('vat_amount');

            $production_cost = 0;
            $retail_production_cost = 0;
            $wholesale_production_cost = 0;

            $retail_order_amount = $orders->where('source', '!=', 'Wholesale')->sum('price');
            $wholesale_order_amount = $orders->where('source', 'Wholesale')->sum('price');
            // $order_amount = $retail_order_amount + $wholesale_order_amount;
            $order_amount = $orders->sum('price');

            foreach ($orders as $order) {
                if ($order->source != 'Wholesale') {
                    $retail_production_cost += $order->order_product->sum(function ($t) {
                        $qty = $t->qty - $t->return_qty;
                        return $t->production_cost * $qty;
                    });
                } else {
                    $wholesale_production_cost += $order->order_product->sum(function ($t) {
                        $qty = $t->qty - $t->return_qty;
                        return $t->production_cost * $qty;
                    });
                }
                $production_cost += $order->order_product->sum(function ($t) {
                    $qty = $t->qty - $t->return_qty;
                    return $t->production_cost * $qty;
                });
            }
            // $production_cost = $retail_production_cost + $wholesale_production_cost;

            return view('admin.report.income-statement-vendor', compact('order_amount', 'production_cost', 'retail_order_amount', 'wholesale_order_amount', 'retail_production_cost', 'wholesale_production_cost', 'other_income', 'expenses', 'expense_types', 'date_from', 'date_to', 'vat_amount', 'vendors', 'vendor'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function balance_sheet() {
        if (auth()->user()->can('report.balance_sheet')) {
            $banks = Bank::orderBy('name', 'DESC')->get();
            $accessories = Accessory::orderBY('id', 'DESC')->get();
            $assets = Asset::orderBy('id', 'DESC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            $partners = Partner::orderBy('id', 'DESC')->with('transactions')->get();

            $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->with('order_product')->get();
            $production_cost = 0;
            $other_income = BankTransaction::where('other_income', 1)->get();
            $expenses = ExpenseEntry::orderBy('id', 'DESC')->get();
            $order_amount = $orders->sum('price');
            foreach ($orders as $order) {
                $production_cost += $order->order_product->sum(function ($t) {
                    $qty = $t->qty - $t->return_qty;
                    return $t->production_cost * $qty;
                });
            }

            return view('admin.report.balance-sheet', compact('banks', 'accessories', 'assets', 'suppliers', 'partners', 'order_amount', 'production_cost', 'other_income', 'expenses'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function owners_equity() {
        if (auth()->user()->can('report.owners')) {
            $banks = Bank::orderBy('name', 'DESC')->get();
            $accessories = Accessory::orderBY('id', 'DESC')->get();
            $assets = Asset::orderBy('id', 'DESC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            $partners = Partner::orderBy('id', 'DESC')->with('transactions')->get();

            $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->with('order_product')->get();
            $production_cost = 0;
            $other_income = BankTransaction::where('other_income', 1)->get();
            $expenses = ExpenseEntry::orderBy('id', 'DESC')->get();
            $order_amount = $orders->sum('price');
            foreach ($orders as $order) {
                $production_cost += $order->order_product->sum(function ($t) {
                    $qty = $t->qty - $t->return_qty;
                    return $t->production_cost * $qty;
                });
            }

            return view('admin.report.owners-equity', compact('banks', 'accessories', 'assets', 'suppliers', 'partners', 'order_amount', 'production_cost', 'other_income', 'expenses'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
