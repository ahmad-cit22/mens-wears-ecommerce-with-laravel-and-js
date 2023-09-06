<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\BkashNumber;
use Image;
use File;

class BkashNumberController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('business_bkash_number.index')) {
            $numbers = BkashNumber::all();
            return view('admin.order.order_sheet.bkash_number.index', compact('numbers'));
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
        if (auth()->user()->can('business_bkash_number.index')) {
            $validatedData = $request->validate([
                'number' => 'required',
            ]);

            $number = new BkashNumber;
            $number->name = $request->name;
            $number->number = $request->number;

            $number->save();
            Alert::toast('New Bkash Number Added !', 'success');
            return redirect()->route('fos.bkash_number.index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (auth()->user()->can('business_bkash_number.index')) {
            $validatedData = $request->validate([
                'number' => 'required',
            ]);

            $number = BkashNumber::find($id);


            if (!is_null($number)) {
                $number->name = $request->name;
                $number->number = $request->number;

                $number->save();
                Alert::toast('Bkash Number has been updated !', 'success');
                return redirect()->route('fos.bkash_number.index');
            } else {
                Alert::toast('Bkash Number Not Found !', 'warning');
                return redirect()->route('fos.bkash_number.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('business_bkash_number.index')) {
            $number = BkashNumber::find($id);

            if (!is_null($number)) {
                $number->delete();
                Alert::toast('Bkash Number has been deleted !', 'success');
                return redirect()->route('fos.bkash_number.index');
            } else {
                Alert::toast('Bkash Number Not Found !', 'warning');
                return redirect()->route('fos.bkash_number.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
