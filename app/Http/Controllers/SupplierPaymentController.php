<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\Bank;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Alert;
use Auth;
use Carbon\Carbon;
use DataTables;

class SupplierPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->can('supplier.payment')) {
            $banks = Bank::all();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            if ($request->ajax()) {
                $data = SupplierPayment::orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('supplier', function($row){
     
                           $data = optional($row->supplier)->name;
    
                            return $data;
                    })
                    ->addColumn('bank', function($row){
     
                           $data = optional($row->bank)->name;
    
                            return $data;
                    })
                    ->addColumn('payment_date', function($row){
     
                           $data = Carbon::parse($row->payment_date)->format('d M, Y');
    
                            return $data;
                    })
                    ->addColumn('date', function($row){
     
                           $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');
    
                            return $data;
                    })
                    ->rawColumns(['supplier','bank','date'])
                    ->make(true);
            }
            return view('admin.supplier.supplier-payment', compact('banks', 'suppliers')); 
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request)
    {
        if (auth()->user()->can('supplier.payment')) {
            $banks = Bank::all();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();

            if (!empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from.' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to.' 23:59:59');
                $payments = SupplierPayment::whereBetween('created_at', [$start_date,$end_date])->orderBy('id', 'DESC')->get();
            }
            else {
                $payments = SupplierPayment::orderBy('id', 'DESC')->get();
            }

            if (!empty($request->supplier_id)) {
                $supplier_id = $request->supplier_id;
                // $data = $data->filter(function($item) use($supplier_id){
                //     return $item->supplier_id == $supplier_id;
                // });
                $payments = $payments->where('supplier_id', $supplier_id);
            }

            if (!empty($request->bank_id)) {
                $bank_id = $request->bank_id;
                // $data = $data->filter(function($item) use($bank_id){
                //     return $item->bank_id == $bank_id;
                // });
                $payments = $payments->where('bank_id', $bank_id);
            }

            if ($request->ajax()) {
            
                return Datatables::of($payments)
                    // ->addIndexColumn()
                    ->addColumn('supplier', function($row){
     
                           $data = optional($row->supplier)->name;
    
                            return $data;
                    })
                    ->addColumn('bank', function($row){
     
                           $data = optional($row->bank)->name;
    
                            return $data;
                    })
                    ->addColumn('payment_date', function($row){
     
                           $data = Carbon::parse($row->payment_date)->format('d M, Y');
    
                            return $data;
                    })
                    ->addColumn('date', function($row){
     
                           $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');
    
                            return $data;
                    })
                    ->rawColumns(['supplier','bank','date'])
                    ->make(true);
            }
            return view('admin.supplier.supplier-payment', compact('banks', 'suppliers')); 
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
        if (auth()->user()->can('supplier.payment')) {
            $validatedData = $request->validate([
                'supplier_id' => 'required',
                'bank_id' => 'required',
                'amount' => 'required|numeric',
            ]);

            $payment = new SupplierPayment;
            $payment->supplier_id = $request->supplier_id; 
            $payment->bank_id = $request->bank_id; 
            $payment->amount = $request->amount; 
            $payment->note = $request->note; 
            $payment->payment_date = $request->payment_date;
            $payment->save();

            $transaction = new BankTransaction;
            $transaction->bank_id = $request->bank_id;
            $transaction->debit = $request->amount;
            $transaction->note = $request->note;
            $transaction->save();

            Alert::toast('Payment successfull', 'success');
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
     * @param  \App\Models\SupplierPayment  $supplierPayment
     * @return \Illuminate\Http\Response
     */
    public function show(SupplierPayment $supplierPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupplierPayment  $supplierPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(SupplierPayment $supplierPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupplierPayment  $supplierPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplierPayment $supplierPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupplierPayment  $supplierPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupplierPayment $supplierPayment)
    {
        //
    }
}
