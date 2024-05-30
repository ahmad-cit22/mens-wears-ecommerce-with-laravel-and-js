<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductStockHistory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorProduct;
use App\Models\VendorTransaction;
use App\Models\WorkTrackingEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class VendorController extends Controller {
    public function index() {
        if (auth()->user()->can('vendors.index')) {
            $vendors = Vendor::with('user', 'transactions')->get();
            $users = User::where('type', 1)->orderBy('name', 'ASC')->get();

            return view('admin.vendors.index', compact('vendors', 'users'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    // public function vendor_transactions() {
    //     if (auth()->user()->can('report.owners')) {
    //         $vendors = Vendor::with('user', 'transactions')->get();
    //         $users = User::where('type', 1)->orderBy('name', 'ASC')->get();

    //         return view('admin.vendors.index', compact('vendors', 'users'));
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (auth()->user()->can('vendors.index')) {
            $validatedData = $request->validate([
                'name' => 'required',
                'user_id' => 'required|not_in:0',
                'branch_name' => 'required',
                'branch_address' => 'required',
                'invested_amount' => 'required',
                'profit_percentage' => 'required',
            ]);

            // create new vendor
            $vendor = new Vendor;
            $vendor->name = $request->name;
            $vendor->user_id = $request->user_id;
            $vendor->branch_name = $request->branch_name;
            $vendor->branch_address = $request->branch_address;
            $vendor->invested_amount = $request->invested_amount;
            $vendor->opening_balance = $request->invested_amount;
            $vendor->profit_percentage = $request->profit_percentage;
            $vendor->save();

            $transaction = new VendorTransaction();
            $transaction->vendor_id = $vendor->id;
            $transaction->credit = $request->invested_amount;
            $transaction->note = 'Opening Balance';
            $transaction->date = Carbon::today()->format('Y-m-d');
            $transaction->save();

            Alert::toast('Vendor Listed', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (auth()->user()->can('vendors.index')) {
            $vendor = Vendor::find($id);
            if ($vendor) {
                $validatedData = $request->validate([
                    'name' => 'required',
                    'branch_name' => 'required',
                    'branch_address' => 'required',
                    'user_id' => 'required|not_in:0',
                    'profit_percentage' => 'required',
                ]);

                $vendor->update($validatedData);
                Alert::toast('Vendor Updated', 'success');
            } else {
                Alert::toast('Vendor Not Found', 'error');
            }

            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('vendors.index')) {
            $vendor = Vendor::find($id);
            if (!is_null($vendor)) {
                $vendor->delete();
                Alert::toast('Vendor deleted successfully!', 'success');
                return redirect()->route('vendor.index');
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function transfer_products() {
        if (auth()->user()->can('vendors.transfer_products')) {
            $vendors = Vendor::with('user', 'transactions')->get();
            $products = Product::orderBy('id', 'DESC')->get();

            $stocks = ProductStockHistory::orderBy('created_at', 'desc')->where('vendor_id', '!=', null)->where('is_approved', 0)->get();

            return view('admin.vendors.transfer-products', compact('vendors', 'products', 'stocks'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function transfer_products_store(Request $request) {
        // return $request->all();
        if (auth()->user()->can('vendors.transfer_products')) {

            $products_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;
            $vendor_id = $request->vendor_id;
            $vendor = Vendor::find($vendor_id);
            $discount = $request->discount;

            foreach ($products_id as $key => $product_id) {
                if (count($products_id) == 1 || count($products_id) - 1 != $key) {
                    if (!$product_id || !$size_id[$key] || !$qty[$key]) {
                        Alert::toast('Fill the fields correctly! Product, Size and Quantity are must.', 'error');
                        return back();
                    }
                }
            }

            $remarks = $request->remarks;

            foreach ($products_id as $key => $product_id) {
                $vendor_product = VendorProduct::where('product_id', $product_id)->where('vendor_id', $vendor_id);

                if (!$vendor_product->exists()) {
                    $vendor_product = new VendorProduct;
                    $vendor_product->vendor_id = $vendor_id;
                    $vendor_product->product_id = $product_id;
                    $vendor_product->discount = $discount;
                    $vendor_product->save();
                }

                $stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id[$key])->first();
                $stock->qty -= $qty[$key];
                $stock->save();

                $vendor_stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id[$key])->where('vendor_id', $vendor_id)->first();

                if ($vendor_stock) {
                    $vendor_stock->qty += $qty[$key];
                    $vendor_stock->save();
                } else {
                    $vendor_stock = new ProductStock;
                    $vendor_stock->product_id = $product_id;
                    $vendor_stock->size_id = $size_id[$key];
                    $vendor_stock->qty = $qty[$key];
                    $vendor_stock->vendor_id = $vendor_id;

                    $vendor_stock->code               = $stock->code;
                    $vendor_stock->production_cost    = $stock->production_cost;
                    $vendor_stock->price              = $stock->price;
                    $vendor_stock->discount_price     = $stock->discount_price;
                    $vendor_stock->wholesale_price    = $stock->wholesale_price;
                    $vendor_stock->save();
                }

                // return 1;

                $history = new ProductStockHistory;
                $history->product_id = $product_id;
                $history->size_id = $size_id[$key];
                $history->qty = $qty[$key];
                $history->remarks = "(" . $vendor->name . ") " . $remarks[$key];
                $history->note = "Transfer to Vendor";
                $history->save();

                $vendor_history = new ProductStockHistory;
                $vendor_history->product_id = $product_id;
                $vendor_history->size_id = $size_id[$key];
                $vendor_history->qty = $qty[$key];
                $vendor_history->remarks = $remarks[$key];
                $vendor_history->note = "Stockin";
                $vendor_history->vendor_id = $vendor_id;
                $vendor_history->save();

                WorkTrackingEntry::create([
                    'product_id' => $product_id,
                    'product_stock_history_id' => $history->id,
                    'user_id' => Auth::id(),
                    'work_name' => 'transfer_to_vendor'
                ]);
            }

            Alert::toast('Product Stock Transfer Successful', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function transfer_products_main() {
        if (1) {
            $vendors = null;
            $vendor_products = ProductStock::orderBy('id', 'DESC')->with('product', 'size')->where('vendor_id', Auth::user()->vendor->id)->where('qty', '>', 0)->pluck('product_id')->toArray();
            $products = Product::whereIn('id', $vendor_products)->orderBy('id', 'DESC')->get();

            return view('admin.vendors.transfer-products-main', compact('vendors', 'products'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function transfer_products_main_store(Request $request) {
        // return $request->all();
        if (1) {
            $products_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;
            $vendor_id = Auth::user()->vendor->id;
            $vendor = Vendor::find($vendor_id);
            $discount = $request->discount;

            foreach ($products_id as $key => $product_id) {
                if (count($products_id) == 1 || count($products_id) - 1 != $key) {
                    if (!$product_id || !$size_id[$key] || !$qty[$key]) {
                        Alert::toast('Fill the fields correctly! Product, Size and Quantity are must.', 'error');
                        return back();
                    }
                }
            }

            $remarks = $request->remarks;

            foreach ($products_id as $key => $product_id) {

                $vendor_history = new ProductStockHistory;
                $vendor_history->product_id = $product_id;
                $vendor_history->size_id = $size_id[$key];
                $vendor_history->qty = $qty[$key];
                $vendor_history->remarks = $remarks[$key];
                $vendor_history->note = "Transfer to Main Business";
                $vendor_history->vendor_id = $vendor_id;
                $vendor_history->is_approved = 0;
                $vendor_history->save();
            }

            Alert::toast('Product Transfer Request Sent!', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }


    public function transfer_products_vendor() {
        if (auth()->user()->can('report.owners')) {
            $vendors = Vendor::with('user', 'transactions')->get();
            $vendor_products = ProductStock::orderBy('id', 'DESC')->with('product', 'size')->where('vendor_id', Auth::user()->vendor->id)->where('qty', '>', 0)->pluck('product_id')->toArray();
            $products = Product::whereIn('id', $vendor_products)->orderBy('id', 'DESC')->get();

            return view('admin.vendors.transfer-products-vendor', compact('vendors', 'products'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function transfer_products_vendor_store(Request $request) {
        // return $request->all();
        if (1) {
            $products_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;
            $sender_id = Auth::user()->vendor->id;
            $receiver_id = $request->vendor_id;
            $sender = Vendor::find($sender_id);
            $receiver = Vendor::find($receiver_id);
            $discount = $request->discount;

            foreach ($products_id as $key => $product_id) {
                if (count($products_id) == 1 || count($products_id) - 1 != $key) {
                    if (!$product_id || !$size_id[$key] || !$qty[$key]) {
                        Alert::toast('Fill the fields correctly! Product, Size and Quantity are must.', 'error');
                        return back();
                    }
                }
            }

            $remarks = $request->remarks;

            foreach ($products_id as $key => $product_id) {

                $sender_history = new ProductStockHistory;
                $sender_history->product_id = $product_id;
                $sender_history->size_id = $size_id[$key];
                $sender_history->qty = $qty[$key];
                $sender_history->remarks = $remarks[$key];
                $sender_history->note = "Transfer to Vendor";
                $sender_history->vendor_id = $sender_id;
                $sender_history->receiver_id = $receiver_id;
                $sender_history->is_approved = 0;
                $sender_history->save();

                // $receiver_history = new ProductStockHistory;
                // $receiver_history->product_id = $product_id;
                // $receiver_history->size_id = $size_id[$key];
                // $receiver_history->qty = $qty[$key];
                // $receiver_history->remarks = $remarks[$key];
                // $receiver_history->note = "Got from Vendor";
                // $receiver_history->vendor_id = $receiver_id;
                // $receiver_history->is_approved = 0;
                // $receiver_history->save();
            }

            Alert::toast('Product Transfer Request Sent!', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    function transfer_products_approve(Request $request, $id) {
        $stock = ProductStockHistory::find($id);
        $stock->is_approved = 1;
        $stock->save();

        $product_id = $stock->product_id;
        $size_id = $stock->size_id;
        $qty = $stock->qty;
        $vendor_id = $stock->vendor_id;
        $remarks = $stock->remarks;
        $note = $stock->note;
        $receiver_id = $stock->receiver_id;
        $vendor = Vendor::find($vendor_id);

        if ($request->status == 1) {

            if ($note == 'Transfer to Main Business') {
                $stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id)->where('vendor_id', null)->first();
                $stock->qty += $qty;
                $stock->save();

                $vendor_stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id)->where('vendor_id', $vendor_id)->first();
                $vendor_stock->qty -= $qty;
                $vendor_stock->save();

                $history = new ProductStockHistory;
                $history->product_id = $product_id;
                $history->size_id = $size_id;
                $history->qty = $qty;
                $history->remarks = "(" . $vendor->name . ") " . $remarks;
                $history->note = "Transfer from Vendor";
                $history->save();

                WorkTrackingEntry::create([
                    'product_id' => $product_id,
                    'product_stock_history_id' => $history->id,
                    'user_id' => Auth::id(),
                    'work_name' => 'transfer_from_vendor'
                ]);
            } elseif ($note == 'Transfer to Vendor') {
                $receiver_product = VendorProduct::where('product_id', $product_id)->where('vendor_id', $receiver_id);

                if (!$receiver_product->exists()) {
                    $vendor_product = new VendorProduct;
                    $vendor_product->vendor_id = $receiver_id;
                    $vendor_product->product_id = $product_id;
                    // $vendor_product->discount = $discount;
                    $vendor_product->save();
                }

                $sender_stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id)->where('vendor_id', $vendor_id)->first();
                $sender_stock->qty -= $qty;
                $sender_stock->save();

                $receiver_stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id)->where('vendor_id', $receiver_id)->first();

                if ($receiver_stock) {
                    $receiver_stock->qty += $qty;
                    $receiver_stock->save();
                } else {
                    $receiver_stock = new ProductStock;
                    $receiver_stock->product_id = $product_id;
                    $receiver_stock->size_id = $size_id;
                    $receiver_stock->qty = $qty;
                    $receiver_stock->vendor_id = $receiver_id;

                    $receiver_stock->code               = $sender_stock->code;
                    $receiver_stock->production_cost    = $sender_stock->production_cost;
                    $receiver_stock->price              = $sender_stock->price;
                    $receiver_stock->discount_price     = $sender_stock->discount_price;
                    $receiver_stock->wholesale_price    = $sender_stock->wholesale_price;
                    $receiver_stock->save();
                }

                $receiver_history = new ProductStockHistory;
                $receiver_history->product_id = $product_id;
                $receiver_history->size_id = $size_id;
                $receiver_history->qty = $qty;
                $receiver_history->remarks = $remarks;
                $receiver_history->note = "Stockin";
                $receiver_history->vendor_id = $receiver_id;
                $receiver_history->save();

                // WorkTrackingEntry::create([
                //     'product_id' => $product_id,
                //     'product_stock_history_id' => $receiver_history->id,
                //     'user_id' => Auth::id(),
                //     'work_name' => 'transfer_to_vendor'
                // ]);
            }
        }

        Alert::toast('Transfer Request Approved Successfully!', 'success');
        return back();
    }
}
