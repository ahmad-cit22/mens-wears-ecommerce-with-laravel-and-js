<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('index');

Route::get('/products', [App\Http\Controllers\PageController::class, 'products'])->name('products');
Route::get('/offer-products', [App\Http\Controllers\PageController::class, 'offer_products'])->name('offer.products');
Route::get('/hot-deals', [App\Http\Controllers\PageController::class, 'hot_deals'])->name('hot.deals');
Route::get('/trending-products', [App\Http\Controllers\PageController::class, 'trending_products'])->name('trending.products');
Route::get('/offer-products', [App\Http\Controllers\PageController::class, 'offer_products'])->name('offer.products');
Route::get('/product/{id}/{slug}', [App\Http\Controllers\PageController::class, 'single_product'])->name('single.product');
Route::post('/api-products-details', [App\Http\Controllers\PageController::class, 'api_product_details'])->name('api.product.details');

Route::get('/categories', [App\Http\Controllers\PageController::class, 'categories'])->name('categories');
Route::get('/category/{id}/{slug}', [App\Http\Controllers\PageController::class, 'category_products'])->name('category.products');

Route::get('/brand/{id}/{slug}', [App\Http\Controllers\PageController::class, 'brand_products'])->name('brand.products');

Route::get('/about-us', [App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/contact-us', [App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::post('/contact-us-message-send', [App\Http\Controllers\PageController::class, 'send_message'])->name('message.send');

Route::get('/search', [App\Http\Controllers\PageController::class, 'search'])->name('search');
Route::get('/search-result', [App\Http\Controllers\PageController::class, 'search_result'])->name('search.result');
Route::post('/subsribe', [App\Http\Controllers\PageController::class, 'subscribe'])->name('subscribe');

Route::get('/privacy-policy', [App\Http\Controllers\PageController::class, 'privacy_policy'])->name('privacy.policy');
Route::get('/cancellation-and-policy', [App\Http\Controllers\PageController::class, 'cancellation_policy'])->name('cancellation.policy');
Route::get('/terms-and-conditions', [App\Http\Controllers\PageController::class, 'term_condition'])->name('term.condition');

// Cart Route
Route::get('/shopping-carts', [App\Http\Controllers\CartController::class, 'index'])->name('carts');
Route::post('/add-to-cart', [App\Http\Controllers\CartController::class, 'add_cart'])->name('cart.add');
Route::post('/update-cart', [App\Http\Controllers\CartController::class, 'update_cart'])->name('cart.update');
Route::post('/remove-from-cart', [App\Http\Controllers\CartController::class, 'remove_cart'])->name('cart.remove');
Route::get('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');

// Wishlist Route
Route::post('/add-to-wishlist', [App\Http\Controllers\WishlistController::class, 'add_wishlist'])->name('wishlist.add');
Route::post('/remove-from-wishlist/{id}', [App\Http\Controllers\WishlistController::class, 'remove_wishlist'])->name('wishlist.remove');


// Coupon Routes
Route::post('/apply-coupon', [App\Http\Controllers\CartController::class, 'apply_coupon'])->name('coupon.apply');
Route::get('/remove-coupon', [App\Http\Controllers\CartController::class, 'remove_coupon'])->name('coupon.remove');

// Wallet Routes in Cart
Route::post('/wallet-use', [App\Http\Controllers\CartController::class, 'wallet_use'])->name('wallet.use');
Route::get('/remove-wallet', [App\Http\Controllers\CartController::class, 'remove_wallet'])->name('wallet.remove');

// Order routes
Route::post('/order-create', [App\Http\Controllers\PageController::class, 'order_create'])->name('order.create');
Route::get('/order-complete/{id}', [App\Http\Controllers\PageController::class, 'order_complete'])->name('order.complete');
Route::get('/track-order', [App\Http\Controllers\PageController::class, 'order_track'])->name('order.track');
Route::get('/track-order-status', [App\Http\Controllers\PageController::class, 'order_track_result'])->name('order.track.result');

// Customer Profile Routes
Route::group(['middleware' => ['auth']], function () {
	Route::get('/my-orders', [App\Http\Controllers\PageController::class, 'my_orders'])->name('customer.orders');
	Route::get('/my-wishlist', [App\Http\Controllers\PageController::class, 'my_wishlist'])->name('customer.wishlist');
	Route::get('/my-account', [App\Http\Controllers\PageController::class, 'my_account'])->name('customer.account');
	Route::post('/customer-account-update/{id}', [App\Http\Controllers\PageController::class, 'customer_account_update'])->name('customer.account.update');
	Route::post('/customer-password-change', [App\Http\Controllers\PageController::class, 'change_password'])->name('customer.password.change');
	Route::get('/my-wallet', [App\Http\Controllers\PageController::class, 'my_wallet'])->name('customer.wallet');
	Route::post('/my-wallet/point-convert', [App\Http\Controllers\PageController::class, 'my_wallet_point_convert'])->name('customer.point.convert');
});

// API Routes
Route::get('get-sub-category/{id}', function ($id) {
	return json_encode(App\Models\Category::where('parent_id', $id)->where('is_active', 1)->get());
});
Route::post('/product-filter', [App\Http\Controllers\PageController::class, 'product_filter'])->name('product.filter');

Route::get('get-area/{id}', function ($id) {
	return json_encode(App\Models\Area::where('district_id', $id)->get());
});

Route::get('get-size/{id}', function ($id) {
	return json_encode(App\Models\Size::whereIn('id', (App\Models\ProductStock::where('product_id', $id)->pluck('size_id')->toArray()))->get());
});


Route::post('/get-shipping-charge', [App\Http\Controllers\PageController::class, 'get_shipping_charge'])->name('shipping_charge.get');

// API Routes End

Auth::routes();

// Admin Routes
Route::group(['prefix' => '/home', 'middleware' => ['auth']], function () {

	Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::get('/cache-clear', [App\Http\Controllers\HomeController::class, 'cache_clear'])->name('cache.clear');
	Route::get('/sitemap-generate', [App\Http\Controllers\HomeController::class, 'sitemap_generate'])->name('sitemap.generate');

	// Role Routes
	Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
		Route::get('/', [App\Http\Controllers\RoleController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\RoleController::class, 'create'])->name('create');
		Route::post('/store', [App\Http\Controllers\RoleController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\RoleController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('destroy');
	});

	// User Routes
	Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
		Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('create');
		Route::post('/store', [App\Http\Controllers\UserController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
	});

	// Customer Routes
	Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
		Route::get('/', [App\Http\Controllers\UserController::class, 'customer_index'])->name('index');
		Route::get('/search', [App\Http\Controllers\UserController::class, 'customer_search'])->name('search');
		Route::post('/change-password-by-admin/{id}', [App\Http\Controllers\UserController::class, 'customer_password_change'])->name('password.change.admin');
		Route::post('/destroy/{id}', [App\Http\Controllers\UserController::class, 'customer_destroy'])->name('destroy');
		Route::post('/status/update/{id}', [App\Http\Controllers\UserController::class, 'customer_status_update'])->name('status_update');
		Route::post('/type/update/{id}', [App\Http\Controllers\UserController::class, 'customer_type_update'])->name('type_update');
	});

	// Category Routes
	Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
		Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\CategoryController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('destroy');
	});

	// Brand Routes
	Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
		Route::get('/', [App\Http\Controllers\BrandController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\BrandController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\BrandController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\BrandController::class, 'destroy'])->name('destroy');
	});

	// Size Routes
	Route::group(['prefix' => 'size', 'as' => 'size.'], function () {
		Route::get('/', [App\Http\Controllers\SizeController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\SizeController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\SizeController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\SizeController::class, 'destroy'])->name('destroy');
	});

	// Production Routes
	Route::group(['prefix' => 'production', 'as' => 'production.'], function () {
		Route::get('/', [App\Http\Controllers\ProductionController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\ProductionController::class, 'create'])->name('create');
		Route::post('/store', [App\Http\Controllers\ProductionController::class, 'store'])->name('store');
		Route::post('/supplier/{id}', [App\Http\Controllers\ProductionController::class, 'supplier'])->name('supplier');
		Route::get('/show/{id}', [App\Http\Controllers\ProductionController::class, 'show'])->name('show');
		Route::get('/recalculate/{id}', [App\Http\Controllers\ProductionController::class, 'recalculate'])->name('recalculate');
		Route::post('/accessory/{id}', [App\Http\Controllers\ProductionController::class, 'accessory'])->name('accessory');
		Route::post('/cost/{id}', [App\Http\Controllers\ProductionController::class, 'cost'])->name('cost');
		Route::post('/destroy/{id}', [App\Http\Controllers\ProductionController::class, 'destroy'])->name('destroy');
	});

	// Product Routes
	Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
		Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('index');
		Route::get('/search_table', [App\Http\Controllers\ProductController::class, 'product_search'])->name('search_table');
		Route::get('/create', [App\Http\Controllers\ProductController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\ProductController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');
		Route::post('/variation/store/{id}', [App\Http\Controllers\ProductController::class, 'variation_store'])->name('variation.store');
		Route::get('/gallery/destroy/{id}', [App\Http\Controllers\ProductController::class, 'gallery_destroy'])->name('gallery.destroy');
		Route::get('/print-label', [App\Http\Controllers\ProductController::class, 'print_label'])->name('printlabel');
		Route::get('/print-label=result', [App\Http\Controllers\ProductController::class, 'print_label_result'])->name('printlabel.result');
	});

	// Product Stock Routes
	Route::group(['prefix' => 'stock', 'as' => 'stock.'], function () {
		Route::get('/add-stock', [App\Http\Controllers\ProductStockHistoryController::class, 'add_stock'])->name('add');
		Route::post('/add-stock-barcode-scan', [App\Http\Controllers\ProductStockHistoryController::class, 'add_stock_barcode_scan'])->name('add.barcode.scan');
		Route::get('/current-stock', [App\Http\Controllers\ProductStockHistoryController::class, 'current'])->name('current');
		Route::get('/history', [App\Http\Controllers\ProductStockHistoryController::class, 'index'])->name('index');
		Route::get('/history-search', [App\Http\Controllers\ProductStockHistoryController::class, 'stock_history_search'])->name('history.search');
		Route::get('/total-sold-amount', [App\Http\Controllers\ProductStockHistoryController::class, 'total_sold_amount'])->name('total.sold.amount');
		Route::get('/total-remaining-amount', [App\Http\Controllers\ProductStockHistoryController::class, 'total_remaining_amount'])->name('total.remaining.amount');
		Route::post('/stote', [App\Http\Controllers\ProductStockHistoryController::class, 'store'])->name('store');
	});

	// Product Damage Routes
	Route::group(['prefix' => 'damage', 'as' => 'damage.'], function () {
		Route::get('/', [App\Http\Controllers\ProductDamageController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\ProductDamageController::class, 'store'])->name('store');
		Route::post('/product-barcode-scan', [App\Http\Controllers\ProductDamageController::class, 'product_barcode_scan'])->name('product.barcode.scan');
	});

	// Reject Product Routes
	Route::group(['prefix' => 'reject', 'as' => 'reject.'], function () {
		Route::get('/', [App\Http\Controllers\RejectedProductController::class, 'index'])->name('index');
		Route::get('/add-view', [App\Http\Controllers\RejectedProductController::class, 'add_view'])->name('add.view');
		Route::post('/store', [App\Http\Controllers\RejectedProductController::class, 'store'])->name('store');
		Route::get('/stock', [App\Http\Controllers\RejectedProductController::class, 'stock'])->name('stock');
		Route::get('/product-out-form', [App\Http\Controllers\RejectedProductController::class, 'product_out_form'])->name('product.out.form');
		Route::post('/product-out-store', [App\Http\Controllers\RejectedProductController::class, 'product_out_store'])->name('product.out.store');
		Route::get('/product-out-list', [App\Http\Controllers\RejectedProductController::class, 'product_out_list'])->name('product.out.list');
	});

	// Order Routes
	Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
		Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
		Route::get('/export-excel', [App\Http\Controllers\OrderController::class, 'order_export_excel2'])->name('export.excel');
		Route::get('/status/{id}', [App\Http\Controllers\OrderController::class, 'orders_by_status'])->name('status.filter');
		//Route::get('/create', [App\Http\Controllers\OrderController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\OrderController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\OrderController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\OrderController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('destroy');

		Route::post('/change-status/{id}', [App\Http\Controllers\OrderController::class, 'change_status'])->name('status.change');
		Route::post('/change-payment-status/{id}', [App\Http\Controllers\OrderController::class, 'change_payment_status'])->name('payment.status.change');
		Route::post('/take-advance/{id}', [App\Http\Controllers\OrderController::class, 'take_advance'])->name('advance.payment');
		Route::post('/convert-to-sell/{id}', [App\Http\Controllers\OrderController::class, 'convert_sell'])->name('convert.sell');
		Route::get('/return/{id}', [App\Http\Controllers\OrderController::class, 'return'])->name('return');
		Route::post('/apply-cod/{id}', [App\Http\Controllers\OrderController::class, 'apply_cod'])->name('apply.cod');
		Route::get('/remove-discount/{id}', [App\Http\Controllers\OrderController::class, 'remove_discount'])->name('remove.discount');
		Route::get('/remove-loss/{id}', [App\Http\Controllers\OrderController::class, 'remove_loss'])->name('remove.loss');
		// Invoice route
		Route::get('/generate-invoice/{id}', [App\Http\Controllers\OrderController::class, 'generate_invoice'])->name('invoice.generate');
		Route::get('/generate-pos-invoice/{id}', [App\Http\Controllers\OrderController::class, 'generate_pos_invoice'])->name('invoice.pos.generate');
		Route::get('/packaging/{id}', [App\Http\Controllers\OrderController::class, 'packaging'])->name('packaging');
		Route::post('/product-barcode-check', [App\Http\Controllers\OrderController::class, 'product_barcode_check'])->name('barcode.check');
		Route::get('/product-barcode-check-confirm/{id}', [App\Http\Controllers\OrderController::class, 'product_barcode_check_confirm'])->name('barcode.check.confirm');
		Route::get('/order-packet-done/{id}', [App\Http\Controllers\OrderController::class, 'packet_done'])->name('packet_done');
		Route::post('/refer-code-store/{id}', [App\Http\Controllers\OrderController::class, 'refer_code_store'])->name('refer_code.store');

		// Report routes
		Route::get('/all', [App\Http\Controllers\OrderController::class, 'all_orders'])->name('all');
		Route::get('/current-year', [App\Http\Controllers\OrderController::class, 'current_year'])->name('current.year');
		Route::get('/current-month', [App\Http\Controllers\OrderController::class, 'current_month'])->name('current.month');
		Route::get('/today', [App\Http\Controllers\OrderController::class, 'today'])->name('today');
		Route::get('/search', [App\Http\Controllers\OrderController::class, 'search'])->name('search');
		Route::get('/search/export', [App\Http\Controllers\OrderController::class, 'search_export'])->name('search.export');
		Route::get('/customer-orders/{id}', [App\Http\Controllers\OrderController::class, 'customer_orders'])->name('customer.orders');
	});

	// Sell Routes
	Route::group(['prefix' => 'sell', 'as' => 'sell.'], function () {
		Route::get('/', [App\Http\Controllers\OrderController::class, 'sell_index'])->name('index');
		Route::get('/export-excel', [App\Http\Controllers\OrderController::class, 'sell_export_excel2'])->name('export.excel');
		Route::get('/report', [App\Http\Controllers\OrderController::class, 'sell_report'])->name('report');
		Route::get('/wholesale', [App\Http\Controllers\OrderController::class, 'wholesale_index'])->name('wholesale.index');
		Route::get('/wholesale-export-excel', [App\Http\Controllers\OrderController::class, 'wholesale_export_excel2'])->name('wholesale.export.excel');
		Route::get('/search', [App\Http\Controllers\OrderController::class, 'sell_search'])->name('search');
		Route::get('/search-export', [App\Http\Controllers\OrderController::class, 'sell_search_export'])->name('search.export');
		Route::get('/report_search', [App\Http\Controllers\OrderController::class, 'report_search'])->name('report_search');
		Route::get('/wholesale-search', [App\Http\Controllers\OrderController::class, 'wholesale_search'])->name('wholesale.search');
		Route::get('/wholesale-search-export', [App\Http\Controllers\OrderController::class, 'wholesale_search_export'])->name('wholesale.search.export');
		Route::get('/sell-export', [App\Http\Controllers\OrderController::class, 'sell_export_excel'])->name('sell.export');
		Route::get('/wholesale-export', [App\Http\Controllers\OrderController::class, 'wholesale_export_excel'])->name('wholesale.export');
        Route::get('/vat-calculate/{id}', [App\Http\Controllers\OrderController::class, 'vat_calculate'])->name('vat.calculate');
	});

	// Sell Return Routes
	Route::group(['prefix' => 'sell-return', 'as' => 'sellreturn.'], function () {
		Route::get('/', [App\Http\Controllers\OrderReturnController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\OrderReturnController::class, 'store'])->name('store');
		Route::get('/search', [App\Http\Controllers\OrderReturnController::class, 'search'])->name('search');
	});

	// Coupone Routes
	Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
		Route::get('/', [App\Http\Controllers\CouponController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\CouponController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\CouponController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\CouponController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\CouponController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\CouponController::class, 'destroy'])->name('destroy');
	});

	// POS Routes
	Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
		Route::get('/', [App\Http\Controllers\PosController::class, 'index'])->name('index');
		Route::get('/create/{id}', [App\Http\Controllers\PosController::class, 'create'])->name('create');
		Route::get('/wholesale-create', [App\Http\Controllers\PosController::class, 'wholesale_create'])->name('wholesale.create');
		Route::post('/store', [App\Http\Controllers\PosController::class, 'store'])->name('store');
		Route::post('/filter-product', [App\Http\Controllers\PosController::class, 'product_filter'])->name('product.filter');
		Route::post('/add-to-cart', [App\Http\Controllers\PosController::class, 'add_cart'])->name('cart.add');
		Route::post('/barcode-add-to-cart', [App\Http\Controllers\PosController::class, 'barcode_add_cart'])->name('barcode.cart.add');
		Route::post('/update-cart', [App\Http\Controllers\PosController::class, 'update_cart'])->name('cart.update');
		Route::post('/remove-from-cart', [App\Http\Controllers\PosController::class, 'remove_cart'])->name('cart.remove');
		Route::post('/apply-discount', [App\Http\Controllers\PosController::class, 'apply_discount'])->name('apply.discount');
	});

    // vat entry routes
    Route::group(['prefix' => 'vat-entry', 'as' => 'vat_entry.'], function () {
        Route::get('/', [App\Http\Controllers\VatEntryController::class, 'index'])->name('index');
        // Route::get('/create/{id}', [App\Http\Controllers\VatEntryController::class, 'create'])->name('create');
        // Route::post('/store', [App\Http\Controllers\VatEntryController::class, 'store'])->name('store');
        // Route::get('/edit/{id}', [App\Http\Controllers\VatEntryController::class, 'edit'])->name('edit');
        // Route::post('/update/{id}', [App\Http\Controllers\VatEntryController::class, 'update'])->name('update');
        // Route::post('/destroy/{id}', [App\Http\Controllers\VatEntryController::class, 'destroy'])->name('destroy');
    });

	// Order Sheet (FOS) Routes
	Route::group(['prefix' => 'fos', 'as' => 'fos.'], function () {
		Route::get('/', [App\Http\Controllers\FacebookOrderController::class, 'index'])->name('index');
		Route::get('/search_table', [App\Http\Controllers\FacebookOrderController::class, 'fos_search'])->name('search_table');
		Route::get('/create', [App\Http\Controllers\FacebookOrderController::class, 'create'])->name('create');
		Route::get('/wholesale-create', [App\Http\Controllers\FacebookOrderController::class, 'wholesale_create'])->name('wholesale.create');
		Route::post('/store', [App\Http\Controllers\FacebookOrderController::class, 'store'])->name('store');
		Route::post('/filter-product', [App\Http\Controllers\FacebookOrderController::class, 'product_filter'])->name('product.filter');
		Route::post('/add-to-cart', [App\Http\Controllers\FacebookOrderController::class, 'add_cart'])->name('cart.add');
		Route::post('/barcode-add-to-cart', [App\Http\Controllers\FacebookOrderController::class, 'barcode_add_cart'])->name('barcode.cart.add');
		Route::post('/update-cart', [App\Http\Controllers\FacebookOrderController::class, 'update_cart'])->name('cart.update');
		Route::post('/remove-from-cart', [App\Http\Controllers\FacebookOrderController::class, 'remove_cart'])->name('cart.remove');
		Route::post('/apply-discount', [App\Http\Controllers\FacebookOrderController::class, 'apply_discount'])->name('apply.discount');

		Route::get('/edit/{id}', [App\Http\Controllers\FacebookOrderController::class, 'edit'])->name('edit');
		Route::post('/order-info-update/{id}', [App\Http\Controllers\FacebookOrderController::class, 'order_info_update'])->name('order_info.update');
		Route::post('/order-products-update/{id}', [App\Http\Controllers\FacebookOrderController::class, 'order_products_update'])->name('order_products.update');
		Route::post('/destroy/{id}', [App\Http\Controllers\FacebookOrderController::class, 'destroy'])->name('destroy');

		Route::post('/take-advance/{id}', [App\Http\Controllers\FacebookOrderController::class, 'take_advance'])->name('advance.payment');
		Route::get('/search', [App\Http\Controllers\FacebookOrderController::class, 'search'])->name('search');
	});

	// fos Status Routes
	Route::group(['prefix' => 'fos/status', 'as' => 'fos.status.'], function () {
		Route::get('/', [App\Http\Controllers\FacebookOrderStatusController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\FacebookOrderStatusController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\FacebookOrderStatusController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\FacebookOrderStatusController::class, 'destroy'])->name('destroy');
	});

	// fos Special Status Routes
	Route::group(['prefix' => 'fos/special-status', 'as' => 'fos.special_status.'], function () {
		Route::get('/', [App\Http\Controllers\OrderSpecialStatusController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\OrderSpecialStatusController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\OrderSpecialStatusController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\OrderSpecialStatusController::class, 'destroy'])->name('destroy');
	});

	// courier name Routes
	Route::group(['prefix' => 'fos/courier-name', 'as' => 'fos.courier_name.'], function () {
		Route::get('/', [App\Http\Controllers\CourierNameController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\CourierNameController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\CourierNameController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\CourierNameController::class, 'destroy'])->name('destroy');
	});

	// business bkash number Routes
	Route::group(['prefix' => 'fos/bkash-number', 'as' => 'fos.bkash_number.'], function () {
		Route::get('/', [App\Http\Controllers\BkashNumberController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\BkashNumberController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\BkashNumberController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\BkashNumberController::class, 'destroy'])->name('destroy');
	});


	// bkash panel Routes
	Route::group(['prefix' => 'bkash-panel', 'as' => 'bkash_panel.'], function () {
		Route::get('/', [App\Http\Controllers\BkashRecordController::class, 'index'])->name('index');
		Route::get('/search', [App\Http\Controllers\BkashRecordController::class, 'transactions_search'])->name('search');
		Route::get('/create', [App\Http\Controllers\BkashRecordController::class, 'create'])->name('create');
		Route::post('/store', [App\Http\Controllers\BkashRecordController::class, 'store'])->name('store');

		// tr-purposes routes
		Route::get('/tr-purposes', [App\Http\Controllers\BkashRecordPurposeController::class, 'tr_purposes'])->name('tr_purposes');
		Route::post('/tr-purposes/store', [App\Http\Controllers\BkashRecordPurposeController::class, 'store'])->name('tr_purposes.store');
		Route::post('/tr-purposes/update/{id}', [App\Http\Controllers\BkashRecordPurposeController::class, 'update'])->name('tr_purposes.update');
		Route::post('/tr-purposes/destroy/{id}', [App\Http\Controllers\BkashRecordPurposeController::class, 'destroy'])->name('tr_purposes.destroy');
	});

	// Slider Routes
	Route::group(['prefix' => 'slider', 'as' => 'slider.'], function () {
		Route::get('/', [App\Http\Controllers\SliderController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\SliderController::class, 'create'])->name('create');
		Route::post('/stote', [App\Http\Controllers\SliderController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\SliderController::class, 'edit'])->name('edit');
		Route::post('/update', [App\Http\Controllers\SliderController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\SliderController::class, 'destroy'])->name('destroy');
		Route::post('/update-video', [App\Http\Controllers\SliderController::class, 'update_video'])->name('video.update');
	});

	// Pages in Admin
	Route::group(['prefix' => 'page', 'as' => 'page.'], function () {

		Route::get('/', [App\Http\Controllers\AdminPageController::class, 'index'])->name('index');
		Route::get('/edit/{id}', [App\Http\Controllers\AdminPageController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\AdminPageController::class, 'update'])->name('update');
	});

	// Setting Routes
	Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
		Route::get('/', [App\Http\Controllers\SettingController::class, 'index'])->name('index');
		Route::post('/update/{id}', [App\Http\Controllers\SettingController::class, 'update'])->name('update');
	});

	// Trending Routes
	Route::group(['prefix' => 'trending', 'as' => 'trending.'], function () {
		Route::get('/', [App\Http\Controllers\TrendingController::class, 'index'])->name('index');
		Route::post('/update/{id}', [App\Http\Controllers\TrendingController::class, 'update'])->name('update');
	});

	// Profile Routes
	Route::group(['prefix' => 'profile', 'as' => 'user.'], function () {

		Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
		Route::post('/update', [App\Http\Controllers\ProfileController::class, 'profile_update'])->name('profile.update');
		Route::post('/change-password', [App\Http\Controllers\ProfileController::class, 'change_password'])->name('password.change');
	});

	//Subscribers in admin
	Route::get('/subscribers', [App\Http\Controllers\SubscriberController::class, 'index'])->name('admin.subscribers');


	// District Routes
	Route::group(['prefix' => 'district', 'as' => 'district.'], function () {
		Route::get('/', [App\Http\Controllers\DistrictController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\DistrictController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\DistrictController::class, 'update'])->name('update');
		Route::get('/upload', [App\Http\Controllers\DistrictController::class, 'upload'])->name('upload');
		Route::post('/destroy/{id}', [App\Http\Controllers\DistrictController::class, 'destroy'])->name('destroy');
	});

	// Area Routes
	Route::group(['prefix' => 'area', 'as' => 'area.'], function () {
		Route::get('/', [App\Http\Controllers\AreaController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\AreaController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\AreaController::class, 'update'])->name('update');
		Route::get('/upload', [App\Http\Controllers\AreaController::class, 'upload'])->name('upload');
		Route::post('/destroy/{id}', [App\Http\Controllers\AreaController::class, 'destroy'])->name('destroy');
	});

	// Expense Routes
	Route::group(['prefix' => 'expense', 'as' => 'expense.'], function () {
		Route::get('/', [App\Http\Controllers\ExpenseController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\ExpenseController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\ExpenseController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('destroy');
	});

	// Expense Entry Routes
	Route::group(['prefix' => 'expense-entries', 'as' => 'expenseentry.'], function () {
		Route::get('/', [App\Http\Controllers\ExpenseEntryController::class, 'index'])->name('index');
		Route::get('/search', [App\Http\Controllers\ExpenseEntryController::class, 'search'])->name('search');
		Route::post('/stote', [App\Http\Controllers\ExpenseEntryController::class, 'store'])->name('store');
		Route::post('/loss-store', [App\Http\Controllers\ExpenseEntryController::class, 'loss_store'])->name('loss.store');
		Route::post('/update/{id}', [App\Http\Controllers\ExpenseEntryController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\ExpenseEntryController::class, 'destroy'])->name('destroy');
	});

	// Bank Routes
	Route::group(['prefix' => 'bank', 'as' => 'bank.'], function () {
		Route::get('/', [App\Http\Controllers\BankController::class, 'index'])->name('index');
		Route::post('/stote', [App\Http\Controllers\BankController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\BankController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\BankController::class, 'destroy'])->name('destroy');
	});

	// Bank Transaction Routes
	Route::group(['prefix' => 'bank-transaction', 'as' => 'banktransaction.'], function () {
		Route::get('/', [App\Http\Controllers\BankTransactionController::class, 'index'])->name('index');
		Route::get('/search', [App\Http\Controllers\BankTransactionController::class, 'search'])->name('search');
		Route::post('/stote', [App\Http\Controllers\BankTransactionController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\BankTransactionController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\BankTransactionController::class, 'destroy'])->name('destroy');
	});

	// Bank Contra Transaction Routes
	Route::group(['prefix' => 'bank-contra', 'as' => 'bankcontra.'], function () {
		Route::get('/', [App\Http\Controllers\BankContraController::class, 'index'])->name('index');
		Route::get('/search', [App\Http\Controllers\BankContraController::class, 'search'])->name('search');
		Route::post('/stote', [App\Http\Controllers\BankContraController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\BankContraController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\BankContraController::class, 'destroy'])->name('destroy');
	});

	// Supplier Routes
	Route::group(['prefix' => 'supplier', 'as' => 'supplier.'], function () {
		Route::get('/', [App\Http\Controllers\SupplierController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\SupplierController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\SupplierController::class, 'update'])->name('update');
		Route::post('/debt', [App\Http\Controllers\SupplierController::class, 'debt'])->name('debt');
		Route::post('/destroy/{id}', [App\Http\Controllers\SupplierController::class, 'destroy'])->name('destroy');
	});

	// Supplier Payment Routes
	Route::group(['prefix' => 'supplier-payment', 'as' => 'supplierpayment.'], function () {
		Route::get('/', [App\Http\Controllers\SupplierPaymentController::class, 'index'])->name('index');
		Route::get('/search', [App\Http\Controllers\SupplierPaymentController::class, 'search'])->name('search');
		Route::post('/store', [App\Http\Controllers\SupplierPaymentController::class, 'store'])->name('store');
	});

	// Accessory Routes
	Route::group(['prefix' => 'accessory', 'as' => 'accessory.'], function () {
		Route::get('/', [App\Http\Controllers\AccessoryController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\AccessoryController::class, 'store'])->name('store');
		Route::post('/update/{id}', [App\Http\Controllers\AccessoryController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\AccessoryController::class, 'destroy'])->name('destroy');
	});

	// Accessory Stock Routes
	Route::group(['prefix' => 'accessory-stock', 'as' => 'accessorystock.'], function () {
		Route::get('/', [App\Http\Controllers\AccessoryAmountController::class, 'index'])->name('index');
		Route::get('/search', [App\Http\Controllers\AccessoryAmountController::class, 'search'])->name('search');
		Route::post('/store', [App\Http\Controllers\AccessoryAmountController::class, 'store'])->name('store');
	});

	// Asset Routes
	Route::group(['prefix' => 'asset', 'as' => 'asset.'], function () {
		Route::get('/', [App\Http\Controllers\AssetController::class, 'index'])->name('index');
		Route::get('/create', [App\Http\Controllers\AssetController::class, 'create'])->name('create');
		Route::post('/store', [App\Http\Controllers\AssetController::class, 'store'])->name('store');
		Route::get('/edit/{id}', [App\Http\Controllers\AssetController::class, 'edit'])->name('edit');
		Route::post('/update/{id}', [App\Http\Controllers\AssetController::class, 'update'])->name('update');
		Route::post('/destroy/{id}', [App\Http\Controllers\AssetController::class, 'destroy'])->name('destroy');
		Route::post('/deduct-now/{id}', [App\Http\Controllers\AssetController::class, 'deduct_now'])->name('deduct.now');
		// Route::get('/deduct', [App\Http\Controllers\AssetController::class, 'deduct'])->name('deduct');
		Route::post('/dispose/{id}', [App\Http\Controllers\AssetController::class, 'dispose'])->name('dispose');
	});

	// Report Routes
	Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
		Route::get('/income-statement', [App\Http\Controllers\ReportController::class, 'income_statement'])->name('incomestatement');
		Route::get('/income-statement-search', [App\Http\Controllers\ReportController::class, 'income_statement_search'])->name('incomestatement.search');
		Route::get('/balance-sheet', [App\Http\Controllers\ReportController::class, 'balance_sheet'])->name('balancesheet');
		Route::get('/owners-equity', [App\Http\Controllers\ReportController::class, 'owners_equity'])->name('ownersequity');
	});

	// Partners Routes
	Route::group(['prefix' => 'partners', 'as' => 'partner.'], function () {
		Route::get('/', [App\Http\Controllers\PartnerController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\PartnerController::class, 'store'])->name('store');
		Route::get('/search', [App\Http\Controllers\PartnerController::class, 'search'])->name('search');
		Route::post('/update/{id}', [App\Http\Controllers\PartnerController::class, 'update'])->name('update');
	});

	// Partner Transaction Routes
	Route::group(['prefix' => 'partner-transactions', 'as' => 'partnertransaction.'], function () {
		Route::get('/', [App\Http\Controllers\PartnerTransactionController::class, 'index'])->name('index');
		Route::post('/store', [App\Http\Controllers\PartnerTransactionController::class, 'store'])->name('store');
		Route::get('/search', [App\Http\Controllers\PartnerTransactionController::class, 'search'])->name('search');
	});
});
