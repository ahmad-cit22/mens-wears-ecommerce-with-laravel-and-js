<?php

namespace App\Http\Controllers;

use App\Models\ProductDamage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RejectedProduct;
use App\Models\RejectedProductSell;
use App\Models\RejectedProductStock;
use App\Models\ProductStock;
use App\Models\ProductStockHistory;
use App\Models\ExpenseEntry;
use App\Models\BankTransaction;
use App\Models\WorkTrackingEntry;
use Auth;
use PDF;
use Session;
use Alert;
use App\Models\Size;
use Carbon\Carbon;
use DataTables;

class RejectedProductController extends Controller {
    public function add_view(Request $request) {
        if (auth()->user()->can('reject.index')) {
            $products = Product::orderBy('created_at', 'desc')->get();
            $sizes = Size::all();

            return view('admin.product.reject.add', compact('products', 'sizes'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index(Request $request) {
        if (auth()->user()->can('reject.index')) {
            if ($request->ajax()) {
                $reject_products = RejectedProduct::orderBy('created_at', 'desc')->with('product', 'created_by', 'created_by.adder', 'size')->get();
                return Datatables::of($reject_products)
                    // ->addIndexColumn()
                    ->addColumn('product', function ($row) {
                        if ($row->is_transfer == 1) {
                            return $title = optional($row->product)->title . '  <span class="badge badge-info">Transferred</span>';
                        } else {
                            return $title = optional($row->product)->title;
                        }
                    })
                    ->addColumn('size_id', function ($row) {
                        $title = optional($row->size)->title;
                        return $title;
                    })
                    ->addColumn('type', function ($row) {

                        if ($row->type == 1) {
                            $data = '<span class="badge badge-success">Product In</span>';
                        } else {
                            $data = '<span class="badge badge-danger">Product Out</span>';
                        }

                        return $data;
                    })
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                            $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' . $row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by')
                    ->addColumn('date', function ($row) {
                        if ($row->product != null) {
                            $date = Carbon::parse($row->created_at)->format('d M, Y g:iA');
                            return $date;
                        }
                    })
                    ->rawColumns(['product'])
                    ->make(true);
            }

            $products = Product::orderBy('created_at', 'desc')->get();
            $sizes = Size::all();

            return view('admin.product.reject.index', compact('products', 'sizes'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request) {
        if (auth()->user()->can('reject.index')) {
            $validatedData = $request->validate([
                'product_id' => 'required|numeric',
                'size_id' => 'required|numeric',
                'qty' => 'required|numeric',
            ]);

            $product_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;

            $reject = new RejectedProduct;
            $reject->product_id = $product_id;
            $reject->size_id = $size_id;
            $reject->qty = $qty;
            $reject->note = $request->note;
            $reject->date = Carbon::today();
            if ($request->has('is_transfer')) {
                $reject->is_transfer = 1;
            }
            $reject->save();

            $stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id)->first();
            $stock->qty -= $qty;
            $stock->save();

            $history = new ProductStockHistory;
            $history->product_id = $product_id;
            $history->size_id = $size_id;
            $history->qty = $qty;
            $history->remarks = 'Reject Product History ID - ' . $reject->id;
            $history->note = "Reject Product";

            $history->save();

            $reject_stock = RejectedProductStock::where('product_id', $product_id)->where('size_id', $size_id);
            if ($reject_stock->exists()) {
                $reject_stock = $reject_stock->first();
                $reject_stock->qty += $qty;
                $reject_stock->save();
            } else {
                $reject_stock = new RejectedProductStock;
                $reject_stock->product_id = $product_id;
                $reject_stock->size_id = $size_id;
                $reject_stock->qty = $qty;
                $reject_stock->save();
            }

            if ($request->has('is_transfer')) {
                $expense = new ExpenseEntry;
                $expense->expense_id = 23;
                $expense->bank_id = $request->bank_id;
                $expense->amount = $stock->production_cost * $qty;
                $expense->date = Carbon::today();
                $expense->note = $request->note;
                $expense->save();

                if ($request->bank_id != '' && $request->bank_id > 0) {
                    $transaction = new BankTransaction;
                    $transaction->bank_id = $request->bank_id;
                    $transaction->expense_id = $expense->id;
                    $transaction->note = $request->note;
                    $transaction->debit = $stock->production_cost * $qty;
                    $transaction->date = Carbon::today();
                    $transaction->save();
                }

                WorkTrackingEntry::create([
                    'rejected_product_id' => $reject->id,
                    'expense_entry_id' => $expense->id,
                    'user_id' => Auth::id(),
                    'work_name' => 'reject_product'
                ]);
            } else {
                WorkTrackingEntry::create([
                    'rejected_product_id' => $reject->id,
                    'user_id' => Auth::id(),
                    'work_name' => 'reject_product'
                ]);
            }

            Alert::toast('Reject Product Added', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function stock(Request $request) {
        if (auth()->user()->can('reject.index')) {
            $reject_stocks  = RejectedProductStock::orderBy('id', 'DESC')->with('product', 'size')->get();
            if ($request->ajax()) {
                return Datatables::of($reject_stocks)
                    ->addIndexColumn()
                    ->addColumn('product', function ($row) {
                        if ($row->product != null) {
                            return $title = $row->product->title;
                        }
                    })
                    ->addColumn('size_id', function ($row) {
                        if ($row->product != null) {
                            $size = $row->size->title;
                            return $size;
                        }
                    })
                    ->rawColumns(['product'])
                    ->make(true);
            }

            return view('admin.product.reject.stock', compact('reject_stocks'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function product_out_form(Request $request) {
        if (auth()->user()->can('reject.index')) {
            $reject_stocks = RejectedProductStock::orderBy('id', 'DESC')->with('product', 'size')->get();

            return view('admin.product.reject.out', compact('reject_stocks'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function product_out_store(Request $request) {
        if (auth()->user()->can('reject.index')) {
            $validatedData = $request->validate([
                'product_id' => 'required|numeric',
                'size_id' => 'required|numeric',
                'qty' => 'required|numeric',
            ]);

            $product_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;

            $reject_out = new RejectedProductSell;
            $reject_out->product_id = $product_id;
            $reject_out->size_id = $size_id;
            $reject_out->qty = $qty;
            $reject_out->note = $request->note;
            $reject_out->price = $request->price;
            $reject_out->save();

            $reject_stock = RejectedProductStock::where('product_id', $product_id)->where('size_id', $size_id)->first();
            $reject_stock->qty -= $qty;
            $reject_stock->save();

            $reject = new RejectedProduct;
            $reject->product_id = $product_id;
            $reject->size_id = $size_id;
            $reject->qty = $qty;
            $reject->type = 2;
            $reject->note = $request->note;
            $reject->date = Carbon::today();
            $reject->save();

            if ($request->has('is_others_income')) {
                $transaction = new BankTransaction;
                $transaction->bank_id = $request->bank_id;
                $transaction->note = 'Reject Sell ID - ' . $reject_out->id;
                $transaction->date = Carbon::today();
                $transaction->credit = $request->price;
                if ($request->has('is_others_income')) {
                    $transaction->other_income = 1;
                }
                $transaction->save();
            }

            WorkTrackingEntry::create([
                'rejected_product_id' => $reject->id,
                'user_id' => Auth::id(),
                'work_name' => 'reject_product_out'
            ]);

            Alert::toast('Reject Product Out Done!', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function product_out_list(Request $request) {
        if (auth()->user()->can('reject.index')) {
            if ($request->ajax()) {
                $reject_product_outs = RejectedProductSell::orderBy('created_at', 'desc')->with('product', 'size')->get();
                return Datatables::of($reject_product_outs)
                    // ->addIndexColumn()
                    ->addColumn('product', function ($row) {
                        return $title = optional($row->product)->title;
                    })
                    ->addColumn('size_id', function ($row) {
                        $title = optional($row->size)->title;
                        return $title;
                    })
                    ->addColumn('date', function ($row) {
                        if ($row->product != null) {
                            $date = Carbon::parse($row->created_at)->format('d M, Y g:iA');
                            return $date;
                        }
                    })
                    ->rawColumns(['product'])
                    ->make(true);
            }
            $reject_stocks = RejectedProductStock::orderBy('id', 'DESC')->with('product', 'size')->get();

            return view('admin.product.reject.out-list', compact('reject_stocks'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
