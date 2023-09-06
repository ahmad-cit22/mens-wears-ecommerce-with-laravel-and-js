<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\FacebookOrder;
use App\Models\OrderSpecialStatus;
use Image;
use File;

class OrderSpecialStatusController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('order_sheet_status.index')) {
            $statuses = OrderSpecialStatus::all();
            return view('admin.order.order_sheet.special_status.index', compact('statuses'));
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
        if (auth()->user()->can('order_sheet_status.index')) {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'color' => 'required|not_in:0',
            ]);

            $status = new OrderSpecialStatus;
            $status->title = $request->title;
            $status->color = $request->color;
            $status->related_field = $request->related_field;

            $status->save();
            Alert::toast('New Special Status Added !', 'success');
            return redirect()->route('fos.special_status.index');
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
        if (auth()->user()->can('order_sheet_status.index')) {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'color' => 'required|not_in:0',
            ]);

            $status = OrderSpecialStatus::find($id);


            if (!is_null($status)) {
                $status->title = $request->title;
                $status->color = $request->color;
                $status->related_field = $request->related_field;

                $status->save();
                Alert::toast('Special Status has been updated !', 'success');
                return redirect()->route('fos.special_status.index');
            } else {
                Alert::toast('Special Status Not Found !', 'warning');
                return redirect()->route('fos.special_status.index');
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
        if (auth()->user()->can('order_sheet_status.index')) {
            $status = OrderSpecialStatus::find($id);

            if (FacebookOrder::where('special_status_id', $id)->exists()) {
                Alert::toast("Status can't be deleted because there is orders under this status !", 'warning');
                return redirect()->route('fos.special_status.index');
            };

            if (!is_null($status)) {
                $status->delete();
                Alert::toast('Special Status has been deleted !', 'success');
                return redirect()->route('fos.special_status.index');
            } else {
                Alert::toast('Special Status Not Found !', 'warning');
                return redirect()->route('fos.special_status.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
