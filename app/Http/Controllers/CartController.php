<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\Wallet;
use App\Models\WalletEntry;
use App\Models\District;
use Illuminate\Support\Str;
use Cart;
use Auth;
use Alert;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Session;

class CartController extends Controller {
    public function index() {
        $carts = Cart::content();
        return view('pages.cart', compact('carts'));
    }

    public function add_cart(Request $request) {
        $product_id = $request->product_id;
        $size_id = $request->size_id;
        $type = $request->type;
        $qty = $request->qty;
        $product = Product::find($product_id);
        if (!is_null($product)) {
            $price = 0;
            $size = '';
            if ($product->type == 'single') {
                $production_cost = $product->variation->production_cost;
                if ($product->is_offer == 1) {
                    $price = $product->variation->discount_price;
                } else {
                    $price = $product->variation->price;
                }
            } else {
                $stock = ProductStock::where('product_id', $product->id)->where('size_id', $size_id)->first();
                $size = $stock->size->title;
                $production_cost = $stock->production_cost;
                if ($product->is_offer == 1) {
                    $price = $stock->discount_price;
                } else {
                    $price = $stock->price;
                }
            }
            Cart::add([
                'id' => $product->id,
                'qty' => $qty,
                'price' => $price,
                'name' => $product->title,
                'weight' => 500,
                'options' => [
                    'production_cost' => $production_cost,
                    'image' => $product->image,
                    'size_id' => $size_id,
                    'size_name' => $size,
                ],
            ]);
        }

        $cart_sidebar = $this->generate_cart();

        return ['total_count' => Cart::count(), 'total_amount' => env('CURRENCY') . Cart::subtotal(), 'cart_sidebar' => $cart_sidebar];
    }

    public function generate_cart() {
        $carts = Cart::content();
        $total = 0;
        $cart_sidebar = '';
        foreach ($carts as $cart) {

            $total += $cart->price * $cart->qty;

            $cart_sidebar .= '<li class="single-product-cart">
                            <div class="cart-img">
                                <a href="' . route('single.product', [$cart->id, Str::slug($cart->name)]) . '"><img src="' . asset('images/product/' . $cart->options->image) . '" alt=""></a>
                            </div>
                            <div class="cart-title">
                                <h4><a href="' . route('single.product', [$cart->id, Str::slug($cart->name)]) . '">' . $cart->name . ($cart->options->size_name == '' ? '' : (' - ' . $cart->options->size_name)) . '</a></h4>
                                <span> ' . $cart->qty . ' × ' . env('CURRENCY') . $cart->price . ' </span>
                            </div>
                            <div class="cart-delete">
                                <form action="' . route('cart.remove') . '" method="POST">' .
                csrf_field()
                . '<input type="hidden" name="rowId" value="' . $cart->rowId . '">
                                    <button type="submit" style="background-color: transparent;border: none;"><i class="dlicon ui-1_simple-remove"></i></button>
                                </form>
                            </div>
                        </li>';
        }
        return $cart_sidebar;
    }

    public function show_cart() {
        $carts = Cart::content();
        return view('shopping-cart', compact('carts'));
        //dd($carts);
    }

    public function update_cart(Request $request) {
        Cart::update($request->rowId, $request->qty);
        return back();
    }

    public function remove_cart(Request $request) {
        Cart::remove($request->rowId);
        return back();
    }



    public function apply_coupon(Request $request) {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
        ]);

        $code = $request->code;

        $coupon = Coupon::where('code', $code)->orderBy('id', 'DESC')->first();
        if (!is_null($coupon)) {
            $valid_to = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->valid_to . ' 23:59:59');
            if ($valid_to->isPast()) {
                session()->flash('invalid', 'Invalid Coupon');
                return back();
            } else {
                if (Cart::subtotal() >= $coupon->purchase_amount) {
                    $discount = 0;
                    if ($coupon->discount == NULL) {
                        $discount = $coupon->amount;
                    }
                    if ($coupon->amount == NULL) {
                        $discount = ($coupon->discount / 100) * Cart::subtotal();
                    }
                    Session::forget('coupon_discount');
                    if ($discount > Cart::subtotal()) {
                        session(['coupon_discount' => Cart::subtotal()]);
                    } else {
                        session(['coupon_discount' => $discount]);
                    }
                    if ($coupon->single_use == 1) {
                        session(['coupon_single_use' => $coupon->single_use]);
                    }
                    session()->flash('success', 'Coupon Applied');
                    return back();
                } else {
                    session()->flash('invalid', 'This coupon is applicable for purchase amount greater than ' . $coupon->purchase_amount);
                    return back();
                }
            }
        } else {
            Session::forget('coupon_discount');
            session()->flash('invalid', 'Invalid Coupon');
            return back();
        }
    }

    public function remove_coupon() {
        Session::forget('coupon_discount');
        return back();
    }

    public function checkout() {
        $carts = Cart::content();
        if (Auth::user() != null) {
            $customer = User::find(Auth::user()->id);

            if ($customer->is_active) {
                if (count($carts) > 0) {
                    $districts = District::orderBy('name', 'ASC')->get();
                    return view('pages.checkout', compact('carts', 'districts'));
                } else {
                    return redirect()->route('products');
                }
            } else {
                Alert::toast('Sorry! Your customer status is deactivated right now. Kindly contact with us to get activated.', 'error');
                return redirect()->route('contact');
            }
        } else {
            if (count($carts) > 0) {
                $districts = District::orderBy('name', 'ASC')->get();
                return view('pages.checkout', compact('carts', 'districts'));
            } else {
                return redirect()->route('products');
            }
        }
    }
}
