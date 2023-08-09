<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Auth;
use Alert;
use Image;
use File;

class CategoryController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('category.index')) {
            $categories = Category::all();
            return view('admin.category.index', compact('categories'));
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
        if (auth()->user()->can('category.create')) {
            $categories = Category::where('parent_id', 0)->get();
            return view('admin.category.create', compact('categories'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (auth()->user()->can('category.create')) {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'parent_id' => 'nullable|integer',
                'position' => 'nullable|integer',
                'image' => 'nullable',
                'banner' => 'nullable',
            ]);
            //dd($request->all());
            $category = new Category;
            $category->title = $request->title;
            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;
            $category->meta_keywords = $request->meta_keywords;
            if ($request->position != NULL) {
                $category->position = $request->position;
            }
            if ($request->has('parent_id')) {
                $category->parent_id = $request->parent_id;
            }
            // image save
            if ($request->image) {
                $image = $request->file('image');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/category/' . $img);
                Image::make($image)->save($location);
                $category->image = $img;
            }

            // banner save
            if ($request->banner) {
                $image = $request->file('banner');
                $img = 'banner_' . time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/category/' . $img);
                Image::make($image)->save($location);
                $category->banner = $img;
            }

            $category->save();
            Alert::toast('Category added successfully!', 'success');
            return redirect()->route('category.index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('category.edit')) {
            $category = Category::find($id);
            if (!is_null($category)) {
                $categories = Category::where('parent_id', 0)->get();
                return view('admin.category.edit', compact('category', 'categories'));
            } else {
                Alert::toast(__('app.messages.category.not_found'), 'error');
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (auth()->user()->can('category.edit')) {
            $category = Category::find($id);
            if (!is_null($category)) {
                $this->validate(
                    $request,
                    [
                        'title' => 'required',
                        'parent_id' => 'nullable|integer',
                        'position' => 'nullable|integer',
                        'image' => 'nullable',
                        'banner' => 'nullable',
                    ],
                    [
                        'title.required' => 'Please provide a category name',
                    ]
                );

                $category->title = $request->title;
                $category->position = $request->position;
                $category->is_active = $request->status;
                $category->meta_title = $request->meta_title;
                $category->meta_description = $request->meta_description;
                $category->meta_keywords = $request->meta_keywords;

                if ($request->has('parent_id')) {
                    $category->parent_id = $request->parent_id;
                }

                // image save
                if ($request->image) {
                    if (File::exists('images/category/' . $category->image)) {
                        File::delete('images/category/' . $category->image);
                    }
                    $image = $request->file('image');
                    $img = time() . '.' . $image->getClientOriginalExtension();
                    $location = public_path('images/category/' . $img);
                    Image::make($image)->save($location);
                    $category->image = $img;
                }
                // banner save
                if ($request->banner) {
                    if (File::exists('images/category/' . $category->banner)) {
                        File::delete('images/category/' . $category->banner);
                    }
                    $image = $request->file('banner');
                    $img = 'banner_' . time() . '.' . $image->getClientOriginalExtension();
                    $location = public_path('images/category/' . $img);
                    Image::make($image)->save($location);
                    $category->banner = $img;
                }
                if ($request->has('is_featured')) {
                    $category->is_featured = 1;
                } else {
                    $category->is_featured = 0;
                }

                $category->save();
                Alert::toast(__('app.messages.category.update'), 'success');
                return redirect()->route('category.index');
            } else {
                Alert::toast(__('app.messages.category.not_found'), 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('category.delete')) {
            $category = Category::find($id);
            if (!is_null($category)) {
                if (File::exists('images/category/' . $category->image)) {
                    File::delete('images/category/' . $category->image);
                }
                if (File::exists('images/category/' . $category->banner)) {
                    File::delete('images/category/' . $category->banner);
                }
                $category->delete();
                Alert::toast(__('app.messages.category.delete'), 'success');
                return back();
            } else {
                Alert::toast(__('app.messages.category.not_found'), 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
