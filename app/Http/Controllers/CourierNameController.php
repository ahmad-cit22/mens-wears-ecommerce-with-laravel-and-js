<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\CourierName;
use App\Models\FacebookOrder;
use Image;
use File;

class CourierNameController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('courier_name.index')) {
            $couriers = CourierName::all();
            return view('admin.order.order_sheet.courier_name.index', compact('couriers'));
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
        if (auth()->user()->can('courier_name.index')) {
            $validatedData = $request->validate([
                'name' => 'required',
            ]);

            $courier = new CourierName;
            $courier->name = $request->name;
            $courier->charge_one = $request->charge_one;
            $courier->charge_two = $request->charge_two;

            $courier->save();
            Alert::toast('New Courier Info Added !', 'success');
            return redirect()->route('fos.courier_name.index');
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
        if (auth()->user()->can('courier_name.index')) {
            $validatedData = $request->validate([
                'name' => 'required',
            ]);

            $courier = CourierName::find($id);


            if (!is_null($courier)) {
                $courier->name = $request->name;
                $courier->charge_one = $request->charge_one;
                $courier->charge_two = $request->charge_two;

                $courier->save();
                
                Alert::toast('Courier Info has been updated !', 'success');
                return redirect()->route('fos.courier_name.index');
            } else {
                Alert::toast('Courier Info Not Found!', 'warning');
                return redirect()->route('fos.courier_name.index');
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
        if (auth()->user()->can('courier_name.index')) {
            $courier = CourierName::find($id);

            if (FacebookOrder::where('courier_id', $id)->exists()) {
                Alert::toast("Courier can't be deleted because there is orders under this courier !", 'warning');
                return redirect()->route('fos.courier_name.index');
            };

            if (!is_null($courier)) {
                $courier->delete();
                Alert::toast('Courier Info has been deleted !', 'success');
                return redirect()->route('fos.courier_name.index');
            } else {
                Alert::toast('Courier Info Not Found !', 'warning');
                return redirect()->route('fos.courier_name.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
