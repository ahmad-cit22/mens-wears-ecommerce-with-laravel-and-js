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
        if (auth()->user()->can('report.owners')) {
            $vendors = Vendor::with('user', 'transactions')->get();
            $users = User::where('type', 1)->orderBy('name', 'ASC')->get();

            return view('admin.vendors.index', compact('vendors', 'users'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

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
        if (auth()->user()->can('report.owners')) {
            $validatedData = $request->validate([
                'name' => 'required',
                'user_id' => 'required|not_in:0',
                'invested_amount' => 'required',
                'profit_percentage' => 'required',
            ]);

            // create new vendor
            $vendor = new Vendor;
            $vendor->name = $request->name;
            $vendor->user_id = $request->user_id;
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
        if (auth()->user()->can('report.owners')) {
            $vendor = Vendor::find($id);
            if ($vendor) {
                $validatedData = $request->validate([
                    'name' => 'required',
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
        if (auth()->user()->can('report.owners')) {
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
        if (auth()->user()->can('report.owners')) {
            $vendors = Vendor::with('user', 'transactions')->get();
            $products = Product::orderBy('id', 'DESC')->get();

            return view('admin.vendors.transfer-products', compact('vendors', 'products'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function transfer_products_store(Request $request) {
        // return $request->all();
        if (auth()->user()->can('report.owners')) {

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
                $vendor_product = VendorProduct::where('product_id', $product_id);

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
}
