<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\BkashNumber;
use App\Models\BkashRecord;
use App\Models\BkashRecordPurpose;
use App\Models\WorkTrackingEntry;
use App\Models\User;
use Carbon\Carbon;
use DataTables;

class BkashRecordController extends Controller {
    public function index(Request $request) {
        $bkash_business_id = '';
        $date_from = '';
        $date_to = '';
        $bkash_nums = BkashNumber::all();
        $bkash_purposes = BkashRecordPurpose::all();
        $bkash_records = BkashRecord::with('bkash_business', 'bkash_purpose', 'created_by')->latest();
        $cash_in = 0;
        $cash_out = 0;
        $send_money = 0;
        $payments = 0;
        $recharge = 0;
        $current_balance = 0;
        $bkash_number = null;

        if (auth()->user()->can('business_bkash_number.index')) {
            if ($request->ajax()) {
                return Datatables::of($bkash_records)
                    // ->addIndexColumn()
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->addColumn('business_bkash_number', function ($row) {

                        $data = $row->bkash_business->number . ' (' . $row->bkash_business->name . ')';

                        return $data;
                    })
                    ->addColumn('purpose', function ($row) {

                        $data = '<span class="badge badge-' . $row->bkash_purpose->color . '">' . $row->bkash_purpose->title . '</span>';

                        return $data;
                    })
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                            $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' . $row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by')
                    ->rawColumns(['business_bkash_number', 'purpose', 'date'])
                    ->make(true);
            }
            return view('admin.bkash-panel.index', compact('bkash_records', 'bkash_nums', 'bkash_purposes', 'bkash_business_id', 'date_from', 'date_to', 'current_balance', 'cash_in', 'cash_out', 'send_money', 'payments', 'recharge', 'bkash_number'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function transactions_search(Request $request) {
        $bkash_business_id = '';
        $date_from = '';
        $date_to = '';
        $bkash_nums = BkashNumber::all();
        $bkash_purposes = BkashRecordPurpose::all();
        $bkash_records = BkashRecord::with('bkash_business', 'bkash_purpose', 'created_by')->latest();
        $cash_in = 0;
        $cash_out = 0;
        $send_money = 0;
        $payments = 0;
        $recharge = 0;
        $current_balance = 0;
        $bkash_number = null;
        $start_date = '';
        $end_date = '';

        if (auth()->user()->can('business_bkash_number.index')) {
            if (!empty($request->bkash_business_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $bkash_business_id = $request->bkash_business_id;

                $bkash_records = BkashRecord::where('bkash_business_id', $bkash_business_id)
                    ->whereBetween('created_at', [$start_date, $end_date])->with('bkash_business', 'bkash_purpose', 'created_by')->latest();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if ((!empty($request->bkash_business_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->bkash_business_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->bkash_business_id) && empty($request->date_from) && !empty($request->date_to))) {

                $bkash_business_id = $request->bkash_business_id;

                $bkash_records = BkashRecord::where('bkash_business_id', $bkash_business_id)->with('bkash_business', 'bkash_purpose', 'created_by')->latest();
            }
            if (empty($request->bkash_business_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $bkash_records = BkashRecord::whereBetween('created_at', [$start_date, $end_date])->with('bkash_business', 'bkash_purpose', 'created_by')->latest();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }

            if ($request->ajax()) {
                return Datatables::of($bkash_records)
                    // ->addIndexColumn()
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->addColumn('business_bkash_number', function ($row) {

                        $data = $row->bkash_business->number . ' (' . $row->bkash_business->name . ')';

                        return $data;
                    })
                    ->addColumn('purpose', function ($row) {

                        $data = '<span class="badge badge-' . $row->bkash_purpose->color . '">' . $row->bkash_purpose->title . '</span>';

                        return $data;
                    })
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                            $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' . $row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by')
                    ->rawColumns(['business_bkash_number', 'purpose', 'date'])
                    ->make(true);
            }

            if ($bkash_business_id != '' && ($date_from == '' || $date_to == '')) {
                $bkash_number = BkashNumber::find($bkash_business_id);
                $cash_in = BkashRecord::where('bkash_business_id', $bkash_business_id)->where('tr_type', 'CASH IN')->sum('amount');
                $cash_out = BkashRecord::where('bkash_business_id', $bkash_business_id)->where('tr_type', 'CASH OUT')->sum('amount');
                $send_money = BkashRecord::where('bkash_business_id', $bkash_business_id)->where('tr_type', 'SEND MONEY')->sum('amount');
                $payments = BkashRecord::where('bkash_business_id', $bkash_business_id)->where('tr_type', 'PAYMENTS')->sum('amount');
                $recharge = BkashRecord::where('bkash_business_id', $bkash_business_id)->where('tr_type', 'RECHARGE')->sum('amount');
                $current_balance = $bkash_number->current_balance();
            } elseif ($bkash_business_id != '' && $date_from != '' && $date_to != '') {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $date_to . ' 23:59:59');

                $bkash_number = BkashNumber::find($bkash_business_id);
                $cash_in = BkashRecord::where('bkash_business_id', $bkash_business_id)->whereBetween('created_at', [$start_date, $end_date])->where('tr_type', 'CASH IN')->sum('amount');
                $cash_out = BkashRecord::where('bkash_business_id', $bkash_business_id)->whereBetween('created_at', [$start_date, $end_date])->where('tr_type', 'CASH OUT')->sum('amount');
                $send_money = BkashRecord::where('bkash_business_id', $bkash_business_id)->whereBetween('created_at', [$start_date, $end_date])->where('tr_type', 'SEND MONEY')->sum('amount');
                $payments = BkashRecord::where('bkash_business_id', $bkash_business_id)->whereBetween('created_at', [$start_date, $end_date])->where('tr_type', 'PAYMENTS')->sum('amount');
                $recharge = BkashRecord::where('bkash_business_id', $bkash_business_id)->whereBetween('created_at', [$start_date, $end_date])->where('tr_type', 'RECHARGE')->sum('amount');
                $current_balance = $bkash_number->current_balance();
            }
            return view('admin.bkash-panel.index', compact('bkash_records', 'bkash_nums', 'bkash_purposes', 'bkash_business_id', 'date_from', 'date_to', 'current_balance', 'cash_in', 'cash_out', 'send_money', 'payments', 'recharge', 'bkash_number'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function create() {
        if (auth()->user()->can('business_bkash_number.index')) {
            $bkash_nums = BkashNumber::all();
            $bkash_purposes = BkashRecordPurpose::all();

            return view('admin.bkash-panel.create', compact('bkash_nums', 'bkash_purposes'));
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
                'bkash_business_id' => 'required|not_in:0',
                'tr_type' => 'required|not_in:0',
                'amount' => 'required',
                'tr_purpose_id' => 'required|not_in:0',
                'last_digit' => 'required',
            ]);

            $bkash_record = new BkashRecord;
            $bkash_record->bkash_business_id = $request->bkash_business_id;
            $bkash_record->tr_type = $request->tr_type;
            $bkash_record->amount = $request->amount;
            $bkash_record->tr_purpose_id = $request->tr_purpose_id;
            $bkash_record->last_digit = $request->last_digit;
            $bkash_record->comments = $request->comments;
            $bkash_record->save();

            WorkTrackingEntry::create([
                'bkash_record_id' => $bkash_record->id,
                'user_id' => Auth::id(),
                'work_name' => 'bkash_record'
            ]);
            Alert::toast('New Bkash Record Added!', 'success');
            // return redirect()->route('bkash_panel.create');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
