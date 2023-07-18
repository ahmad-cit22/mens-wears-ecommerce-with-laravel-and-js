<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Auth;
use Alert;
use Image;
use File;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('size.index')) {
            $sizes = Size::all();
            return view('admin.size.index', compact('sizes'));
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
        if (auth()->user()->can('size.create')) {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
            ]);
            $size = new size;
            $size->title = $request->title;
            
            $size->save();
            Alert::toast('One size Added !', 'success');
            return redirect()->route('size.index');
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit(size $size)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('size.edit')) {
            $this->validate($request, [
                'title' => 'required',
            ]);

            $size = size::find($id);

            if (!is_null($size)) {
                $size->title = $request->title;

                $size->save();
                Alert::toast('Size has been updated !', 'success');
                return redirect()->route('size.index');
            }
            else{
                Alert::toast('Size Not Found !', 'warning');
                return redirect()->route('size.index');
            }
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->can('size.create')) {
            $size = size::find($id);

            if (!is_null($size)) {
                $size->delete();
                Alert::toast('Size has been deleted !', 'success');
                return redirect()->route('size.index');
            }
            else{
                Alert::toast('Size Not Found !', 'warning');
                return redirect()->route('size.index');
            }
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }
}
