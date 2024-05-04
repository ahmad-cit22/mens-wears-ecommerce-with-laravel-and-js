<?php

namespace App\Http\Controllers;

use App\Models\BankContra;
use App\Models\BankTransaction;
use App\Models\Bank;
use Illuminate\Http\Request;

use Alert;
use Auth;
use Carbon\Carbon;
use DataTables;

class BankContraController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if (auth()->user()->can('bank.index')) {
            if (!Auth::user()->vendor) {
                $banks = Bank::where('vendor_id', null)->get();
                $data = BankContra::where('vendor_id', null)->orderBy('id', 'DESC')->get();
            } else {
                $banks = Bank::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->get();
                $data = BankContra::orderBy('id', 'DESC')->where('vendor_id', Auth::user()->vendor->id)->get();
            }
            if ($request->ajax()) {
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('from', function ($row) {

                        $data = optional($row->from)->name;

                        return $data;
                    })
                    ->addColumn('to', function ($row) {

                        $data = optional($row->to)->name;

                        return $data;
                    })
                    ->addColumn('transaction_date', function ($row) {

                        $data = Carbon::parse($row->date)->format('d M, Y');

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->rawColumns(['from', 'to', 'date'])
                    ->make(true);
            }
            return view('admin.bank.contra.index', compact('banks'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request) {
        if (auth()->user()->can('bank.index')) {
            if (!Auth::user()->vendor) {
                $banks = Bank::where('vendor_id', null)->get();
            } else {
                $banks = Bank::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->get();
            }
            if ($request->ajax()) {
                if (!empty($request->date_from) && !empty($request->date_to)) {
                    $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                    $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                    $data = BankContra::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
                } else {
                    $data = BankContra::orderBy('id', 'DESC')->get();
                }

                if (Auth::user()->vendor) {
                    $data = $data->where('vendor_id', Auth::user()->vendor->id);
                } else {
                    $data = $data->where('vendor_id', null);
                }

                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('from', function ($row) {

                        $data = optional($row->from)->name;

                        return $data;
                    })
                    ->addColumn('to', function ($row) {

                        $data = optional($row->to)->name;

                        return $data;
                    })
                    ->addColumn('transaction_date', function ($row) {

                        $data = Carbon::parse($row->date)->format('d M, Y');

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->rawColumns(['from', 'to', 'date'])
                    ->make(true);
            }
            return view('admin.bank.contra.index', compact('banks'));
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
        if (auth()->user()->can('bank.create')) {
            $validatedData = $request->validate([
                'from_id' => 'required',
                'to_id' => 'required',
                'amount' => 'required|numeric',
            ]);

            if ($request->from_id != $request->to_id) {
                $contra = new BankContra;
                $contra->from_id = $request->from_id;
                $contra->to_id = $request->to_id;
                $contra->amount = $request->amount;
                $contra->date = $request->date;
                $contra->note = $request->note;
                if (Auth::user()->vendor) {
                    $contra->vendor_id = Auth::user()->vendor->id;
                }
                $contra->save();
                // credit transaction
                $transaction = new BankTransaction;
                $transaction->bank_id = $request->to_id;
                $transaction->note = $request->note;
                $transaction->credit = $request->amount;
                $transaction->date = $request->date;
                if (Auth::user()->vendor) {
                    $transaction->vendor_id = Auth::user()->vendor->id;
                }
                $transaction->save();
                // debit transaction
                $transaction = new BankTransaction;
                $transaction->bank_id = $request->from_id;
                $transaction->note = $request->note;
                $transaction->debit = $request->amount;
                $transaction->date = $request->date;
                if (Auth::user()->vendor) {
                    $transaction->vendor_id = Auth::user()->vendor->id;
                }
                $transaction->save();

                Alert::toast('New cash flow created', 'success');
                return back();
            } else {
                Alert::toast('Both sender and receiver can not be same', 'warning');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankContra  $bankContra
     * @return \Illuminate\Http\Response
     */
    public function show(BankContra $bankContra) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankContra  $bankContra
     * @return \Illuminate\Http\Response
     */
    public function edit(BankContra $bankContra) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankContra  $bankContra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankContra $bankContra) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankContra  $bankContra
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankContra $bankContra) {
        //
    }
}
