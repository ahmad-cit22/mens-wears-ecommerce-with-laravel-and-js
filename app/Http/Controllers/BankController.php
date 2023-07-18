<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Alert;
use Auth;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('bank.index')) {
           $banks = Bank::orderBy('id', 'DESC')->get();
           return view('admin.bank.index', compact('banks')); 
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
                'name' => 'required|string',
                'account_number' => 'required',
                'opening_balance' => 'required|numeric',
            ]);

            $bank = new Bank;
            $bank->name = $request->name;
            $bank->account_number = $request->account_number;
            $bank->save();

            $transaction = new BankTransaction;
            $transaction->bank_id = $bank->id;
            $transaction->credit = $request->opening_balance;
            $transaction->note = 'Opening Balance';
            $transaction->save();
            Alert::toast('New Bank created', 'success');
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
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('bank.index')) {
           $bank = Bank::find($id);
           if (!is_null($bank)) {
                $validatedData = $request->validate([
                    'name' => 'required|string',
                    'account_number' => 'required',
                ]);

                $bank->name = $request->name;
                $bank->account_number = $request->account_number;
                $bank->save();
                Alert::toast('Bank details updated.', 'success');
                return back();
           }
           else {
                Alert::toast('Bank not found!', 'error');
                return back();
           }
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
