<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Artisan;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Sitemap\SitemapGenerator;

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
            $orders_not_final = Order::all();
            $yearly_orders = Order::where('is_final', 1)->whereYear('created_at', Carbon::now()->year)->get();
            $monthly_orders = Order::where('is_final', 1)->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month)
                ->get();
            $daily_orders = Order::where('is_final', 1)->whereDate('created_at', Carbon::today())->get();
            return view('admin.index', compact('orders', 'orders_not_final', 'yearly_orders', 'monthly_orders', 'daily_orders'));
        } else if (Auth::user()->type == 2) {
            return redirect()->route('customer.account');
        } else {
            session()->flash('error', 'Access Denied !');
            return back();
        }
    }

    public function cache_clear() {
        Artisan::call("cache:clear");

        Alert::toast('Cache cleared!', 'success');
        return back();
    }

    public function sitemap_generate() {
        $path = public_path('sitemap.xml');
        SitemapGenerator::create('https://gobyfabrifest.com')->writeToFile($path);

        Alert::toast('Sitemap generated!', 'success');
        return back();
    }
}
