<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
use App\Models\VendorProduct;
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

    public function current(Request $request) {
        if (auth()->user()->can('current.stock.view')) {
            if (!Auth::user()->vendor) {
                $stocks = ProductStock::where('is_active', 1)->orderBy('id', 'DESC')->where('vendor_id', null)->with('product', 'size')->get();
                $categories = Category::all();
                $p_stocks = ProductStock::where('is_active', 1)->where('vendor_id', null)->orderBy('id', 'DESC')->with('product', 'size')->get();
                $total_production_cost = ProductStock::where('is_active', 1)->where('vendor_id', null)->get()->sum(function ($t) {
                    return $t->production_cost * $t->qty;
                });

                $price = ProductStock::where('is_active', 1)->where('vendor_id', null)->get()->sum(function ($t) {
                    return $t->price * $t->qty;
                });
            } else {
                $stocks = ProductStock::where('is_active', 1)->orderBy('id', 'DESC')->where('vendor_id', Auth::user()->vendor->id)->with('product', 'size')->get();

                $p_stocks = ProductStock::where('is_active', 1)->where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->with('product', 'size')->get();
                $total_production_cost = ProductStock::where('is_active', 1)->where('vendor_id', Auth::user()->vendor->id)->get()->sum(function ($t) {
                    return $t->production_cost * $t->qty;
                });

                $price = ProductStock::where('is_active', 1)->where('vendor_id', Auth::user()->vendor->id)->get()->sum(function ($t) {
                    return $t->price * $t->qty;
                });
                $vendor_products = Auth::user()->vendor->vendor_products->pluck('product_id')->toArray();
                $product_categories = Product::whereIn('id', $vendor_products)->orderBy('id', 'DESC')->with('category')->get()->pluck('category_id')->toArray();
                $categories = Category::whereIn('id', $product_categories)->get();
            }

            if ($request->ajax()) {

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


            return view('admin.product.stock.current', [
                // 'products' => $products,
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

        $date_from = '';
        $date_to = '';
        $reason = '';
        if (!Auth::user()->vendor) {
            $stocks = ProductStockHistory::orderBy('created_at', 'desc')->where('vendor_id', null)->with('product', 'product.variation', 'created_by', 'created_by.adder', 'size')->get();
        } else {
            $stocks = ProductStockHistory::orderBy('created_at', 'desc')->where('vendor_id', Auth::user()->vendor->id)->with('product', 'product.variation', 'created_by', 'created_by.adder', 'size')->get();
        }


        if (auth()->user()->can('stock.history')) {
            if ($request->ajax()) {
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
                    ->addColumn('note', function ($row) {

                        $data = '<b>' . $row->note . '</b>';

                        return $data;
                    })
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                            $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' . $row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by', 'note')
                    ->addColumn('date', function ($row) {
                        if ($row->product != null) {
                            $date = Carbon::parse($row->created_at)->format('d M, Y g:iA');
                            return $date;
                        }
                    })
                    ->rawColumns(['product'])
                    ->make(true);
            }

            return view('admin.product.stock.index', compact('stocks', 'date_from', 'date_to', 'reason'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function stock_history_search(Request $request) {

        $date_from = '';
        $date_to = '';
        $reason = '';

        if (!empty($request->reason) && !empty($request->date_from) && !empty($request->date_to)) {
            $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
            $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
            $reason = $request->reason;

            $stocks = ProductStockHistory::where('note', $reason)
                ->whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->with('product', 'product.variation', 'created_by', 'created_by.adder', 'size')->get();

            $date_from = $request->date_from;
            $date_to = $request->date_to;
        }
        if ((!empty($request->reason) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->reason) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->reason) && empty($request->date_from) && !empty($request->date_to))) {

            $reason = $request->reason;

            $stocks = ProductStockHistory::where('note', $reason)->orderBy('created_at', 'desc')->with('product', 'product.variation', 'created_by', 'created_by.adder', 'size')->get();
        }
        if (empty($request->reason) && !empty($request->date_from) && !empty($request->date_to)) {
            $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
            $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
            $reason = $request->reason;
            $stocks = ProductStockHistory::whereBetween('created_at', [$start_date, $end_date])->orderBy('created_at', 'desc')->with('product', 'product.variation', 'created_by', 'created_by.adder', 'size')->get();

            $date_from = $request->date_from;
            $date_to = $request->date_to;
        }
        if (empty($request->reason) && (empty($request->date_from) || empty($request->date_to))) {
            $stocks = ProductStockHistory::orderBy('created_at', 'desc')->with('product', 'product.variation', 'created_by', 'created_by.adder', 'size')->get();
        }

        if (!Auth::user()->vendor) {
            $stocks = $stocks->where('vendor_id', null);
        } else {
            $stocks = $stocks->where('vendor_id', Auth::user()->vendor->id);
        }

        if (auth()->user()->can('stock.history')) {
            if ($request->ajax()) {
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
                    ->addColumn('note', function ($row) {

                        $data = '<b>' . $row->note . '</b>';

                        return $data;
                    })
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                            $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' . $row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by', 'note')
                    ->addColumn('date', function ($row) {
                        if ($row->product != null) {
                            $date = Carbon::parse($row->created_at)->format('d M, Y');
                            return $date;
                        }
                    })
                    ->rawColumns(['product'])
                    ->make(true);
            }
            // $stocks = ProductStockHistory::orderBy('created_at', 'desc')->with('product', 'product.variation', 'created_by')->get();

            return view('admin.product.stock.index', compact('stocks', 'date_from', 'date_to', 'reason'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function total_sold_amount(Request $request) {
        if (auth()->user()->can('stock.history')) {

            if (!Auth::user()->vendor) {
                $orders = Order::where('vendor_id', null)->orderBy('id', 'DESC')->where('is_final', 1)->where('source', '!=', 'Wholesale')->with('order_product', 'order_product.product', 'order_product.product.variation')->get();
            } else {
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->where('is_final', 1)->where('source', '!=', 'Wholesale')->with('order_product', 'order_product.product', 'order_product.product.variation')->get();
            }

            return view('admin.product.stock.modals.total-sold-amount', [
                'orders' => $orders,
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function total_remaining_amount(Request $request) {
        if (auth()->user()->can('stock.history')) {
            if (!Auth::user()->vendor) {
                $current_remaining_stock = ProductStock::where('qty', '>', 0)->where('vendor_id', null)->get();
            } else {
                $current_remaining_stock = ProductStock::where('qty', '>', 0)->where('vendor_id', Auth::user()->vendor->id)->get();
            }

            $cost_remaining = $current_remaining_stock->sum(function ($data) {
                return $data->production_cost * $data->qty;
            });

            return view('admin.product.stock.modals.total-remaining-amount', [
                'cost_remaining' => $cost_remaining,
            ]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request) {
        if (auth()->user()->can('product.edit')) {
            // $validatedData = $request->validate([
            //     'product_id' => 'required',
            //     'qty' => 'required',
            // ]);

            $products_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;

            foreach ($products_id as $key => $product_id) {
                if (count($products_id) == 1 || count($products_id) - 1 != $key) {
                    if (!$product_id || !$size_id[$key] || !$qty[$key]) {
                        Alert::toast('Fill the fields correctly! Product, Size and Quantity are must', 'error');
                        return back();
                    }
                }
            }

            $remarks = $request->remarks;
            $reference_code = $request->reference_code;

            foreach ($products_id as $key => $product_id) {

                $stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id[$key])->where('vendor_id', null)->first();
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

                WorkTrackingEntry::create([
                    'product_id' => $product_id,
                    'product_stock_history_id' => $history->id,
                    'user_id' => Auth::id(),
                    'work_name' => 'add_stock'
                ]);
            }


            Alert::toast('Stock Updated', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
