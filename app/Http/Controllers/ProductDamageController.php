<?php

namespace App\Http\Controllers;

use App\Models\ProductDamage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use Auth;
use PDF;
use Session;
use Alert;
use Carbon\Carbon;
use DataTables;

class ProductDamageController extends Controller {
    public function index(Request $request) {
        if (auth()->user()->can('damage.view')) {
            if ($request->ajax()) {
                $stocks = ProductDamage::orderBy('created_at', 'desc')->get();
                return Datatables::of($stocks)
                    // ->addIndexColumn()
                    ->addColumn('product', function ($row) {
                        $title = optional($row->product)->title;
                        if (optional($row->product)->is_active) {
                            return $title . '<span class="ml-3 badge badge-success">Active</span>';
                        } else {
                            return $title . '<span class="ml-3 badge badge-danger">Inactive</span>';
                        }
                    })
                    ->addColumn('size_id', function ($row) {
                        $title = optional($row->size)->title;
                        return $title;
                    })
                    ->addColumn('date', function ($row) {
                        if ($row->product != null) {
                            $date = Carbon::parse($row->created_at)->format('d M, Y');
                            return $date;
                        }
                    })
                    ->rawColumns(['product'])
                    ->make(true);
            }

            $products = Product::orderBy('created_at', 'desc')->get();

            return view('admin.product.damage.index', compact('products'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request) {
        if (auth()->user()->can('damage.store')) {
            $validatedData = $request->validate([
                'product_id' => 'required|numeric',
                'qty' => 'required|numeric',
            ]);

            $product_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;
            $stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id)->first();
            $stock->qty -= $qty;
            $stock->save();

            $history = new ProductDamage;
            $history->product_id = $product_id;
            $history->size_id = $size_id;
            $history->qty = $qty;
            $history->production_cost = $stock->production_cost;
            $history->code = $stock->code;
            $history->note = $request->note;
            $history->save();
            Alert::toast('Stock Updated', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
