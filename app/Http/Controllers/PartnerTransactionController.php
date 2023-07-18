<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerTransaction;
use App\Models\Bank;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Datatables;
use Alert;
use Auth;
use Carbon\Carbon;

class PartnerTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->can('setting.index')) {
            $partners = Partner::orderBy('name', 'ASC')->get();
            $banks = Bank::orderBy('id', 'DESC')->get();
            $transactions = PartnerTransaction::orderBy('id', 'DESC')->get();
            return view('admin.partner.transaction', compact('partners', 'transactions', 'banks'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request)
    {
        if (auth()->user()->can('setting.index')) {
            $partners = Partner::orderBy('name', 'ASC')->get();
            $banks = Bank::all();
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from.' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to.' 23:59:59');
                $transactions = PartnerTransaction::whereBetween('created_at', [$start_date,$end_date])->orderBy('id', 'DESC')->get();
            }
            else {
                $transactions = PartnerTransaction::orderBy('id', 'DESC')->get();
            }
            $partner_id = $request->partner_id;
            if ($partner_id != '') {
                $transactions = $transactions->filter(function($item) use($partner_id){
                    return $item->partner_id == $partner_id;
                });
            }
            
            return view('admin.partner.transaction', compact('partners', 'banks', 'transactions')); 
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
                'partner_id' => 'required|integer',
                'bank_id' => 'required|integer',
                'type' => 'required',
                'amount' => 'required|numeric',
            ]);

            $p_transaction = new PartnerTransaction;
            $p_transaction->partner_id = $request->partner_id;
            $p_transaction->bank_id = $request->bank_id;
            $p_transaction->note = $request->note;
            $p_transaction->date = $request->date;
            if ($request->type == 'deposit') {
                $p_transaction->credit = $request->amount;
            }
            else {
                $p_transaction->debit = $request->amount;
            }
            $p_transaction->save();

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
            $transaction->save();
            Alert::toast('New Transaction created', 'success');
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
     * @param  \App\Models\PartnerTransaction  $partnerTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(PartnerTransaction $partnerTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnerTransaction  $partnerTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(PartnerTransaction $partnerTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartnerTransaction  $partnerTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PartnerTransaction $partnerTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnerTransaction  $partnerTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartnerTransaction $partnerTransaction)
    {
        //
    }
}
