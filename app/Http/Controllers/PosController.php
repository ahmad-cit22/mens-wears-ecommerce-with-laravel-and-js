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
use Cart;
use Session;
use DNS1D;
use DNS2D;
use Illuminate\Support\Facades\Hash;

class PosController extends Controller {
    public function index() {
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
        // return DNS1D::getBarcodeSVG('1005', 'C39');
        return view('admin.pos.create', compact('products', 'categories', 'brands', 'customers', 'districts', 'carts'));
    }

    public function wholesale_create(Request $request) {
        session(['wholesale_price' => 1]);
        $products = ProductStock::orderBy('id', 'DESC')->get();
        $categories = Category::orderBy('title', 'ASC')->get();
        $brands = Brand::orderBy('title', 'ASC')->get();
        $customers = User::where('type', 2)->orderBy('name', 'ASC')->get();
        $districts = District::orderBy('name', 'ASC')->get();
        $carts = Cart::content();
        return view('admin.pos.create', compact('products', 'categories', 'brands', 'customers', 'districts', 'carts'));
    }

    public function generateUniqueCode() {

        // $characters = '0123456789';
        // $charactersNumber = strlen($characters);
        // $codeLength = 6;

        // $code = '';

        // while (strlen($code) < 6) {
        //     $position = rand(0, $charactersNumber - 1);
        //     $character = $characters[$position];
        //     $code = $code.$character;
        // }
        // $code = date('y').'-'.$code;

        // if (Order::where('code', $code)->exists()) {
        //     $this->generateUniqueCode();
        // }

        $order = Order::orderBy('id', 'DESC')->first();
        if (!is_null($order)) {
            $code = $order->code + 1;
        } else {
            $code = 17000;
        }

        return $code;
    }

    public function store(Request $request) {
        if (Cart::content()->count() <= 0) {
            return back()->with('errMsg', 'Please select products correctly!');
        }

        $order = new Order;

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

            $order->code = $this->generateUniqueCode();

            $discount = 0;
            if (Session::has('coupon_discount')) {
                $discount = Session::get('coupon_discount');
                $order->discount_amount = $discount;
            }

            $order->price = Cart::subtotal() - $discount;

            $order->shipping_address = $request->shipping_address;
            $order->district_id = $request->district_id;
            $order->area_id = $request->area_id;
            $order->courier_name = $request->courier_name;

            if ($request->has('remove_shipping_charge')) {
                $order->delivery_charge = 0;
            } else {
                $order->delivery_charge = $request->shipping_charge;
            }

            if ($request->advanced_charge != 0) {
                $order->advance = $request->advanced_charge;
            }

            if (Session::has('wholesale_price')) {
                $order->source = 'Wholesale';
            } else {
                $order->source = 'Offline';
            }

            $order->is_final = 1;
            $order->note = $request->note;

            $order->save();

            // Calculate discoutn percentage

            $percentage = ($discount / Cart::subtotal()) * 100;

            foreach ($carts as $cart) {

                $order_product = new OrderProduct;

                $order_product->order_id = $order->id;
                $order_product->product_id = $cart->id;
                $order_product->size_id = $cart->options->size_id;
                $order_product->price = round($cart->price - ($cart->price * ($percentage / 100)));
                $order_product->production_cost = $cart->options->production_cost;
                $order_product->qty = $cart->qty;
                $order_product->save();

                $stock = ProductStock::where('product_id', $order_product->product_id)->where('size_id', $order_product->size_id)->first();
                $stock->qty -= $order_product->qty;
                $stock->save();

                Cart::remove($cart->rowId);
            }

            // if ($request->email != '') {
            //     Mail::send(new OrderMail($order));
            // }


            Session::forget('coupon_discount');
            Session::forget('wholesale_price');
            Alert::toast('One Sell added', 'success');
            return redirect()->route('pos.create');
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
