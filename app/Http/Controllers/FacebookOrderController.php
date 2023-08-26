<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Size;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\District;
use App\Models\Area;

use Illuminate\Http\Request;
use Auth;
use Alert;
use Mail;
use App\Mail\OrderMail;
use App\Models\CourierName;
use App\Models\FacebookOrder;
use App\Models\FacebookOrderProduct;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Session;
use DNS1D;
use DNS2D;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class FacebookOrderController extends Controller {
    public function index(Request $request) {
        $date_from = '';
        $date_to = '';

        if (auth()->user()->can('order.index')) {
            $orders = FacebookOrder::orderBy('id', 'DESC')->get();

            return view('admin.order.order_sheet.index', compact('orders', 'date_from', 'date_to'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    // public function search(Request $request) {
    //     $date_from = '';
    //     $date_to = '';

    //     if (auth()->user()->can('order.index')) {
    //         if (!empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
    //             $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
    //             $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
    //             $order_status_id = $request->order_status_id;

    //             $orders = Order::where('order_status_id', $order_status_id)
    //                 ->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

    //             $date_from = $request->date_from;
    //             $date_to = $request->date_to;
    //         }
    //         if ((!empty($request->order_status_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->date_from) && !empty($request->date_to))) {

    //             $order_status_id = $request->order_status_id;

    //             $orders = Order::where('order_status_id', $order_status_id)->orderBy('id', 'DESC')->get();
    //         }
    //         if (empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
    //             $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
    //             $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
    //             $order_status_id = $request->order_status_id;
    //             $orders = Order::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

    //             $date_from = $request->date_from;
    //             $date_to = $request->date_to;
    //         }
    //         if (empty($request->order_status_id) && (empty($request->date_from) || empty($request->date_to))) {
    //             $orders = Order::orderBy('id', 'DESC')->get();
    //         }

    //         // 2nd step filter
    //         $district_id = $request->district_id;

    //         if (!empty($request->district_id) && !empty($request->area_id)) {

    //             $orders = $orders->where('district_id', $request->district_id)->where('area_id', $request->area_id);
    //         }
    //         if (!empty($request->district_id) && empty($request->area_id)) {
    //             $orders = $orders->where('district_id', $request->district_id);
    //         }

    //         if ($request->ajax()) {
    //             return Datatables::of($orders)
    //                 // ->addIndexColumn()
    //                 ->addColumn('code', function ($row) {

    //                     $code = '<a href="' . route('order.edit', $row->id) . '">' . $row->code . '</a>';

    //                     return $code;
    //                 })
    //                 ->addColumn('status', function ($row) {

    //                     $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';

    //                     return $data;
    //                 })
    //                 ->addColumn('date', function ($row) {

    //                     $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

    //                     return $data;
    //                 })
    //                 ->addColumn('action', function ($row) {

    //                     $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
    //                       <a href="' . route('order.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';

    //                     return $btn;
    //                 })
    //                 ->rawColumns(['code', 'status', 'date', 'action'])
    //                 ->make(true);
    //         }
    //         return view('admin.order.index', compact('orders', 'date_from', 'date_to'));
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }
    // /**

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('order.edit')) {
            $order = FacebookOrder::find($id);

            if (!is_null($order)) {
                return view('admin.order.order_sheet.edit', compact('order'));
            } else {
                Alert::toast('Order Not Found', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Order $order) {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('order.delete')) {
            $order = FacebookOrder::find($id);
            if (!is_null($order)) {
                foreach ($order->order_product as $product) {
                    $product->delete();
                }
                $order->delete();
                Alert::toast('Order deleted successfully!', 'success');
                return redirect()->route('fos.index');
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    // public function change_status(Request $request, $id) {
    //     if (auth()->user()->can('order.edit')) {
    //         $order = Order::find($id);
    //         if (!is_null($order)) {
    //             if ($request->order_status_id == 5) {
    //                 $validatedData = $request->validate(
    //                     [
    //                         'note' => 'required',
    //                     ],
    //                     [
    //                         'note.required' => 'Please Provide a Cancellation Reason',
    //                     ]
    //                 );

    //                 if ($order->is_final == 1) {
    //                     $order_products = $order->order_product;
    //                     foreach ($order_products as $product) {
    //                         $stock = ProductStock::where('product_id', $product->product_id)->where('size_id', $product->size_id)->first();
    //                         $stock->qty += $product->qty;
    //                         $stock->save();
    //                     }
    //                     $order->is_final = 0;
    //                 }
    //             }

    //             $order->order_status_id = $request->order_status_id;
    //             $order->note = $request->note;
    //             $order->save();
    //             // $msg = 'Dear Sir/Madam, Your order('. $order->code .') status has been updated to '.$order->status->title.'. Thanks for shopping with us.';
    //             // $send_sms = $order->send_sms($msg, $order->phone);

    //             Alert::toast('Status Updated!', 'success');
    //             return back();
    //         } else {
    //             Alert::toast('Something went wrong !', 'error');
    //             return back();
    //         }
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }

    public function create(Request $request) {
        if (Session::has('wholesale_price')) {
            Session::forget('wholesale_price');
        }

        $products = ProductStock::orderBy('id', 'DESC')->get();
        $categories = Category::orderBy('title', 'ASC')->get();
        $brands = Brand::orderBy('title', 'ASC')->get();
        $customers = User::where('type', 2)->orderBy('name', 'ASC')->get();
        $districts = District::orderBy('name', 'ASC')->get();
        $carts = Cart::content();
        $couriers = CourierName::all();
        // return DNS1D::getBarcodeSVG('1005', 'C39');
        return view('admin.fos.create', compact('products', 'categories', 'brands', 'couriers', 'customers', 'districts', 'carts'));
    }

    public function wholesale_create(Request $request) {
        session(['wholesale_price' => 1]);
        $products = ProductStock::orderBy('id', 'DESC')->get();
        $categories = Category::orderBy('title', 'ASC')->get();
        $brands = Brand::orderBy('title', 'ASC')->get();
        $customers = User::where('type', 2)->orderBy('name', 'ASC')->get();
        $districts = District::orderBy('name', 'ASC')->get();
        $couriers = CourierName::all();
        $carts = Cart::content();
        return view('admin.fos.create', compact('products', 'categories', 'brands', 'customers', 'couriers', 'districts', 'carts'));
    }

    // public function generateUniqueCode() {

    //     // $characters = '0123456789';
    //     // $charactersNumber = strlen($characters);
    //     // $codeLength = 6;

    //     // $code = '';

    //     // while (strlen($code) < 6) {
    //     //     $position = rand(0, $charactersNumber - 1);
    //     //     $character = $characters[$position];
    //     //     $code = $code.$character;
    //     // }
    //     // $code = date('y').'-'.$code;

    //     // if (Order::where('code', $code)->exists()) {
    //     //     $this->generateUniqueCode();
    //     // }

    //     $order = Order::orderBy('id', 'DESC')->first();
    //     if (!is_null($order)) {
    //         $code = $order->code + 1;
    //     } else {
    //         $code = 17000;
    //     }

    //     return $code;
    // }

    public function store(Request $request) {
        if (Cart::content()->count() <= 0) {
            return back()->with('errMsg', 'Please select products correctly!');
        }

        $order = new FacebookOrder;

        if ($request->customer_id == 0) {
            if (!User::where('phone', $request->phone)->exists()) {
                $user = new User;
                $user->name       = $request->name;
                $user->email      = $request->email;
                $user->phone      = $request->phone;
                $user->city       = $request->district_id;
                $user->address    = $request->shipping_address;
                $user->password   = Hash::make(12345678);
                $user->save();

                $order->customer_id = $user->id;
                $order->name = $user->name;
                $order->email = $user->email;
                $order->phone = $user->phone;
            } else {
                $user = User::where('phone', $request->phone)->first();
                $order->customer_id = $user->id;
                $order->name = $user->name;
                $order->email = $user->email;
                $order->phone = $user->phone;
            }
        } else {
            $user = User::find($request->customer_id);
            $order->customer_id = $user->id;
            $order->name = $user->name;
            $order->email = $user->email;
            $order->phone = $user->phone;
        }

        if (auth()->user()->can('order.create')) {
            $carts = Cart::content();

            // return Cart::content();

            $discount = 0;
            if (Session::has('coupon_discount')) {
                $discount = Session::get('coupon_discount');
                $order->discount_amount = $discount;
            }

            $order->price = Cart::subtotal() - $discount;

            $order->shipping_address = $request->shipping_address;
            $order->courier_id = $request->courier_id;
            $order->source = $request->source;
            $order->note = $request->note;

            $order->save();

            // Calculate discoutn percentage

            $percentage = ($discount / Cart::subtotal()) * 100;

            foreach ($carts as $cart) {

                $order_product = new FacebookOrderProduct;

                $order_product->order_id = $order->id;
                $order_product->product_id = $cart->id;
                $order_product->size_id = $cart->options->size_id;
                $order_product->price = round($cart->price - ($cart->price * ($percentage / 100)));
                $order_product->production_cost = $cart->options->production_cost;
                $order_product->qty = $cart->qty;
                $order_product->save();

                Cart::remove($cart->rowId);
            }

            Session::forget('coupon_discount');
            Session::forget('wholesale_price');
            Alert::toast('Order added successfully!', 'success');
            return redirect()->route('fos.create');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function take_advance(Request $request, $id) {
        if (auth()->user()->can('order.edit')) {
            $order = FacebookOrder::find($id);
            if (!is_null($order)) {
                $validatedData = $request->validate([
                    'amount' => 'required|numeric',
                ]);
                
                $order->advance = $request->amount;
                $order->save();
                Alert::toast('Advance Amount Received', 'success');
                return back();
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function product_filter(Request $request) {
        $product_name = $request->product_name;
        $category_id = $request->category_id;
        $brand_id = $request->brand_id;

        $product_filtered = '';

        if ($product_name != '') {
            $products = Product::where('title', 'LIKE', '%' . $product_name . '%')->orWhere('description', 'LIKE', '%' . $product_name . '%');
        } else {
            $products = Product::orderBy('id', 'DESC');
        }

        if ($category_id != 'all' && $brand_id != 'all') {
            $products = $products->where('category_id', $category_id)->where('brand_id', $brand_id)->get();
        }
        if ($category_id != 'all' && $brand_id == 'all') {
            $products = $products->where('category_id', $category_id)->get();
        }
        if ($category_id == 'all' && $brand_id != 'all') {
            $products = $products->where('brand_id', $brand_id)->get();
        }

        $products = $products->pluck('id')->toArray();
        $products = ProductStock::whereIn('product_id', $products)->where('qty', '>=', 0)->orderBy('id', 'DESC')->get();

        if (count($products) > 0) {
            foreach ($products as $product) {
                $product_filtered .= view('admin.pos.partials.product', compact('product'));
            }
        } else {
            $product_filtered .= '<div calss="col-md-12 productCard"><h5 class="ml-4" style="text-align: center;">Product Not Found!</h5></div>';
        }

        return ['product_filtered' => $product_filtered];
    }

    public function add_cart(Request $request) {
        $stock_id = $request->stock_id;
        $stock = ProductStock::find($stock_id);
        if (!is_null($stock)) {
            $product = $stock->product;
            Cart::add([
                'id' => $product->id,
                'qty' => 1,
                'price' => Session::has('wholesale_price') ? $stock->wholesale_price : $stock->price,
                'name' => $product->title,
                'weight' => 500,
                'options' => [
                    'production_cost' => $stock->production_cost,
                    'image' => $product->image,
                    'size_id' => $stock->size_id,
                    'size_name' => optional($stock->size)->title,
                ],
            ]);
        }

        $cart_table = $this->generate_cart();

        return ['total_count' => Cart::count(), 'total_amount' => Cart::subtotal(), 'cart_table' => $cart_table];
    }

    public function barcode_add_cart(Request $request) {
        $barcode = $request->barcode;
        $stock_id = $barcode - 1000;
        $stock = ProductStock::find($stock_id);
        if (!is_null($stock)) {
            $product = $stock->product;
            Cart::add([
                'id' => $product->id,
                'qty' => 1,
                'price' => Session::has('wholesale_price') ? $stock->wholesale_price : $stock->price,
                'name' => $product->title,
                'weight' => 500,
                'options' => [
                    'production_cost' => $stock->production_cost,
                    'image' => $product->image,
                    'size_id' => $stock->size_id,
                    'size_name' => optional($stock->size)->title,
                ],
            ]);
        }

        $cart_table = $this->generate_cart();

        return ['total_count' => Cart::count(), 'total_amount' => Cart::subtotal(), 'cart_table' => $cart_table];
    }

    public function generate_cart() {
        $carts = Cart::content();
        $total = 0;
        $cart_table = '';
        foreach ($carts as $cart) {

            $total += $cart->price * $cart->qty;

            $cart_table .= view('admin.pos.partials.cart-item', compact('cart'));
        }
        return $cart_table;
    }

    public function update_cart(Request $request) {
        Cart::update($request->rowId, ['price' => $request->price]);

        $cart_table = $this->generate_cart();

        return ['total_count' => Cart::count(), 'total_amount' => Cart::subtotal(), 'cart_table' => $cart_table];
    }

    public function remove_cart(Request $request) {
        Cart::remove($request->rowId);
        $cart_table = $this->generate_cart();

        return ['total_count' => Cart::count(), 'total_amount' => Cart::subtotal(), 'cart_table' => $cart_table];
    }

    public function apply_discount(Request $request) {
        if (Session::has('coupon_discount')) {
            Session::forget('coupon_discount');
        }
        $amount = $request->amount;
        session(['coupon_discount' => $amount]);
        return $amount;
    }
}
