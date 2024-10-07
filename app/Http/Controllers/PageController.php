<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Size;
use App\Models\Setting;
use App\Models\District;
use App\Models\Area;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Slider;
use App\Models\Trending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Alert;
use Illuminate\Support\Facades\Auth;
use Share;
use DB;
use Session;
use Str;
use Cart;
use Mail;
use App\Mail\OrderMail;
use App\Mail\OrderNotificationMail;
use App\Mail\ContactMail;

class PageController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $sliders = Slider::all();
        $page = Page::find(1);
        $featured_categories = Category::where('is_featured', 1)->where('parent_id', 0)->where('is_active', 1)->orderBy('position', 'ASC')->limit(5)->get();
        $featured_products = Product::where('is_featured', 1)->where('is_active', 1)->orderBy('priority_no_1', 'DESC')->orderBy('id', 'DESC')->limit(8)->get();
        $trending = Trending::find(1);
        DB::statement("SET SQL_MODE=''"); //this is the trick use it just before your query
        $top_sales = Product::query()
            ->where('is_active', 1)
            ->join('order_products', 'order_products.product_id', '=', 'products.id')
            ->selectRaw('products.*, SUM(order_products.qty) AS quantity_sold')
            ->groupBy(['products.id']) // should group by primary key
            ->orderByDesc('quantity_sold')
            ->orderBy('priority_no_1', 'DESC')
            ->take(8) // 8 best-selling products
            ->get();
        // dd($top_sales);
        return view('pages.index', compact('featured_categories', 'page', 'featured_products', 'top_sales', 'sliders', 'trending'));
    }

    public function products() {
        $products = Product::where('is_active', 1)->orderBy('priority_no_1', 'DESC')->orderBy('id', 'DESC')->paginate(15);
        $page = Page::find(2);
        $min_price = ProductStock::min('price');
        $max_price = ProductStock::max('price');
        return view('pages.products', compact('products', 'page', 'min_price', 'max_price'));
    }

    public function trending_products() {
        $page = Page::find(8);
        $products = Product::where('is_active', 1)->where('is_trending', 1)->orderBy('id', 'DESC')->get();
        return view('pages.trending-product', compact('products', 'page'));
    }

    public function offer_products() {
        $page = Page::find(9);
        $products = Product::where('is_active', 1)->where('is_offer', 1)->orderBy('priority_no_2', 'DESC')->orderBy('id', 'DESC')->paginate(16);
        return view('pages.offer-product', compact('products', 'page'));
    }

    public function hot_deals() {
        $page = Page::find(11);
        $products = Product::where('is_active', 1)->where('is_hot_deal', 1)->orderBy('priority_no_3', 'DESC')->orderBy('id', 'DESC')->paginate(12);
        return view('pages.hot-deals', compact('products', 'page'));
    }

    public function single_product($id, $slug) {
        $product = Product::find($id);
        if (!is_null($product)) {
            //dd(json_decode($product->choice_options, true));
            if (!$product->is_active) {
                session()->flash('error', 'This product is not available right now.');
                return back();
            }
            $similar_products = Product::where('is_active', 1)->where('category_id', $product->category_id)->inRandomOrder()->get()->take(10);
            $share = Share::page(route('single.product', [$product->id, Str::slug($product->title)]), $product->title)
                ->facebook()
                ->twitter()
                ->linkedin('Extra linkedin summary can be passed here')
                ->whatsapp()->getRawLinks();
            return view('pages.single-product', compact('product', 'similar_products', 'share'));
        } else {
            session()->flash('error', 'Page Not Found');
            return back();
        }
    }

    public function api_product_details(Request $request) {
        $product_id = $request->product_id;
        $product = Product::find($product_id);

        $product_details = '';

        $product_details .= '<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="quickview-slider-active">
                                    <a class="img-popup" href="' . asset('images/product/' . $product->image) . '"><img src="' . asset('images/product/' . $product->image) . '" alt="" style="width: 100%"></a>
                                </div>
                                <!-- Thumbnail Large Image End -->
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="product-details-content">

                                    <h2 class="uppercase">' . $product->title . '</h2>
                                    <h3>';
        if ($product->type == 'single') {
            if ($product->is_offer == 1) {
                $product_details .= env('CURRENCY') . $product->variation->discount_price;
            } else {
                $product_details .= env('CURRENCY') . $product->variation->price;
            }
        } else {
            if ($product->is_offer == 1) {
                $product_details .= env('CURRENCY') . $product->variations_website->where('discount_price', $product->variations_website->min('discount_price'))->first()->discount_price;
            } else {
                $product_details .= env('CURRENCY') . $product->variations_website->where('price', $product->variations_website->min('price'))->first()->price;
            }
        }
        $product_details .= '</h3>
                                    <div class="product-details-peragraph">
                                        ' . Str::limit(strip_tags($product->description), 110, $end = ' ....') . '
                                    </div>
                                    <div class="product-details-action-wrap">';
        if ($product->type == 'variation') {
            $product_details .= '<div class="size_variation">
                                            <label>Size: </label>
                                            <div style="width: 100%;">';
            foreach ($product->variations_website as $variation) {

                $product_details .= view('pages.partials.api-variation', compact('variation'));
            }

            $product_details .= '</div>
                                        </div>';
        }

        $product_details .= '<div class="cart-options">
                                            <div class="product-details-quality">
                                                <div class="cart-plus-minus">
                                                    <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1" id="qty">
                                                </div>
                                            </div>
                                            <div class="product-details-cart">
                                                <a title="Add to cart" onclick="addToCart(' . $product->id . ',\'' . $product->type . '\')">Add to cart</a>
                                            </div>
                                            <div class="product-details-wishlist">
                                                <a title="Add to wishlist" href="javascript:void(0)" onclick="addToWishlist( ' . $product->id . ' )"><i class="fa fa-heart"></i></a>
                                            </div>
                                        </div>
                                    </div>';
        if (!is_null($product->category)) {
            $product_details .= '
                                        <div class="product-details-meta">
                                            <span>Categories: <a href="' . route('category.products', [$product->category->id, Str::slug($product->category->title)]) . '">' . optional($product->category)->title . '</a></span>

                                        </div>
                                        ';
        }

        $product_details .= '</div>
                            </div>';

        return ['product_details' => $product_details];
    }

    public function product_filter(Request $request) {
        $category_id = $request->category_id;
        $brand_id = $request->brand_id;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        if ($category_id != 'all' && $brand_id != 'all') {
            $category = Category::find($category_id);
            if ($category->parent_id == 0) {
                $products = Product::where('category_id', $category_id)->where('brand_id', $brand_id)->where('is_active', 1)->orderBy('id', 'DESC')->paginate(24);
            } else {
                $products = Product::where('sub_category_id', $category_id)->where('brand_id', $brand_id)->where('is_active', 1)->orderBy('id', 'DESC')->paginate(24);
            }
        } else if ($category_id != 'all' && $brand_id == 'all') {
            $category = Category::find($category_id);
            if ($category->parent_id == 0) {
                $products = Product::where('category_id', $category_id)->where('is_active', 1)->orderBy('id', 'DESC')->paginate(24);
            } else {
                $products = Product::where('sub_category_id', $category_id)->where('is_active', 1)->orderBy('id', 'DESC')->paginate(24);
            }
        } else if ($category_id == 'all' && $brand_id != 'all') {
            $products = Product::where('brand_id', $brand_id)->where('is_active', 1)->orderBy('id', 'DESC')->paginate(24);
        } else {
            $products = Product::where('is_active', 1)->orderBy('id', 'DESC')->paginate(24);
        }

        $product_ids = ProductStock::whereBetween('price', [$min_price, $max_price])->pluck('product_id')->toArray();

        $products = $products->filter(function ($product) use ($product_ids) {
            return in_array($product->id, $product_ids);
        });

        $product_filtered = '';

        if (count($products) > 0) {
            foreach ($products as $product) {
                $product_filtered .= '<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">';
                $product_filtered .= view('pages.partials.product', compact('product'));
                $product_filtered .= '</div>';
            }
        } else {
            $product_filtered .= '<h3 class="text-center p-4">Products not found.</h3>';
        }
        return ['product_filtered' => $product_filtered];
    }

    public function search(Request $request) {
        $search = $request->get('search');
        $filterResult = Product::where(function ($query) use ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%');
        })
            ->where('is_active', 1)
            ->pluck('title');

        return $filterResult;
    }

    public function search_result(Request $request) {
        $search = $request->search;
        $products = Product::where('is_active', 1)
            ->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            })->paginate(20);
        return view('pages.search-result', compact('products'));
    }

    public function categories() {
        $page = Page::find(3);
        $categories = Category::where('parent_id',  0)->where('is_active', 1)->orderBy('position', 'ASC')->get();
        return view('pages.categories', compact('categories', 'page'));
    }

    public function category_products($id, $slug) {
        $category = Category::find($id);
        $products = Product::where(function ($query) use ($id) {
            $query->where('category_id', $id)
                ->orWhere('sub_category_id', $id);
        })
            ->where('is_active', 1)->orderBy('id', 'DESC')->paginate(16);
        if (!is_null($category)) {
            return view('pages.category-product', compact('category', 'products'));
        } else {
            session()->flash('error', 'Page Not Found');
            return back();
        }
    }

    public function brand_products($id, $slug) {
        $brand = Brand::find($id);
        $products = Product::where('brand_id', $id)->where('is_active', 1)->orderBy('id', 'DESC')->paginate(16);
        if (!is_null($brand)) {
            return view('pages.brand-product', compact('brand', 'products'));
        } else {
            session()->flash('error', 'Page Not Found');
            return back();
        }
    }

    public function about() {
        $page = Page::find(4);
        return view('pages.about', compact('page'));
    }

    public function privacy_policy() {
        $page = Page::find(5);
        return view('pages.privacy-policy', compact('page'));
    }

    public function term_condition() {
        $page = Page::find(6);
        return view('pages.terms-and-conditions', compact('page'));
    }

    public function cancellation_policy() {
        $page = Page::find(7);
        return view('pages.cancellation-policy', compact('page'));
    }

    public function contact() {
        $page = Page::find(10);
        return view('pages.contact', compact('page'));
    }

    public function send_message(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        Mail::send(new ContactMail($request));

        session()->flash('success', 'Thank you for contacting us, we will be in touch within 24 to 48 hours');
        return redirect()->route('contact');
    }

    public function subscribe(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        $subscriber = Subscriber::where('email', $request->email)->first();
        if (is_null($subscriber)) {
            $subscriber = new Subscriber;
            $subscriber->email = $request->email;
            $subscriber->save();

            Alert::success('Thanks, Welcome to our NEWSLETTER', '');
            return back();
        } else {
            Alert::error('Thanks, You already subscribed us!', '');
            return back();
        }
    }

    public function get_shipping_charge(Request $request) {
        $area = Area::find($request->area_id);
        $shipping_charge = 0;
        if (!is_null($area)) {
            $setting = Setting::find(1);

            if ($area->location == 1) {
                $shipping_charge = $setting->shipping_charge_dhaka;
            } else if ($area->location == 2) {
                $shipping_charge = $setting->shipping_charge_dhaka_metro;
            } else {
                $shipping_charge = $setting->shipping_charge;
            }

            return $shipping_charge;
        } else {
            return $shipping_charge;
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
        $code = $order->code + 1;

        return $code;
    }

    public function order_create(Request $request) {
        if (Cart::content()->count() == 0) {
            return redirect()->route('products')->with('error', 'Your cart is empty! Please add some products');
        }

        $order = new Order;
        $order->code = $this->generateUniqueCode();
        if (Auth::user()) {
            $order->customer_id = Auth::id();
        } else {
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
            } else {
                $user = User::where('phone', $request->phone)->first();
                $order->customer_id = $user->id;
            }
        }

        $discount = 0;
        if (Session::has('coupon_discount')) {
            $discount = Session::get('coupon_discount');
        }

        $member_discount_rate = $request->member_discount_rate;
        $member_discount_amount = $request->member_discount_amount;
        $redeem_points_amount = $request->redeem_points_amount;

        $member = Auth::user() ? Auth::user()->member : null;
        if ($member) {
            $order->membership_discount = $member_discount_amount;

            $order->points_redeemed = $redeem_points_amount;
            $member->current_points -= $redeem_points_amount;
            $member->current_points += round(Cart::subtotal() * ($member->card->point_percentage / 100));
            $member->save();

            if ($member_discount_rate) {
                $order->discount_rate = $member_discount_rate;
            }
        }

        $order->price = Cart::subtotal() - $discount - $member_discount_amount - $redeem_points_amount;
        $order->name = $request->name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->district_id = $request->district_id;
        $order->area_id = $request->area_id;
        $order->shipping_address = $request->shipping_address;
        $order->delivery_charge = $request->shipping_charge;
        $order->note = $request->note;
        $order->source = 'Website';
        $order->payment_method = $request->payment_method;
        if ($request->payment_method == 'Bkash') {
            $order->transaction_id = $request->bkash_transaction_id;
            $order->sender_phone = $request->bkash_phone;
            $order->sender_amount = $request->bkash_amount;
        }
        if ($request->payment_method == 'Rocket') {
            $order->transaction_id = $request->rocket_transaction_id;
            $order->sender_phone = $request->rocket_phone;
            $order->sender_amount = $request->rocket_amount;
        }

        if (Session::has('coupon_discount')) {
            $order->discount_amount = Session::get('coupon_discount');
        }

        if (Session::has('wallet_amount')) {
            $order->wallet_amount = Session::get('wallet_amount');
        }

        $order->save();

        foreach (Cart::content() as $cart) {

            $order_product = new OrderProduct;

            $order_product->order_id = $order->id;
            $order_product->product_id = $cart->id;
            $order_product->size_id = $cart->options->size_id;
            $order_product->price = round($cart->price);
            $order_product->production_cost = $cart->options->production_cost;
            $order_product->qty = $cart->qty;
            $order_product->save();

            // $product = Product::find($cart->id);
            // $product->qty = $product->qty - $cart->qty;
            // $product->save();

            Cart::remove($cart->rowId);
        }

        if ($request->email != '') {
            Mail::send(new OrderMail($order));
        }
        Mail::send(new OrderNotificationMail($order));

        if (Auth::check()) {
            if (Session::has('wallet_amount')) {
                $wallet = Wallet::where('customer_id', Auth::id())->first();
                $entry = new WalletEntry;
                $entry->wallet_id = $wallet->id;
                $entry->cash_out = Session::get('wallet_amount');
                $entry->save();
            }
        }
        Session::forget('coupon_discount');
        Session::forget('wallet_amount');
        return redirect()->route('order.complete', $order->id);
    }

    public function order_complete($id) {
        $order = Order::find($id);
        if (!is_null($order)) {
            return view('pages.track-order-result', compact('order'));
            //return view('pages.order-complete', compact('order'));
        } else {
            session()->flash('error', 'Page Not Found');
            return back();
        }
    }

    public function order_track() {
        return view('pages.track-order');
    }

    public function order_track_result(Request $request) {
        $code = $request->code;
        $order = Order::where('code', $code)->first();
        if (!is_null($order)) {
            return view('pages.track-order-result', compact('order'));
        } else {
            // session()->flash('error','Page Not Found');
            Alert::error('Page Not Found');
            return back();
        }
    }

    // Customer profile
    public function my_orders() {
        if (Auth::check()) {
            $orders = Order::where('customer_id', Auth::id())->get();
            return view('pages.customer.orders', compact('orders'));
        } else {
            return redirect()->route('index');
        }
    }

    public function my_wishlist() {
        $wishlists = Wishlist::where('customer_id', Auth::id())->get();
        return view('pages.customer.wishlist', compact('wishlists'));
    }

    public function my_account() {
        if (Auth::check()) {
            if (Auth::user()->type == 1) {
                return redirect()->route('home');
            } else {
                $orders = Order::where('customer_id', Auth::id())->get();
                $wishlists = Wishlist::where('customer_id', Auth::id())->get();
                return view('pages.customer.account', compact('orders', 'wishlists'));
            }
        } else {
            return redirect()->route('index');
        }
    }

    public function customer_account_update(Request $request, $id) {
        $customer = User::find($id);
        if (!is_null($customer)) {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'phone' => 'required|unique:users,phone,' . $customer->id,
            ]);
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->address = $request->address;

            // image save
            if ($request->image) {
                $image = $request->file('image');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/user/' . $img);
                Image::make($image)->save($location);
                $customer->image = $img;
            }

            $customer->save();
            Alert::success('Customer Profile Updated!', '');
            return back();
        } else {
            Alert::error('Something went wrong!', '');
            return back();
        }
    }

    public function change_password(Request $request) {
        $user = Auth::user();
        $c_password = $request->c_password;
        $n_password = $request->n_password;
        $cf_password = $request->cf_password;
        //dd(Hash::make($c_password));
        if (Hash::check($request->c_password, $user->password)) {
            if ($n_password == $cf_password) {
                $user->password = Hash::make($n_password);
                $user->save();
                Alert::success('Password has been updated', '');
                return back();
            } else {
                Alert::error('Password do not match !', '');
                return back();
            }
        } else {
            Alert::error('Your current password is wrong !', '');
            return back();
        }
    }
}
