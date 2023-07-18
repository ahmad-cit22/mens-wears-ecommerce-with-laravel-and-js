<?php

namespace App\Http\Controllers;

use App\Models\ExpenseEntry;
use App\Models\BankTransaction;
use App\Models\Bank;
use Illuminate\Http\Request;
use Auth;
use Alert;
use Carbon\Carbon;
use DataTables;

class ExpenseEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        if (auth()->user()->can('expense.index')) {
            $data = ExpenseEntry::orderBy('id', 'DESC')->get();
            $banks = Bank::all();
            if ($request->ajax()) {
                return Datatables::of($data)
                        // ->addIndexColumn()
                        ->addColumn('expense_type', function($row){
         
                               $data = optional($row->expense)->type;
        
                                return $data;
                        })
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
            return view('admin.expense.entry', compact('data', 'banks'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request)
    {
        if (auth()->user()->can('expense.index')) {
            $banks = Bank::all();
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from.' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to.' 23:59:59');
                $data = ExpenseEntry::whereBetween('created_at', [$start_date,$end_date])->orderBy('id', 'DESC')->get();
            }
            else {
                $data = ExpenseEntry::orderBy('id', 'DESC')->get();
            }

            $bank_id = $request->bank_id;
            if ($bank_id != '') {
                $data = $data->filter(function($item) use($bank_id){
                    return $item->bank_id == $bank_id;
                });
            }

            if ($request->ajax()) {
                
                return Datatables::of($data)
                        // ->addIndexColumn()
                        ->addColumn('expense_type', function($row){
         
                               $data = optional($row->expense)->type;
        
                                return $data;
                        })
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
            return view('admin.expense.entry', compact('data', 'banks')); 
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
        if (auth()->user()->can('expense.create')) {
           $validatedData = $request->validate([
                'expense_id' => 'required|integer',
                'amount' => 'required|numeric',
            ]);

            $expense = new ExpenseEntry;
            $expense->expense_id = $request->expense_id; 
            $expense->bank_id = $request->bank_id; 
            $expense->amount = $request->amount; 
            $expense->date = $request->date; 
            $expense->note = $request->note;
            $expense->save();

            if ($request->bank_id != '' && $request->bank_id > 0) {
                $transaction = new BankTransaction;
                $transaction->bank_id = $request->bank_id;
                $transaction->note = $request->note;
                $transaction->debit = $request->amount;
                $transaction->date = $request->date;
                $transaction->save();
            }
            Alert::toast('New expense listed', 'success');
            return back(); 
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseEntry  $expenseEntry
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseEntry $expenseEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseEntry  $expenseEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseEntry $expenseEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseEntry  $expenseEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseEntry $expenseEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseEntry  $expenseEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseEntry $expenseEntry)
    {
        //
    }
}
