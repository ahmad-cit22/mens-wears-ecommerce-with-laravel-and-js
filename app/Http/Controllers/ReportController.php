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
use Carbon\Carbon;

class ReportController extends Controller {
    public function income_statement() {
        $date_from = '';
        $date_to = '';

        if (auth()->user()->can('setting.index')) {
            $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
            $production_cost = 0;
            $other_income = BankTransaction::where('other_income', 1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
            $expenses = ExpenseEntry::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->orderBy('id', 'DESC')->get();
            $expense_types = Expense::all();
            $order_amount = $orders->sum('price');
            foreach ($orders as $order) {
                $production_cost += $order->order_product->sum(function ($t) {
                    return $t->production_cost * $t->qty;
                });
            }

            return view('admin.report.income-statement', compact('order_amount', 'production_cost', 'other_income', 'expenses', 'expense_types', 'date_from', 'date_to'));
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
                $expenses = ExpenseEntry::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            } else {
                $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
                $other_income = BankTransaction::where('other_income', 1)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->get();
                $expenses = ExpenseEntry::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->orderBy('id', 'DESC')->get();
            }
            $production_cost = 0;
            $order_amount = $orders->sum('price');
            foreach ($orders as $order) {
                $production_cost += $order->order_product->sum(function ($t) {
                    return $t->production_cost * $t->qty; 
                });
            }
            $expense_types = Expense::all();

            return view('admin.report.income-statement', compact('order_amount', 'production_cost', 'other_income', 'expenses', 'expense_types', 'date_from', 'date_to'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function balance_sheet() {
        if (auth()->user()->can('setting.index')) {
            $banks = Bank::orderBy('name', 'DESC')->get();
            $accessories = Accessory::orderBY('id', 'DESC')->get();
            $assets = Asset::orderBy('id', 'DESC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            $partners = Partner::orderBy('id', 'DESC')->get();

            $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->get();
            $production_cost = 0;
            $other_income = BankTransaction::where('other_income', 1)->get();
            $expenses = ExpenseEntry::orderBy('id', 'DESC')->get();
            $order_amount = $orders->sum('price');
            foreach ($orders as $order) {
                $production_cost += $order->order_product->sum(function ($t) {
                    return $t->production_cost * $t->qty;
                });
            }

            return view('admin.report.balance-sheet', compact('banks', 'accessories', 'assets', 'suppliers', 'partners', 'order_amount', 'production_cost', 'other_income', 'expenses'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function owners_equity() {
        if (auth()->user()->can('setting.index')) {
            $banks = Bank::orderBy('name', 'DESC')->get();
            $accessories = Accessory::orderBY('id', 'DESC')->get();
            $assets = Asset::orderBy('id', 'DESC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            $partners = Partner::orderBy('id', 'DESC')->get();

            $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->get();
            $production_cost = 0;
            $other_income = BankTransaction::where('other_income', 1)->get();
            $expenses = ExpenseEntry::orderBy('id', 'DESC')->get();
            $order_amount = $orders->sum('price');
            foreach ($orders as $order) {
                $production_cost += $order->order_product->sum(function ($t) {
                    return $t->production_cost * $t->qty;
                });
            }

            return view('admin.report.owners-equity', compact('banks', 'accessories', 'assets', 'suppliers', 'partners', 'order_amount', 'production_cost', 'other_income', 'expenses'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
