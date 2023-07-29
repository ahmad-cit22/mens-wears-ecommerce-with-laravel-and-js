<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Alert;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        if (Auth::user()->type == 1) {
            $orders = Order::where('is_final', 1)->get();
            $yearly_orders = Order::where('is_final', 1)->whereYear('created_at', Carbon::now()->year)->get();
            $monthly_orders = Order::where('is_final', 1)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)
                ->get();
            $daily_orders = Order::where('is_final', 1)->whereDate('created_at', Carbon::today())->get();
            return view('admin.index', compact('orders', 'yearly_orders', 'monthly_orders', 'daily_orders'));
        } else if (Auth::user()->type == 2) {
            return redirect()->route('customer.account');
        } else {
            session()->flash('error', 'Access Denied !');
            return back();
        }
    }
}
