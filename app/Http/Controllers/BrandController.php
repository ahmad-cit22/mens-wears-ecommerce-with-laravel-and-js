<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Auth;
use Alert;
use Image;
use File;

class BrandController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('brand.index')) {
            $brands = Brand::all();
            return view('admin.brand.index', compact('brands'));
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
        if (auth()->user()->can('brand.create')) {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'image' => 'nullable',
            ]);
            $brand = new Brand;
            $brand->title = $request->title;
            $brand->meta_description = $request->meta_description;
            // image save
            if ($request->image) {
                $image = $request->file('image');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/brand/' . $img);
                Image::make($image)->save($location);
                $brand->image = $img;
            }
            $brand->save();
            Alert::toast('One Brand Added !', 'success');
            return redirect()->route('brand.index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (auth()->user()->can('brand.edit')) {
            $this->validate($request, [
                'title' => 'required',
                'image' => 'nullable',
            ]);

            $brand = Brand::find($id);

            if (!is_null($brand)) {
                $brand->title = $request->title;
                $brand->meta_description = $request->meta_description;

                // image save
                if ($request->image) {
                    if (File::exists('images/brand/' . $brand->image)) {
                        File::delete('images/brand/' . $brand->image);
                    }
                    $image = $request->file('image');
                    $img = time() . '.' . $image->getClientOriginalExtension();
                    $location = public_path('images/brand/' . $img);
                    Image::make($image)->save($location);
                    $brand->image = $img;
                }

                $brand->save();
                Alert::toast('Brand has been updated !', 'success');
                return redirect()->route('brand.index');
            } else {
                Alert::toast('Brand Not Found !', 'warning');
                return redirect()->route('brand.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('brand.create')) {
            $brand = Brand::find($id);

            if (!is_null($brand)) {
                if (File::exists('images/brand/' . $brand->image)) {
                    File::delete('images/brand/' . $brand->image);
                }
                $brand->delete();
                Alert::toast('Brand has been deleted !', 'success');
                return redirect()->route('brand.index');
            } else {
                Alert::toast('Brand Not Found !', 'warning');
                return redirect()->route('brand.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
