<?php

namespace App\Http\Controllers;

use App\Models\AccessoryAmount;
use App\Models\Accessory;
use App\Models\Bank;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Alert;
use Auth;
use Carbon\Carbon;
use DataTables;

class AccessoryAmountController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if (auth()->user()->can('setting.create')) {
            if (!Auth::user()->vendor) {
                $banks = Bank::where('vendor_id', null)->get();
                $accessories = Accessory::where('vendor_id', null)->get();
                $data = AccessoryAmount::where('vendor_id', null)->orderBy('id', 'DESC')->get();
            } else {
                $banks = Bank::where('vendor_id', Auth::user()->vendor->id)->get();
                $accessories = Accessory::where('vendor_id', Auth::user()->vendor->id)->get();
                $data = AccessoryAmount::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->get();
            }
            if ($request->ajax()) {
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('accessory', function ($row) {

                        $data = optional($row->accessory)->name;

                        return $data;
                    })
                    ->addColumn('bank', function ($row) {

                        $data = optional($row->bank)->name;

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->rawColumns(['accessory', 'bank', 'date'])
                    ->make(true);
            }
            return view('admin.accessory.accessory-stock', compact('banks', 'accessories'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request) {
        if (auth()->user()->can('supplier.payment')) {
            $banks = Bank::all();
            $accessories = Accessory::orderBy('name', 'ASC')->get();

            if (!empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $data = AccessoryAmount::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            } else {
                $data = AccessoryAmount::orderBy('id', 'DESC')->get();
            }

            if (!empty($request->accessory_id)) {
                $accessory_id = $request->accessory_id;
                $data = $data->where('accessory_id', $accessory_id);
            }

            if (!empty($request->bank_id)) {
                $bank_id = $request->bank_id;
                $data = $data->where('bank_id', $bank_id);
            }

            if (Auth::user()->vendor) {
                $data = $data->where('vendor_id', Auth::user()->vendor->id);
            } else {
                $data = $data->where('vendor_id', null);
            }

            if ($request->ajax()) {

                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('accessory', function ($row) {

                        $data = optional($row->accessory)->name;

                        return $data;
                    })
                    ->addColumn('bank', function ($row) {

                        $data = optional($row->bank)->name;

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->rawColumns(['accessory', 'bank', 'date'])
                    ->make(true);
            }
            return view('admin.accessory.accessory-stock', compact('banks', 'accessories'));
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
        if (auth()->user()->can('setting.create')) {
            $validatedData = $request->validate([
                'accessory_id' => 'required|integer',
                'amount' => 'required|numeric',
            ]);
            $stock = new AccessoryAmount;
            $stock->accessory_id = $request->accessory_id;
            $stock->bank_id = $request->bank_id;
            $stock->credit = $request->amount;
            $stock->note = $request->note;
            if (Auth::user()->vendor) {
                $stock->vendor_id = Auth::user()->vendor->id;
            }
            $stock->save();

            // $transaction = new BankTransaction;
            // $transaction->bank_id = $request->bank_id;
            // $transaction->debit = $request->amount;
            // $transaction->note = $request->note;
            // if (Auth::user()->vendor) {
            //     $transaction->vendor_id = Auth::user()->vendor->id;
            // }
            // $transaction->save();
            Alert::toast('Accessory added', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessoryAmount  $accessoryAmount
     * @return \Illuminate\Http\Response
     */
    public function show(AccessoryAmount $accessoryAmount) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccessoryAmount  $accessoryAmount
     * @return \Illuminate\Http\Response
     */
    public function edit(AccessoryAmount $accessoryAmount) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccessoryAmount  $accessoryAmount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccessoryAmount $accessoryAmount) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccessoryAmount  $accessoryAmount
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccessoryAmount $accessoryAmount) {
        //
    }
}
