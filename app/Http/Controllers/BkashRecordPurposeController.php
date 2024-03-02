<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\FacebookOrder;
use App\Models\BkashRecord;
use App\Models\BkashRecordPurpose;
use Image;
use File;

class BkashRecordPurposeController extends Controller
{

    public function index(Request $request) {
        $business_bkash_number = '';
        $month = '';
        $bkash_nums = BkashNumber::all();
        $bkash_purposes = BkashRecordPurpose::all();

        if (auth()->user()->can('business_bkash_number.index')) {
            $bkash_records = BkashRecord::orderBy('id', 'DESC')->with('bkash_business', 'bkash_purpose', 'created_by')->get();
            if ($request->ajax()) {
                return Datatables::of($bkash_records)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('order.edit', $row->id) . '">' . $row->code . '</a>';

                        return $code;
                    })
                    // ->addColumn('tr_type', function ($row) {

                    //     if ($row->tr_type == '1') {
                    //         $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span> <br> <span class="badge badge-danger">Returned</span>';
                    //     } elseif ($row->is_return == 2) {
                    //         $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span> <br> <span class="badge badge-danger">Returned Partially</span>';
                    //     } else {
                    //     }
                    //     $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';

                    //     return $data;
                    // })
                    ->addColumn('purpose', function ($row) {

                        $data = '<span class="badge badge-' . $row->bkash_purpose->color . '">' . $row->bkash_purpose->title . '</span>';

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                           $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' .$row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by')
                    ->addColumn('action', function ($row) {
                            $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                               <a href="' . route('order.invoice.pos.generate', $row->id) . '" class="btn btn-success" title="Print Invoice"><i class="fas fa-print"></i></a>
                          <a href="' . route('order.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['purpose', 'date', 'action'])
                    ->make(true);
            }
            return view('admin.bkash-panel.index', compact('bkash_records', 'bkash_nums', 'bkash_purposes'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function tr_purposes() {
        if (auth()->user()->can('business_bkash_number.index')) {
            $purposes = BkashRecordPurpose::all();
            return view('admin.bkash-panel.tr-purpose.index', compact('purposes'));
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
                'title' => 'required|max:255',
                'color' => 'required|not_in:0',
            ]);

            $purpose = new BkashRecordPurpose;
            $purpose->title = $request->title;
            $purpose->color = $request->color;

            $purpose->save();
            Alert::toast('New Purpose Added !', 'success');
            return redirect()->route('bkash_panel.tr_purposes');
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
                'title' => 'required|max:255',
                'color' => 'required|not_in:0',
            ]);

            $purpose = BkashRecordPurpose::find($id);


            if (!is_null($purpose)) {
                $purpose->title = $request->title;
                $purpose->color = $request->color;

                $purpose->save();
                Alert::toast('Purpose has been updated!', 'success');
                return redirect()->route('bkash_panel.tr_purposes');
            } else {
                Alert::toast('Purpose Not Found !', 'warning');
                return redirect()->route('bkash_panel.tr_purposes');
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
            $purpose = BkashRecordPurpose::find($id);

            if (BkashRecord::where('tr_purpose_id', $id)->exists()) {
                Alert::toast("Purpose can't be deleted because there is bkash records under this purpose!", 'warning');
                return redirect()->route('bkash_panel.tr_purposes');
            };

            if (!is_null($purpose)) {
                $purpose->delete();
                Alert::toast('Purpose has been deleted !', 'success');
                return redirect()->route('bkash_panel.tr_purposes');
            } else {
                Alert::toast('Purpose Not Found !', 'warning');
                return redirect()->route('bkash_panel.tr_purposes');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
