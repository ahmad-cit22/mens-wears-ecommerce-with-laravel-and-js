<?php

namespace App\Http\Controllers;

use App\Models\ProductDamage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductStockHistory;
use App\Models\WorkTrackingEntry;
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
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                           $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' .$row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by')
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

    public function product_barcode_scan(Request $request) {
        $barcode = $request->barcode;
        $stock_id = $barcode - 1000;
        $stock = ProductStock::find($stock_id);

        if ($stock) {
            $size = ProductStock::find($stock_id)->size;
            if (!is_null($stock)) {
                $product = $stock->product;
            }
    
            return ['product' => $product, 'stock' => $stock, 'size' => $size];
        } else {
            return ['product' => null, 'stock' => null, 'size' => null];
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

            $damage = new ProductDamage;
            $damage->product_id = $product_id;
            $damage->size_id = $size_id;
            $damage->qty = $qty;
            $damage->production_cost = $stock->production_cost;
            $damage->code = $stock->code;
            $damage->note = $request->note;
            $damage->save();

            $history = new ProductStockHistory;
            $history->product_id = $product_id;
            $history->size_id = $size_id;
            $history->qty = $qty;
            $history->remarks = $request->note;
            $history->note = "Damage";
            $history->save();
            
            WorkTrackingEntry::create([
                'product_id' => $product_id,
                'product_stock_history_id' => $history->id,
                'damaged_product_id' => $damage->id,
                'user_id' => Auth::id(),
                'work_name' => 'damage_product'
            ]);

            Alert::toast('Stock Updated', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
