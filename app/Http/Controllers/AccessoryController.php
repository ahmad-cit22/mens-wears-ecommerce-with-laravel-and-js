<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\AccessoryAmount;
use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\Bank;

class AccessoryController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('setting.accessory')) {
            if (!Auth::user()->vendor) {
                $banks = Bank::where('vendor_id', null)->get();
                $accessories = Accessory::where('vendor_id', null)->get();
            } else {
                $banks = Bank::where('vendor_id', Auth::user()->vendor->id)->get();
                $accessories = Accessory::where('vendor_id', Auth::user()->vendor->id)->get();
            }
            return view('admin.accessory.index', compact('accessories', 'banks'));
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
        if (auth()->user()->can('setting.accessory')) {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'opening_amount' => 'required|numeric',
            ]);

            $accessory = new Accessory;
            $accessory->name = $request->name;
            $accessory->min_quantity = $request->min_quantity;
            if (Auth::user()->vendor) {
                $accessory->vendor_id = Auth::user()->vendor->id;
            }
            $accessory->save();

            $stock = new AccessoryAmount;
            $stock->accessory_id = $accessory->id;
            $stock->bank_id = $request->bank_id;
            $stock->credit = $request->opening_amount;
            $stock->note = $request->note;
            if (Auth::user()->vendor) {
                $accessory->vendor_id = Auth::user()->vendor->id;
            }
            $stock->save();
            Alert::toast('Accessory has been saved.', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function show(Accessory $accessory) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function edit(Accessory $accessory) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (auth()->user()->can('setting.accessory')) {
            $accessory = Accessory::find($id);
            if (!is_null($accessory)) {
                $validatedData = $request->validate([
                    'name' => 'required|max:255',
                ]);

                $accessory->name = $request->name;
                $accessory->min_quantity = $request->min_quantity;
                $accessory->save();
                Alert::toast('Accessory Updated', 'success');
                return back();
            } else {
                Alert::toast('Accessory Not Found!', 'success');
                return back();
            }
            return view('admin.accessory.index', compact('accessories'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('setting.accessory')) {
            $accessory = Accessory::find($id);
            if (!is_null($accessory)) {
                foreach ($accessory->stock as $stock) {
                    $stock->delete();
                }

                $accessory->delete();
                Alert::toast('Accessory Item deleted successfully!', 'success');
                return redirect()->route('accessory.index');
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
