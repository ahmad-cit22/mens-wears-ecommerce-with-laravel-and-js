<?php

namespace App\Http\Controllers;

use App\Models\OrderReturn;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\WorkTrackingEntry;
use App\Models\ProductStockHistory;
use Illuminate\Http\Request;

use Auth;
use PDF;
use Session;
use Alert;
use App\Models\OrderProduct;
use Carbon\Carbon;
use DataTables;

class OrderReturnController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('order.return')) {
            $sell_returns = OrderReturn::orderBy('id', 'DESC')->get();
            return view('admin.order.sell.return.index', compact('sell_returns'));
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

    public function generateUniqueCode() {

        $characters = '0123456789';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }
        $code = date('y') . '-' . $code;

        if (OrderReturn::where('code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $change = 0;
        $i = 0;
        $code = $this->generateUniqueCode();
        $returned_price = 0;

        for ($i = 0; $i < count($request->qty); $i++) {
            if ($request->qty[$i] != NULL && $request->qty[$i] > 0) {
                $change += 1;

                // save the returned item
                $sell_return = new OrderReturn;
                $sell_return->order_id = $request->order_id;
                $sell_return->order_code = $request->order_code;
                $sell_return->code = $code;
                $sell_return->product_id = $request->product_id[$i];
                $sell_return->size_id = $request->size_id[$i];
                $sell_return->qty = $request->qty[$i];
                $sell_return->price = $request->price[$i];
                $sell_return->save();

                $returned_price += $request->qty[$i] * $request->price[$i];

                // adjust the order products
                $order_product = OrderProduct::where('order_id', $request->order_id)->where('product_id', $request->product_id[$i])->where('size_id', $request->size_id[$i])->first();
                $order_product->qty = $order_product->qty - $request->qty[$i];
                $order_product->return_qty = $order_product->return_qty != null ? $order_product->return_qty + $request->qty[$i] : $request->qty[$i];
                $order_product->save();

                // adjust the stock value
                $stock = ProductStock::where('product_id', $request->product_id[$i])->where('size_id', $request->size_id[$i])->first();
                $stock->qty += $request->qty[$i];
                $stock->save();

                $history = new ProductStockHistory;
                $history->product_id = $request->product_id[$i];
                $history->size_id = $request->size_id[$i];
                $history->qty = $request->qty[$i];
                $history->remarks = 'Order Code - ' . $request->order_code;
                $history->note = "Order Return";
                $history->save();

                WorkTrackingEntry::create([
                    'order_id' => $request->order_id,
                    'product_stock_history_id' => $history->id,
                    'user_id' => Auth::id(),
                    'work_name' => 'order_return'
                ]);
            }
        }

        if ($change > 0) {
            $order = Order::find($request->order_id);
            $order->price = $order->price - $returned_price;

            if ($order->price > 0) {
                $order->is_return = 2;
            } else {
                $order->is_return = 1;
                $order->order_status_id = 4;
            }
            $order->save();

            Alert::toast('Return invoice created.', 'success');
            return redirect()->route('order.edit', $order->id);
        } else {
            Alert::toast('Incorrect inputs, order return failed.', 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderReturn  $orderReturn
     * @return \Illuminate\Http\Response
     */
    public function show(OrderReturn $orderReturn) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderReturn  $orderReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderReturn $orderReturn) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderReturn  $orderReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderReturn $orderReturn) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderReturn  $orderReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderReturn $orderReturn) {
        //
    }
}


// <?php

// namespace App\Http\Controllers;

// use App\Models\OrderReturn;
// use App\Models\Order;
// use App\Models\ProductStock;
// use Illuminate\Http\Request;

// use Auth;
// use PDF;
// use Session;
// use Alert;
// use App\Models\OrderProduct;
// use Carbon\Carbon;
// use DataTables;

// class OrderReturnController extends Controller {
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index() {
//         if (auth()->user()->can('order.return')) {
//             $sell_returns = OrderReturn::orderBy('id', 'DESC')->get();
//             return view('admin.order.sell.return.index', compact('sell_returns'));
//         } else {
//             abort(403, 'Unauthorized action.');
//         }
//     }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function create() {
//         //
//     }

//     public function generateUniqueCode() {

//         $characters = '0123456789';
//         $charactersNumber = strlen($characters);
//         $codeLength = 6;

//         $code = '';

//         while (strlen($code) < 6) {
//             $position = rand(0, $charactersNumber - 1);
//             $character = $characters[$position];
//             $code = $code . $character;
//         }
//         $code = date('y') . '-' . $code;

//         if (OrderReturn::where('code', $code)->exists()) {
//             $this->generateUniqueCode();
//         }

//         return $code;
//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(Request $request) {
//         $change = 0;
//         $i = 0;
//         $code = $this->generateUniqueCode();
//         $returned_price = 0;

//         for ($i = 0; $i < count($request->qty); $i++) {
//             if ($request->qty[$i] != NULL && $request->qty[$i] > 0) {
//                 $change += 1;

//                 // save the returned item
//                 $sell_return = new OrderReturn;
//                 $sell_return->order_id = $request->order_id;
//                 $sell_return->order_code = $request->order_code;
//                 $sell_return->code = $code;
//                 $sell_return->product_id = $request->product_id[$i];
//                 $sell_return->size_id = $request->size_id[$i];
//                 $sell_return->qty = $request->qty[$i];
//                 $sell_return->price = $request->price[$i];
//                 $sell_return->save();

//                 $returned_price += $request->qty[$i] * $request->price[$i];

//                 // adjust the order products
//                 $order_product = OrderProduct::where('order_id', $request->order_id)->where('product_id', $request->product_id[$i])->where('size_id', $request->size_id[$i])->first();
//                 $order_product->qty = $order_product->qty - $request->qty[$i];
//                 $order_product->return_qty = $order_product->return_qty != null ? $order_product->return_qty + $request->qty[$i] : $request->qty[$i];
//                 $order_product->save();

//                 // adjust the stock value
//                 $stock = ProductStock::where('product_id', $request->product_id[$i])->where('size_id', $request->size_id[$i])->first();
//                 $stock->qty += $request->qty[$i];
//                 $stock->save();
//             }
//         }

//         $order = Order::find($request->order_id);
//         $discount = $order->discount_amount;
//         $order_products = $order->order_product;
//         $subtotal = 0;
//         $new_total = 0;

//         if ($discount > 0) {
//             foreach ($order_products as $key => $product) {
//                 $subtotal += $product->qty * $product->product->variation->price;
//             }

//             $percentage = ($discount / $subtotal) * 100;


//             foreach ($order_products as $key => $product) {
//                 $product->price = round($product->product->variation->price - ($product->product->variation->price * ($percentage / 100)));
//                 $product->save();

//                 $new_total += $product->price * $product->qty;
//             }
//         }

//         if ($change > 0) {
//             if ($new_total > 0) {
//                 $order->price = $new_total;
//             } else {
//                 $order->price = $order->price - $returned_price;
//             }

//             if ($order->price > 0) {
//                 $order->is_return = 2;
//             } else {
//                 $order->is_return = 1;
//             }
//             $order->save();

//             Alert::toast('Return invoice created.', 'success');
//             return redirect()->route('order.edit', $order->id);
//         } else {
//             Alert::toast('Incorrect inputs, order return failed.', 'error');
//             return back();
//         }
//     }

//     /**
//      * Display the specified resource.
//      *
//      * @param  \App\Models\OrderReturn  $orderReturn
//      * @return \Illuminate\Http\Response
//      */
//     public function show(OrderReturn $orderReturn) {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  \App\Models\OrderReturn  $orderReturn
//      * @return \Illuminate\Http\Response
//      */
//     public function edit(OrderReturn $orderReturn) {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \App\Models\OrderReturn  $orderReturn
//      * @return \Illuminate\Http\Response
//      */
//     public function update(Request $request, OrderReturn $orderReturn) {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  \App\Models\OrderReturn  $orderReturn
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy(OrderReturn $orderReturn) {
//         //
//     }
// }
