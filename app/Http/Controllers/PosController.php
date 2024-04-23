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
use App\Models\WorkTrackingEntry;
use App\Models\ProductStockHistory;

use Illuminate\Http\Request;
use Auth;
use Alert;
use Mail;
use App\Mail\OrderMail;
use App\Models\CourierName;
use App\Models\FacebookOrder;
use Gloudemans\Shoppingcart\Facades\Cart;
use Session;
use DNS1D;
use DNS2D;
use Illuminate\Support\Facades\Hash;

class PosController extends Controller {
    public function index() {
    }

    public function create(Request $request, $id) {
        if (auth()->user()->can('pos.create')) {
            $fos_order = null;

            $carts = Cart::content();
            foreach ($carts as $cart) {
                Cart::remove($cart->rowId);
            }

            if (FacebookOrder::where('id', $id)->exists()) {
                $fos_order = FacebookOrder::with('order_product', 'order_product.product')->where('id', $id)->get()->first();

                foreach ($fos_order->order_product as $product) {
                    Cart::add([
                        'id' => $product->product->id,
                        'qty' => 1,
                        'price' => $product->stock()->price,
                        'name' => $product->product->title,
                        'weight' => 500,
                        'options' => [
                            'production_cost' => $product->stock()->production_cost,
                            'image' => $product->product->image,
                            'size_id' => $product->stock()->size_id,
                            'size_name' => optional($product->stock()->size)->title,
                        ],
                    ]);
                }
            }
            $carts = Cart::content();

            $couriers = CourierName::all();
            $products = ProductStock::orderBy('id', 'DESC')->with('product', 'size')->get();
            $categories = Category::orderBy('title', 'ASC')->get();
            $brands = Brand::orderBy('title', 'ASC')->get();
            $customers = User::where('type', 2)->orderBy('name', 'ASC')->get();
            $districts = District::orderBy('name', 'ASC')->get();

            // return DNS1D::getBarcodeSVG('1005', 'C39');
            return view('admin.pos.create', compact('products', 'categories', 'brands', 'customers', 'districts', 'carts', 'fos_order', 'couriers'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function wholesale_create(Request $request, $id) {

        $fos_order = null;
        if (auth()->user()->can('wholesale.create')) {
            $carts = Cart::content();
            foreach ($carts as $cart) {
                Cart::remove($cart->rowId);
            }

            if (FacebookOrder::where('id', $id)->exists()) {
                $fos_order = FacebookOrder::with('order_product', 'order_product.product')->where('id', $id)->get()->first();

                foreach ($fos_order->order_product as $product) {
                    Cart::add([
                        'id' => $product->product->id,
                        'qty' => 1,
                        'price' => $product->stock()->wholesale_price,
                        'name' => $product->product->title,
                        'weight' => 500,
                        'options' => [
                            'production_cost' => $product->stock()->production_cost,
                            'image' => $product->product->image,
                            'size_id' => $product->stock()->size_id,
                            'size_name' => optional($product->stock()->size)->title,
                        ],
                    ]);
                }
            }
            $carts = Cart::content();
            $couriers = CourierName::all();
            $products = ProductStock::orderBy('id', 'DESC')->with('product', 'size')->get();
            $categories = Category::orderBy('title', 'ASC')->get();
            $brands = Brand::orderBy('title', 'ASC')->get();
            $customers = User::where('type', 2)->orderBy('name', 'ASC')->get();
            $districts = District::orderBy('name', 'ASC')->get();

            return view('admin.pos.create-wholesale', compact('products', 'categories', 'brands', 'customers', 'districts', 'carts', 'fos_order', 'couriers'));
        } else {
            abort(403, 'Unauthorized action.');
        }
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
        } elseif ($request->courier_id == 0) {
            return back()->with('errMsg', 'Please select a valid courier name!');
        }

        $order = new Order;

        if ($request->customer_id == 0) {
            if (!User::where('phone', $request->phone)->exists()) {
                if ($request->name == null || $request->phone == null) {
                    return back()->with('errMsg', 'You must add customer name & phone number!');
                }

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
            $member_discount_rate = $request->member_discount_rate;
            $member_discount_amount = $request->member_discount_amount;
            $redeem_points_amount = $request->redeem_points_amount;

            $order->price = Cart::subtotal() - $discount - $member_discount_amount - $redeem_points_amount;

            $order->points_redeemed = $redeem_points_amount;
            $user->member->current_points -= $redeem_points_amount;
            $user->member->current_points += round(Cart::subtotal() * ($user->member->card->point_percentage / 100));
            //test
            $user->member->save();

            if ($member_discount_rate) {
                $order->discount_rate = $member_discount_rate;
            }
            $order->membership_discount = $member_discount_amount;

            $order->shipping_address = $request->shipping_address;
            $order->district_id = $request->district_id;
            $order->area_id = $request->area_id;
            $order->courier_name = CourierName::find($request->courier_id)->name;

            if ($request->has('remove_shipping_charge')) {
                $order->delivery_charge = 0;
            } else {
                $order->delivery_charge = $request->shipping_charge;
            }

            if ($request->advanced_charge != 0) {
                $order->advance = $request->advanced_charge;
            }

            $order->source = 'Offline';

            $order->order_status_id = 2;
            $order->is_final = 1;
            $order->note = $request->note;

            $order->save();

            // Calculate discount percentage

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

                $history = new ProductStockHistory;
                $history->product_id = $order_product->product_id;
                $history->size_id = $order_product->size_id;
                $history->qty = $order_product->qty;
                $history->remarks = 'Order Code - ' . $order->code;
                $history->note = "Sell (Offline)";

                $history->save();

                Cart::remove($cart->rowId);
            }

            // if ($request->email != '') {
            //     Mail::send(new OrderMail($order));
            // }

            WorkTrackingEntry::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'work_name' => 'create_order'
            ]);

            Alert::toast('One Sell Added', 'success');

            Session::forget('coupon_discount');
            return redirect()->route('pos.create', 'none');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function wholesale_store(Request $request) {
        if (Cart::content()->count() <= 0) {
            return back()->with('errMsg', 'Please select products correctly!');
        } elseif ($request->courier_id == 0) {
            return back()->with('errMsg', 'Please select a valid courier name!');
        }

        $order = new Order;

        if ($request->customer_id == 0) {
            if (!User::where('phone', $request->phone)->exists()) {
                if ($request->name == null || $request->phone == null) {
                    return back()->with('errMsg', 'You must add customer name & phone number!');
                }

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
            $order->courier_name = CourierName::find($request->courier_id)->name;

            if ($request->has('remove_shipping_charge')) {
                $order->delivery_charge = 0;
            } else {
                $order->delivery_charge = $request->shipping_charge;
            }

            if ($request->advanced_charge != 0) {
                $order->advance = $request->advanced_charge;
            }

            $order->source = 'Wholesale';

            $order->order_status_id = 2;
            $order->is_final = 1;
            $order->note = $request->note;

            $order->save();

            // Calculate discount percentage

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

                $history = new ProductStockHistory;
                $history->product_id = $order_product->product_id;
                $history->size_id = $order_product->size_id;
                $history->qty = $order_product->qty;
                $history->remarks = 'Order Code - ' . $order->code;
                $history->note = "Sell (Wholesale)";

                $history->save();

                Cart::remove($cart->rowId);
            }

            // if ($request->email != '') {
            //     Mail::send(new OrderMail($order));
            // }

            WorkTrackingEntry::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'work_name' => 'create_order'
            ]);

            Alert::toast('One Sell Added', 'success');

            Session::forget('coupon_discount');
            return redirect()->route('pos.wholesale.create', 'none');
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
                'price' => $stock->price,
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

    public function add_cart_wholesale(Request $request) {
        $stock_id = $request->stock_id;
        $stock = ProductStock::find($stock_id);
        if (!is_null($stock)) {
            $product = $stock->product;
            Cart::add([
                'id' => $product->id,
                'qty' => 1,
                'price' => $stock->wholesale_price,
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
                'price' => $stock->price,
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

    public function barcode_add_cart_wholesale(Request $request) {
        $barcode = $request->barcode;
        $stock_id = $barcode - 1000;
        $stock = ProductStock::find($stock_id);
        if (!is_null($stock)) {
            $product = $stock->product;
            Cart::add([
                'id' => $product->id,
                'qty' => 1,
                'price' => $stock->wholesale_price,
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

    public function check_membership(Request $request) {
        $customer = User::with('member', 'member.card')->find($request->customer_id);
        if ($customer->member) {
            $member = $customer->member;
            $card = $customer->member->card;
            $card_number = $customer->member->card_number;
            return response()->json(['status' => 'success', 'card' => $card, 'card_number' => $card_number, 'member' => $member]);
        } else {
            return response()->json(['card' => 'Not Found']);
        }
    }
}
