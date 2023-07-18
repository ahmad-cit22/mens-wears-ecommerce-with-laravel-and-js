<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\AccessoryAmount;
use Illuminate\Http\Request;
use Auth;
use Alert;

class AccessoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('setting.index')) {
            $accessories = Accessory::all();
            return view('admin.accessory.index', compact('accessories'));
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
        if (auth()->user()->can('setting.create')) {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'opening_amount' => 'required|numeric',
            ]);

            $accessory = new Accessory;
            $accessory->name = $request->name;
            $accessory->save();

            $stock = new AccessoryAmount;
            $stock->accessory_id = $accessory->id;
            $stock->credit = $request->opening_amount;
            $stock->note = 'Opening Balance';
            $stock->save();
            Alert::toast('Accessory has been saved.', 'success');
            return back();
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function show(Accessory $accessory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function edit(Accessory $accessory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('setting.edit')) {
            $accessory = Accessory::find($id);
            if (!is_null($accessory)) {
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);

                $accessory->name = $request->name;
                $accessory->save();
                Alert::toast('Accessory Updated', 'success');
                return back();
            }
            else {
                Alert::toast('Accessory Not Found!', 'success');
                return back();
            }
            return view('admin.accessory.index', compact('accessories'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Accessory $accessory)
    {
        //
    }
}
