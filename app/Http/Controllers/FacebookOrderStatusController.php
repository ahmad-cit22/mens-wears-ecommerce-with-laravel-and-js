<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\FacebookOrder;
use App\Models\FacebookOrderStatus;
use Image;
use File;

class FacebookOrderStatusController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('order_sheet_status.index')) {
            $statuses = FacebookOrderStatus::all();
            return view('admin.order.order_sheet.status.index', compact('statuses'));
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

            $status = new FacebookOrderStatus;
            $status->title = $request->title;
            $status->color = $request->color;

            $status->save();
            Alert::toast('New Status Added !', 'success');
            return redirect()->route('fos.status.index');
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

            $status = FacebookOrderStatus::find($id);


            if (!is_null($status)) {
                $status->title = $request->title;
                $status->color = $request->color;

                $status->save();
                Alert::toast('Status has been updated !', 'success');
                return redirect()->route('fos.status.index');
            } else {
                Alert::toast('Status Not Found !', 'warning');
                return redirect()->route('fos.status.index');
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
            $status = FacebookOrderStatus::find($id);

            if (FacebookOrder::where('order_status_id', $id)->exists()) {
                Alert::toast("Status can't be deleted because there is orders under this status !", 'warning');
                return redirect()->route('fos.status.index');
            };

            if (!is_null($status)) {
                $status->delete();
                Alert::toast('Status has been deleted !', 'success');
                return redirect()->route('fos.status.index');
            } else {
                Alert::toast('Status Not Found !', 'warning');
                return redirect()->route('fos.status.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
