<?php

namespace App\Http\Controllers;

use App\Models\Trending;
use Illuminate\Http\Request;
use Auth;
use Alert;
use Image;
use File;

class TrendingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('setting.index')) {
            $trending = Trending::orderBy('id', 'DESC')->first();
            return view('admin.trending.index', compact('trending'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trending  $trending
     * @return \Illuminate\Http\Response
     */
    public function show(Trending $trending)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Trending  $trending
     * @return \Illuminate\Http\Response
     */
    public function edit(Trending $trending)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trending  $trending
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('setting.edit')) {
            $this->validate($request, [
                'link' => 'nullable',
                'type' => 'required',
                'image'=> 'nullable',
            ]);

            $trending = Trending::find($id);
            
            $trending->type = $request->type;
            $trending->link = $request->link;

            // logo save
            if ($request->image){
                if (File::exists('images/website/'.$trending->image)){
                    File::delete('images/website/'.$trending->image);
                }
                $image = $request->file('image');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/website/'. $img);
                Image::make($image)->save($location);
                $trending->image = $img;
            }

            $trending->save();
            Alert::toast('Trending settings has been updated !', 'success');
            return back();
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trending  $trending
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trending $trending)
    {
        //
    }
}
