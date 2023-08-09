<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Auth;
use Image;
use File;
use Alert;

class AdminPageController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('page.index')) {
            $pages = Page::all();
            return view('admin.page.index', compact('pages'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('page.edit')) {
            $page = Page::find($id);
            if (!is_null($page)) {
                return view('admin.page.edit', compact('page'));
            } else {
                Alert::toast('Something went wrong !', 'error');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $page = Page::find($id);
        if (!is_null($page)) {
            $page->name = $request->name;
            $page->description = $request->description;
            $page->meta_description = $request->meta_description;
            $page->description2 = $request->description2;
            $page->meta_keywords = $request->meta_keywords;

            // image save
            if ($request->image) {
                if (File::exists('images/website/' . $page->image)) {
                    File::delete('images/website/' . $page->image);
                }
                $image = $request->file('image');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/website/' . $img);
                Image::make($image)->save($location);
                $page->image = $img;
            }

            // new_arrival save
            if ($request->new_arrival) {
                if (File::exists('images/website/' . $page->new_arrival)) {
                    File::delete('images/website/' . $page->new_arrival);
                }
                $image = $request->file('new_arrival');
                $img = 'new_arrival' . time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/website/' . $img);
                Image::make($image)->save($location);
                $page->new_arrival = $img;
            }

            // product_banner save
            if ($request->product_banner) {
                if (File::exists('images/website/' . $page->product_banner)) {
                    File::delete('images/website/' . $page->product_banner);
                }
                $image = $request->file('product_banner');
                $img = 'product_banner' . time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/website/' . $img);
                Image::make($image)->save($location);
                $page->product_banner = $img;
            }

            // image save
            if ($request->advertisement) {
                if (File::exists('images/website/' . $page->advertisement)) {
                    File::delete('images/website/' . $page->advertisement);
                }
                $image = $request->file('advertisement');
                $img = 'advertisement' . time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/website/' . $img);
                Image::make($image)->save($location);
                $page->advertisement = $img;
            }

            $page->save();

            Alert::toast('Page Updated', 'success');
            return redirect()->route('page.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
