<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\ProductionSupplier;
use Illuminate\Http\Request;
use Alert;
use Auth;
use Carbon\Carbon;
use DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('supplier.index')) {
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            return view('admin.supplier.index', compact('suppliers'));
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->can('supplier.create')) {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'opening_balance' => 'required|numeric',
            ]);

            $supplier = new Supplier;
            $supplier->name = $request->name;
            $supplier->phone = $request->phone;
            $supplier->email = $request->email;
            $supplier->address = $request->address;
            $supplier->opening_balance = $request->opening_balance;
            $supplier->save();
            Alert::toast('Supplier Saved.', 'success');
            return back();
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    public function debt(Request $request)
    {
        $supplier = Supplier::find($request->supplier_id);
        if (!is_null($supplier)) {
            $validatedData = $request->validate([
                'supplier_id' => 'required|max:255',
                'amount' => 'required|numeric',
            ]);

            $debt = new ProductionSupplier;
            $debt->supplier_id = $request->supplier_id;
            $debt->amount = $request->amount;
            $debt->note = $request->note;
            $debt->save();
            Alert::toast('Supplier debt added', 'success');
            return back();
        }
        else {
            Alert::toast('Supplier Not Found', 'error');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('supplier.edit')) {
            $supplier = Supplier::find($id);
            if (!is_null($supplier)) {
                $validatedData = $request->validate([
                    'name' => 'required|max:255'
                ]);

                $supplier->name = $request->name;
                $supplier->phone = $request->phone;
                $supplier->email = $request->email;
                $supplier->address = $request->address;
                // $supplier->opening_balance = $request->opening_balance;
                $supplier->save();
                Alert::toast('Supplier Updated.', 'success');
                return back();
            }
            else {
                Alert::toast('Supplier Not Found!', 'success');
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
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
