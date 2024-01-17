<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\ProductStockHistory;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Size;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\Accessory;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Auth;
use Alert;
use File;
use Intervention\Image\Facades\Image;
use DNS1D;
use DNS2D;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('product.index')) {
            $products = Product::orderBy('id', 'DESC')->with('category', 'brand', 'variation', 'variations', 'variations.size', 'product_image', 'ratings')->paginate(10);
            // return $products;
            return view('admin.product.index', compact('products'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function product_search(Request $request) {

        $search = $request->search;

        if (auth()->user()->can('product.index')) {
            $products = Product::where('title', 'like', '%' . $search . '%')->orderBy('id', 'DESC')->with('category', 'brand', 'variation', 'variations', 'variations.size', 'product_image', 'ratings')->paginate(10);
            if (count($products) > 0) {
                return view('admin.product.index', compact('products'));
            }
            return back()->with('error', 'No results Found');
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
        if (auth()->user()->can('product.index')) {
            $categories = Category::where('parent_id', 0)->orderBy('id', 'DESC')->get();
            $brands = Brand::orderBy('id', 'DESC')->get();
            $sizes = Size::orderBy('id', 'DESC')->get();
            return view('admin.product.create', compact('categories', 'brands', 'sizes'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function print_label() {
        if (auth()->user()->can('product.print_label')) {
            $products = Product::where('is_active', 1)->orderBy('id', 'DESC')->get();
            // return DNS1D::getBarcodeSVG('1005', 'C39');
            // return '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('1004', 'C39') . '" alt="barcode"   />';
            return view('admin.product.print-label', compact('products'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function print_label_result(Request $request) {
        if (auth()->user()->can('product.index')) {
            $product_id = $request->product_id;
            $size_id = $request->size_id;
            $qty = $request->qty;
            $stock = ProductStock::where('product_id', $product_id)->where('size_id', $size_id)->first();
            return view('admin.product.print-label-result', compact('stock', 'qty'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function generateUniqueCode() {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }

        if (ProductStock::where('code', $code)->exists()) {
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
        if (auth()->user()->can('product.create')) {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
            ]);

            if ($request->type == 'single') {
                $validatedData = $request->validate([
                    'production_cost' => 'required|numeric',
                    'price' => 'required|numeric',
                    'wholesale_price' => 'required|numeric',
                    'qty' => 'required|numeric',
                ]);
            }

            if ($request->type == 'variation') {
                $validatedData = $request->validate([
                    'production_costs.*' => 'required|numeric',
                    'prices.*' => 'required|numeric',
                    'wholesale_prices.*' => 'required|numeric',
                    'qtys.*' => 'required|numeric',
                ]);
            }

            $product = new Product;
            $product->title = $request->title;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->type = $request->type;
            $product->meta_description = $request->meta_description;

            if ($request->has('is_sale')) {
                $product->is_sale = 1;
            }


            // image save
            if ($request->image) {
                $image = $request->file('image');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $location_one = public_path('images/product/' . $img);
                Image::make($image)->save($location_one);
                $location_two = public_path('images/product/pos_images/' . $img);
                Image::make($image)->resize(150, 200)->save($location_two);
                $product->image = $img;
            }

            // size_chart save
            if ($request->size_chart) {
                $image = $request->file('size_chart');
                $img = 'size_chart_' . time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/product/' . $img);
                Image::make($image)->save($location);
                $product->size_chart = $img;
            }
            $product->save();

            if ($request->type == 'single') {
                $prodduct_stock = new ProductStock;
                if (!empty($request->code)) {
                    $prodduct_stock->code = $request->code;
                } else {
                    $prodduct_stock->code = $this->generateUniqueCode();
                }
                $prodduct_stock->product_id         = $product->id;
                $prodduct_stock->production_cost    = $request->production_cost;
                $prodduct_stock->price              = $request->price;
                $prodduct_stock->discount_price     = $request->discount_price;
                $prodduct_stock->wholesale_price    = $request->wholesale_price;
                $prodduct_stock->qty                = $request->qty;
                $prodduct_stock->save();

                $stock_history = new ProductStockHistory;
                $stock_history->product_id = $product->id;
                $stock_history->qty = $request->qty;
                $stock_history->note = 'Opening Stock';
                $stock_history->save();
            }

            if ($request->type == 'variation') {
                $i = 0;
                foreach ($request->sizes as $size) {
                    $exists = ProductStock::where('product_id', $product->id)->where('size_id', $size)->first();
                    if (is_null($exists)) {
                        $prodduct_stock = new ProductStock;
                        if (!empty($request->codes[$i])) {
                            $prodduct_stock->code = $request->codes[$i];
                        } else {
                            $prodduct_stock->code = $this->generateUniqueCode();
                        }

                        $prodduct_stock->product_id = $product->id;
                        $prodduct_stock->size_id = $request->sizes[$i];
                        $prodduct_stock->production_cost = $request->production_costs[$i];
                        $prodduct_stock->price = $request->prices[$i];
                        $prodduct_stock->discount_price = $request->discount_prices[$i];
                        $prodduct_stock->wholesale_price = $request->wholesale_prices[$i];
                        $prodduct_stock->qty = $request->qtys[$i];
                        $prodduct_stock->save();

                        $stock_history = new ProductStockHistory;
                        $stock_history->product_id = $product->id;
                        $stock_history->size_id = $request->sizes[$i];
                        $stock_history->qty = $request->qtys[$i];
                        $stock_history->note = 'Opening Stock';
                        $stock_history->save();
                    }

                    $i += 1;
                }
            }

            // check if any gallery image then save
            if (count($request->gallery) > 0) {
                $i = 0;
                foreach ($request->gallery as $gallery) {
                    $img = time() . $i . '.' . $gallery->getClientOriginalExtension();
                    $location = public_path('images/product/' . $img);
                    Image::make($gallery)->save($location);

                    $gallery = new ProductImage;
                    $gallery->image = $img;
                    $gallery->product_id = $product->id;
                    $gallery->save();
                    $i = $i + 1;
                }
            }

            Alert::toast('Product Added!', 'success');
            return redirect()->route('product.index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('product.edit')) {
            $product = Product::find($id);
            if (!is_null($product)) {
                $categories = Category::where('parent_id', 0)->orderBy('id', 'DESC')->get();
                $sub_categories = optional($product->category)->childs;
                $brands = Brand::orderBy('id', 'DESC')->get();
                $sizes = Size::orderBy('id', 'DESC')->get();
                return view('admin.product.edit', compact('product', 'categories', 'sub_categories', 'brands', 'sizes'));
            } else {
                Alert::toast('Product Not Found!', 'warning');
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (auth()->user()->can('product.edit')) {
            $product = Product::find($id);
            if (!is_null($product)) {
                $product->title = $request->title;
                $product->category_id = $request->category_id;
                $product->sub_category_id = $request->sub_category_id;
                $product->brand_id = $request->brand_id;
                $product->description = $request->description;
                $product->short_description = $request->short_description;
                $product->is_active = $request->is_active;
                $product->meta_description = $request->meta_description;

                // image save
                if ($request->image) {
                    if (File::exists('images/product/' . $product->image)) {
                        File::delete('images/product/' . $product->image);
                    }
                    if (File::exists('images/product/pos_images/' . $product->image)) {
                        File::delete('images/product/pos_images/' . $product->image);
                    }
                    $image = $request->file('image');
                    $img = time() . '.' . $image->getClientOriginalExtension();
                    $location_one = public_path('images/product/' . $img);
                    Image::make($image)->save($location_one);
                    $location_two = public_path('images/product/pos_images/' . $img);
                    Image::make($image)->resize(150, 200)->save($location_two);
                    $product->image = $img;
                }

                // size_chart save
                if ($request->size_chart) {
                    if (File::exists('images/product/' . $product->size_chart)) {
                        File::delete('images/product/' . $product->size_chart);
                    }
                    $image = $request->file('size_chart');
                    $img = 'size_chart_' . time() . '.' . $image->getClientOriginalExtension();
                    $location = public_path('images/product/' . $img);
                    Image::make($image)->save($location);
                    $product->size_chart = $img;
                }

                if ($request->has('is_featured')) {
                    $product->is_featured = 1;
                } else {
                    $product->is_featured = 0;
                }

                if ($request->has('is_trending')) {
                    $product->is_trending = 1;
                } else {
                    $product->is_trending = 0;
                }

                if ($request->has('is_offer')) {
                    $product->is_offer = 1;
                } else {
                    $product->is_offer = 0;
                }

                $product->save();

                if ($product->type == 'single') {
                    $prodduct_stock = ProductStock::find($request->variation_id);

                    $prodduct_stock->production_cost = $request->production_cost;
                    $prodduct_stock->price = $request->price;
                    $prodduct_stock->discount_price = $request->discount_price;
                    $prodduct_stock->wholesale_price = $request->wholesale_price;
                    $prodduct_stock->save();

                    $stock_history = new ProductStockHistory;
                    $stock_history->product_id = $product->id;
                    $stock_history->qty = $request->qty;
                    $stock_history->note = 'Opening Stock';
                    $stock_history->save();
                }

                if ($product->type == 'variation') {
                    $i = 0;
                    foreach ($request->sizes as $size) {
                        $exists = ProductStock::where('id', '!=', $request->variation_ids[$i])->where('product_id', $product->id)->where('size_id', $size)->first();
                        if (is_null($exists)) {
                            $prodduct_stock = ProductStock::find($request->variation_ids[$i]);
                            $prodduct_stock->size_id = $request->sizes[$i];
                            $prodduct_stock->production_cost = $request->production_costs[$i];
                            $prodduct_stock->price = $request->prices[$i];
                            $prodduct_stock->discount_price = $request->discount_prices[$i];
                            $prodduct_stock->wholesale_price = $request->wholesale_prices[$i];
                            $prodduct_stock->save();
                        }


                        $i += 1;
                    }
                }
                // check if any gallery image then save
                if ($request->has('gallery')) {
                    if (count($request->gallery) > 0) {

                        $i = 0;
                        foreach ($request->gallery as $gallery) {
                            $img = time() . $i . '.' . $gallery->getClientOriginalExtension();
                            $location = public_path('images/product/' . $img);
                            Image::make($gallery)->save($location);

                            $gallery = new ProductImage;
                            $gallery->image = $img;
                            $gallery->product_id = $product->id;
                            $gallery->save();
                            $i = $i + 1;
                        }
                    }
                }

                Alert::toast('Product Updated!', 'success');
                return redirect()->route('product.index');
            } else {
                Alert::toast('Product Not Found!', 'warning');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('product.delete')) {
            $product = Product::find($id);
            if (!is_null($product)) {
                if (!OrderProduct::where('product_id', $id)->exists()) {
    
                    if (File::exists('images/product/' . $product->image)) {
                        File::delete('images/product/' . $product->image);
                    }
                    if (File::exists('images/product/pos_images/' . $product->image)) {
                        File::delete('images/product/pos_images/' . $product->image);
                    }
                    if (File::exists('images/product/' . $product->size_chart)) {
                        File::delete('images/product/' . $product->size_chart);
                    }
                    $product_gallery_images = ProductImage::where('product_id', $id)->get();

                    foreach ($product_gallery_images as $key => $gallery_image) {
                        if (!is_null($gallery_image)) {
                            if (File::exists('images/product/' . $gallery_image->image)) {
                                File::delete('images/product/' . $gallery_image->image);
                            }
                            $gallery_image->delete();
                        }
                    }

                    if ($product->type == 'single') {
                        $product_stock = ProductStock::find($product->variation->id);

                        $product_stock->delete();
                    }

                    if ($product->type == 'variation') {
                        $exists = ProductStock::where('product_id', $product->id)->get();
                        if (!is_null($exists)) {
                            $prodduct_stock = ProductStock::where('product_id', $product->id);
                            $prodduct_stock->delete();
                        }
                    }

                    $product->delete();

                    Alert::toast('Product Deleted!', 'success');
                    return redirect()->route('product.index');
                } else {
                    Alert::toast('Sorry! There is orders containing this product.', 'warning');
                    return back();
                }

            } else {
                Alert::toast('Product Not Found!', 'warning');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function gallery_destroy($id) {
        if (auth()->user()->can('product.edit')) {
            $image = ProductImage::find($id);
            if (!is_null($image)) {
                if (File::exists('images/product/' . $image->image)) {
                    File::delete('images/product/' . $image->image);
                }
                $image->delete();
                Alert::toast('Image deleted', 'success');
                return back();
            } else {
                Alert::toast('Image Not Found!', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function variation_store(Request $request, $id) {
        if (auth()->user()->can('product.edit')) {
            $product = Product::find($id);
            if (!is_null($product)) {
                $exists = ProductStock::where('product_id', $product->id)->where('size_id', $request->size_id)->first();
                if (is_null($exists)) {
                    $prodduct_stock = new ProductStock;
                    if (!empty($request->code)) {
                        $prodduct_stock->code = $request->code;
                    } else {
                        $prodduct_stock->code = $this->generateUniqueCode();
                    }
                    $prodduct_stock->product_id = $product->id;
                    $prodduct_stock->size_id = $request->size_id;
                    $prodduct_stock->production_cost = $request->production_cost;
                    $prodduct_stock->price = $request->price;
                    $prodduct_stock->discount_price = $request->discount_price;
                    $prodduct_stock->wholesale_price = $request->wholesale_price;
                    $prodduct_stock->qty = $request->qty;
                    $prodduct_stock->save();

                    $stock_history = new ProductStockHistory;
                    $stock_history->product_id = $product->id;
                    $stock_history->size_id = $request->size_id;
                    $stock_history->qty = $request->qty;
                    $stock_history->note = 'Opening Stock';
                    $stock_history->save();
                    Alert::toast('New variation added.', 'success');
                    return back();
                } else {
                    Alert::toast('This variation is already exists.', 'warning');
                    return back();
                }
            } else {
                Alert::toast('Product Not Found!', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
