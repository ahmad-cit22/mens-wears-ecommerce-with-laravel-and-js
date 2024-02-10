<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductStockHistory;
use App\Models\WorkTrackingEntry;
use Illuminate\Http\Request;
use Auth;
use PDF;
use Session;
use Alert;
use App\Models\Category;
use Carbon\Carbon;
use DataTables;
use DB;

class ProductStockHistoryController extends Controller {

    public function add_stock() {

        if (auth()->user()->can('add.stock')) {
            $products = Product::orderBy('id', 'DESC')->get();

            return view('admin.product.stock.add_stock', [
                'products' => $products,
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function add_stock_barcode_scan(Request $request) {
        $barcode = $request->barcode;
        $stock_id = $barcode - 1000;
        $stock = ProductStock::find($stock_id);
        $size = ProductStock::find($stock_id)->size;
        if (!is_null($stock)) {
            $product = $stock->product;
        }

        return ['product' => $product, 'stock' => $stock, 'size' => $size];
    }

    public function current(Request $request) {
        if (auth()->user()->can('current.stock.view')) {
            if ($request->ajax()) {
                $stocks = ProductStock::orderBy('id', 'DESC')->with('product', 'size')->get();
                return Datatables::of($stocks)
                    ->addIndexColumn()
                    ->addColumn('product', function ($row) {
                        if ($row->product != null) {
                            $title = $row->product->title;
                            if ($row->product->is_active) {
                                return $title . '<span class="ml-3 badge badge-success">Active</span>';
                            } else {
                                return $title . '<span class="ml-3 badge badge-danger">Inactive</span>';
                            }
                        }
                    })
                    ->addColumn('size_id', function ($row) {
                        if ($row->product != null) {
                            $size = $row->size->title;
                            return $size;
                        }
                    })
                    ->addColumn('production_cost', function ($row) {
                        if ($row->product != null) {
                            $production_cost_ppc = $row->production_cost;
                            $production_cost = $row->production_cost * $row->qty;
                            return $production_cost . ' TK (' . $production_cost_ppc . '/pc)';
                        }
                    })
                    ->addColumn('price', function ($row) {
                        if ($row->product != null) {
                            $price_ppc = $row->price;
                            $price = $row->price * $row->qty;
                            return $price . ' TK (' . $price_ppc . '/pc)';
                        }
                    })
                    ->addColumn('discount_price', function ($row) {
                        if ($row->product != null) {
                            $discount_price_ppc = $row->discount_price;
                            $discount_price = $row->discount_price * $row->qty;
                            return $discount_price . ' TK (' . $discount_price_ppc . '/pc)';
                        }
                    })
                    ->addColumn('wholesale_price', function ($row) {
                        if ($row->product != null) {
                            $wholesale_price_ppc = $row->wholesale_price;
                            $wholesale_price = $row->wholesale_price * $row->qty;
                            return $wholesale_price . ' TK (' . $wholesale_price_ppc . '/pc)';
                        }
                    })
                    ->rawColumns(['product'])
                    ->make(true);
            }
            $products = Product::where('is_active', 1)->orderBy('id', 'DESC')->get();
            $categories = Category::all();
            $p_stocks = ProductStock::where('is_active', 1)->orderBy('id', 'DESC')->with('product', 'size')->get();
            $total_production_cost = ProductStock::where('is_active', 1)->get()->sum(function ($t) {
                return $t->production_cost * $t->qty;
            });

            $price = ProductStock::where('is_active', 1)->get()->sum(function ($t) {
                return $t->price * $t->qty;
            });

            return view('admin.product.stock.current', [
                'products' => $products,
                'total_production_cost' => $total_production_cost,
                'total_price' => $price,
                'categories' => $categories,
                'p_stocks' => $p_stocks,
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index(Request $request) {
        if (auth()->user()->can('stock.history')) {
            if ($request->ajax()) {
                $stocks = ProductStockHistory::orderBy('created_at', 'desc')->get();
                return Datatables::of($stocks)
                    ->addIndexColumn()
                    ->addColumn('product', function ($row) {
                        if ($row->product != null) {
                            $title = $row->product->title;
                            if ($row->product->is_active) {
                                return $title . '<span class="ml-3 badge badge-success">Active</span>';
                            } else {
                                return $title . '<span class="ml-3 badge badge-danger">Inactive</span>';
                            }
                        }
                    })
                    ->addColumn('size_id', function ($row) {
                        if ($row->product != null) {
                            $size = $row->size->title;
                            return $size;
                        }
                    })
                    ->addColumn('production_cost', function ($row) {
                        if ($row->product != null) {
                            $production_cost_ppc = $row->product->variation->production_cost;
                            $production_cost = $row->product->variation->production_cost * $row->qty;
                            return $production_cost . ' TK (' . $production_cost_ppc . '/pc)';
                        }
                    })
                    ->addColumn('price', function ($row) {
                        if ($row->product != null) {
                            $price_ppc = $row->product->variation->price;
                            $price = $row->product->variation->price * $row->qty;
                            return $price . ' TK (' . $price_ppc . '/pc)';
                        }
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
            $stocks = ProductStockHistory::orderBy('created_at', 'desc')->get();

            return view('admin.product.stock.index', [
                'stocks' => $stocks,
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request) {
        if (auth()->user()->can('product.edit')) {
            $validatedData = $request->validate([
                'product_id' => 'required',
                'qty' => 'required',
            ]);

            $product_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;
            $remarks = $request->remarks;
            $reference_code = $request->reference_code;

            foreach ($request->product_id as $key => $product_id) {

                $stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id[$key])->first();
                $stock->qty += $qty[$key];
                $stock->save();

                $history = new ProductStockHistory;
                $history->product_id = $product_id;
                $history->size_id = $size_id[$key];
                $history->qty = $qty[$key];
                $history->reference_code = $reference_code;
                $history->remarks = $remarks[$key];
                $history->note = "Stockin";
                $history->save();
            }

            WorkTrackingEntry::create([
                        'product_id' => $product_id,
                        'product_stock_history_id' => $history->id,
                        'user_id' => Auth::id(),
                        'work_name' => 'add_stock'
            ]);

            Alert::toast('Stock Updated', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
