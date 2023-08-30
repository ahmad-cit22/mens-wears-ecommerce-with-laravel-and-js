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
use RealRashid\SweetAlert\Facades\Alert;
use Mail;
use App\Mail\OrderMail;
use App\Models\BkashNumber;
use App\Models\CourierName;
use App\Models\FacebookOrder;
use App\Models\FacebookOrderProduct;
use App\Models\FacebookOrderStatus;
use App\Models\OrderSpecialStatus;
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
        $order_status_id = '';
        $special_status_id = '';

        if (auth()->user()->can('order.index')) {
            $orders = FacebookOrder::orderBy('id', 'DESC')->get();

            return view('admin.order.order_sheet.index', compact('orders', 'date_from', 'date_to', 'order_status_id', 'special_status_id'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

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

    public function store(Request $request) {
        if (Cart::content()->count() <= 0) {
            return back()->with('errMsg', 'Please select products correctly!');
        } else if ($request->source == 0) {
            return back()->with('errMsg', 'Please select order source!');
        } else if ($request->courier_id == null) {
            return back()->with('errMsg', 'Please select courier name!');
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
                $order_product->price = $cart->price;
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

    public function search(Request $request) {
        $date_from = '';
        $date_to = '';

        if (auth()->user()->can('order.index')) {
            if (!empty($request->order_status_id) && !empty($request->special_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;
                $special_status_id = $request->special_status_id;

                $orders = FacebookOrder::where('order_status_id', $order_status_id)->where('special_status_id', $special_status_id)
                    ->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }

            if (!empty($request->order_status_id) && !empty($request->special_status_id) && (empty($request->date_from) || empty($request->date_to))) {
                $start_date = '';
                $end_date = '';
                $order_status_id = $request->order_status_id;
                $special_status_id = $request->special_status_id;

                $orders = FacebookOrder::where('order_status_id', $order_status_id)->where('special_status_id', $special_status_id)->orderBy('id', 'DESC')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }

            if ((!empty($request->order_status_id) && empty($request->special_status_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->special_status_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->special_status_id) && empty($request->date_from) && !empty($request->date_to))) {

                $order_status_id = $request->order_status_id;
                $special_status_id = $request->special_status_id;

                $orders = FacebookOrder::where('order_status_id', $order_status_id)->orderBy('id', 'DESC')->get();
            }

            if ((empty($request->order_status_id) && !empty($request->special_status_id) && empty($request->date_from) && empty($request->date_to)) || (empty($request->order_status_id) && !empty($request->special_status_id) && !empty($request->date_from) && empty($request->date_to)) || (empty($request->order_status_id) && !empty($request->special_status_id) && empty($request->date_from) && !empty($request->date_to))) {

                $order_status_id = $request->order_status_id;
                $special_status_id = $request->special_status_id;

                $orders = FacebookOrder::where('special_status_id', $special_status_id)->orderBy('id', 'DESC')->get();
            }

            if (empty($request->order_status_id) && empty($request->special_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;
                $special_status_id = $request->special_status_id;
                $orders = FacebookOrder::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if (empty($request->order_status_id) && empty($request->special_status_id) && (empty($request->date_from) || empty($request->date_to))) {
                $date_from = '';
                $date_to = '';
                $order_status_id = '';
                $special_status_id = '';

                $orders = FacebookOrder::orderBy('id', 'DESC')->get();
            }

            return view('admin.order.order_sheet.index', compact('orders', 'date_from', 'date_to', 'order_status_id', 'special_status_id'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    // /**

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('order.edit')) {
            $products = ProductStock::orderBy('id', 'DESC')->get();
            $order = FacebookOrder::find($id);
            $couriers = CourierName::all();
            $statuses = FacebookOrderStatus::where('is_active', 1)->get();
            $special_statuses = OrderSpecialStatus::where('is_active', 1)->get();
            $bkash_nums = BkashNumber::all();
            $sizes = Size::all();

            if (!is_null($order)) {
                return view('admin.order.order_sheet.edit', compact('order', 'products', 'sizes', 'couriers', 'statuses', 'special_statuses', 'bkash_nums'));
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
    public function order_info_update(Request $request, $id) {

        $request->validate([
            'name'  => 'required',
            'phone' => 'required',
            'shipping_address'  => 'required',
            'source'    => 'required|not_in:0',
            'order_status_id'    => 'required|not_in:0',
        ]);

        if (auth()->user()->can('order.edit')) {
            $order = FacebookOrder::find($id);
            if (!is_null($order)) {
                $order->code = $request->code;
                $order->name = $request->name;
                $order->email = $request->email;
                $order->phone = $request->phone;
                $order->whatsapp_num = $request->whatsapp_num;
                $order->shipping_address = $request->shipping_address;
                $order->bkash_num = $request->bkash_num;
                $order->bkash_business_id = $request->bkash_business_id;
                $order->bkash_amount = $request->bkash_amount;
                $order->source = $request->source;
                $order->courier_id = $request->courier_id;
                $order->order_status_id = $request->order_status_id;
                $order->special_status_id = $request->special_status_id;
                $order->note = $request->note;
                $order->remarks = $request->remarks;
                $order->advance = $request->advance;
                $order->discount_amount = $request->discount_amount;
                $order->save();

                Alert::toast('Order Info Updated!', 'success');
                return back();
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function order_products_update(Request $request, $id) {

        for ($i = 0; $i < count($request->qty) - 1; $i++) {

            if ($request->qty[$i] == null) {
                return back()->with('qtyError' . $i, 'Quantity is required!');
            }
        }

        // delete the old order products
        $old_order_products = FacebookOrderProduct::where('order_id', $id);
        $old_order_products->delete();
        $new_total = 0;

        $i = 0;

        for ($i = 0; $i < count($request->qty); $i++) {
            $product_stock = ProductStock::find($request->product[$i]);

            if ($request->qty[$i] > 0) {
                // $change += 1;

                // save the updated order products
                $order_product = new FacebookOrderProduct;
                $order_product->order_id = $id;
                $order_product->product_id = $product_stock->product->id;
                $order_product->size_id = $product_stock->size_id;
                $order_product->price = $product_stock->price;
                $order_product->production_cost = $product_stock->production_cost;
                $order_product->qty = $request->qty[$i];
                $order_product->save();

                $new_total += $order_product->qty * $order_product->price;
            }
        }

        $order = FacebookOrder::find($id);
        $order->price = $new_total;
        $order->save();

        Alert::toast('Order Products Updated.', 'success');
        return redirect()->route('fos.edit', $id);
    }


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
