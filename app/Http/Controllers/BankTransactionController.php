<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use App\Models\Bank;
use Illuminate\Http\Request;

use Alert;
use Auth;
use Carbon\Carbon;
use DataTables;

class BankTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->can('bank.index')) {
            $banks = Bank::all();
            $data = BankTransaction::orderBy('id', 'DESC')->get();
            if ($request->ajax()) {
                $data = BankTransaction::orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                        // ->addIndexColumn()
                        ->addColumn('bank', function($row){
         
                               $data = optional($row->bank)->name;
        
                                if ($row->other_income) {
                                    return $data . '<span class="ml-3 badge badge-success">Others Income</span>';
                                } else {
                                    return $data;
                                }
                        })
                        ->addColumn('transaction_date', function($row){
         
                               $data = Carbon::parse($row->date)->format('d M, Y');
        
                                return $data;
                        })
                        ->addColumn('date', function($row){
         
                               $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');
        
                                return $data;
                        })
                        ->rawColumns(['expense_type','bank','date','action'])
                        ->make(true);
            }
            return view('admin.bank.transaction.index', compact('banks', 'data')); 
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
        if (auth()->user()->can('bank.create')) {
            $validatedData = $request->validate([
                'bank_id' => 'required|string',
                'type' => 'required',
                'amount' => 'required|numeric',
            ]);

            $transaction = new BankTransaction;
            $transaction->bank_id = $request->bank_id;
            $transaction->note = $request->note;
            $transaction->date = $request->date;
            if ($request->type == 'deposit') {
                $transaction->credit = $request->amount;
            }
            else {
                $transaction->debit = $request->amount;
            }
            if ($request->has('other_income')) {
                $transaction->other_income = $request->other_income;
            }
            $transaction->save();
            Alert::toast('New Transaction created', 'success');
            return back();
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request)
    {
        if (auth()->user()->can('bank.index')) {
            $banks = Bank::all();
            $data = BankTransaction::orderBy('id', 'DESC')->get();
            if ($request->ajax()) {
                if (!empty($request->date_from) && !empty($request->date_to)) {
                    $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from.' 00:00:00');
                    $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to.' 23:59:59');
                    $data = BankTransaction::whereBetween('created_at', [$start_date,$end_date])->orderBy('id', 'DESC')->get();
                }
                else {
                    $data = BankTransaction::orderBy('id', 'DESC')->get();
                }
                $bank_id = $request->bank_id;
                if ($bank_id != '') {
                    $data = $data->filter(function($item) use($bank_id){
                        return $item->bank_id == $bank_id;
                    });
                }
                
                return Datatables::of($data)
                        // ->addIndexColumn()
                        ->addColumn('bank', function($row){
         
                               $data = optional($row->bank)->name;
        
                                return $data;
                        })
                        ->addColumn('transaction_date', function($row){
         
                               $data = Carbon::parse($row->date)->format('d M, Y');
        
                                return $data;
                        })
                        ->addColumn('date', function($row){
         
                               $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');
        
                                return $data;
                        })
                        ->rawColumns(['expense_type','bank','date','action'])
                        ->make(true);
            }
            //dd($data->sum('credit'));
            return view('admin.bank.transaction.index', compact('banks', 'data')); 
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankTransaction  $bankTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(BankTransaction $bankTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankTransaction  $bankTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(BankTransaction $bankTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankTransaction  $bankTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankTransaction $bankTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankTransaction  $bankTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankTransaction $bankTransaction)
    {
        //
    }
}
