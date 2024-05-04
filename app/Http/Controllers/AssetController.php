<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetDeduction;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\ExpenseEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class AssetController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('setting.asset')) {
            $banks = Bank::orderBy('name', 'ASC')->get();
            $assets = Asset::orderBy('id', 'DESC')->get();
            // $asset_deductions = AssetDeduction::orderBy('id', 'DESC')->get();
            return view('admin.asset.index', compact('banks', 'assets'));
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
        if (auth()->user()->can('setting.asset')) {
            $banks = Bank::orderBy('name', 'ASC')->get();
            return view('admin.asset.create', compact('banks'));
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
        if (auth()->user()->can('setting.asset')) {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'bank_id' => 'required|integer',
                'amount' => 'required|numeric',
                'estimated_life' => 'required',
                'purchase_date' => 'required',
            ]);

            $asset = new Asset;
            $asset->name = $request->name;
            $asset->bank_id = $request->bank_id;
            $asset->amount = $request->amount;
            if ($request->depreciation_value != '') {
                $asset->depreciation_value = $request->depreciation_value;
            } else {
                $asset->depreciation_value = round($request->amount / $request->estimated_life);
            }
            $asset->estimated_life = $request->estimated_life;
            $asset->purchase_date = $request->purchase_date;
            $asset->depreciation_date = $request->depreciation_date ?? '30';
            $asset->note = $request->note;
            $asset->save();

            $transaction = new BankTransaction;
            $transaction->bank_id = $request->bank_id;
            $transaction->debit = $request->amount;
            $transaction->note = $request->note;
            $transaction->save();

            Alert::toast('Asset created!', 'success');
            return redirect()->route('asset.index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('setting.asset')) {
            $asset = Asset::find($id);
            if (!is_null($asset)) {
                $banks = Bank::orderBy('name', 'ASC')->get();
                return view('admin.asset.edit', compact('banks', 'asset'));
            } else {
                Alert::toast('Asset Not Found', 'success');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (auth()->user()->can('setting.asset')) {
            $asset = Asset::find($id);
            if (!is_null($asset)) {
                $validatedData = $request->validate([
                    'name' => 'required|string',
                    'estimated_life' => 'required',
                    'purchase_date' => 'required',
                ]);

                $asset->name = $request->name;
                if ($request->depreciation_value != '') {
                    $asset->depreciation_value = $request->depreciation_value;
                } else {
                    $asset->depreciation_value = round($asset->amount / $request->estimated_life);
                }
                $asset->estimated_life = $request->estimated_life;
                $asset->purchase_date = $request->purchase_date;
                $asset->depreciation_date = $request->depreciation_date ?? '30';
                $asset->note = $request->note;
                $asset->save();

                Alert::toast('Asset Updated', 'success');
                return redirect()->route('asset.index');
            } else {
                Alert::toast('Asset Not Found', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset) {
        //
    }

    public function deduct_now(Request $request, $id) {
        if (auth()->user()->can('setting.asset')) {
            $validatedData = $request->validate([
                'depreciation_value' => 'required',
            ]);

            $asset = Asset::find($id);
            $total_depreciated = $asset->deductions->sum('amount');
            $net_value = $asset->amount - $total_depreciated;

            if ($request->depreciation_value > $net_value) {
                Alert::toast('Depreciation value can not be greater than net asset value!', 'error');
                return back();
            }

            $deduct = new AssetDeduction;
            $deduct->asset_id = $asset->id;

            $expense = new ExpenseEntry;
            $expense->expense_id = 13;
            $expense->bank_id = $asset->bank_id;
            $expense->date = Carbon::today();
            $expense->note = 'Depreciation of ' . $asset->name;

            if ($request->depreciation_value != '') {
                $deduct->amount = $request->depreciation_value;

                $expense->amount = $request->depreciation_value;
            } else {
                if ($asset->depreciation_value <= $net_value) {
                    $deduct->amount = $asset->depreciation_value;

                    $expense->amount = $asset->depreciation_value;
                } else {
                    $deduct->amount = $net_value;

                    $expense->amount = $net_value;
                }
            }

            $expense->save();
            $deduct->save();

            Alert::toast('Depreciated Successfully!', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function dispose(Request $request, $id) {
        if (auth()->user()->can('setting.asset')) {
            $validatedData = $request->validate([
                'disposal_amount' => 'required',
                'bank_id' => 'required|integer',
            ]);

            $asset = Asset::find($id);
            $asset->disposal_amount = $request->disposal_amount;

            $asset->save();

            $transaction = new BankTransaction;
            $transaction->bank_id = $request->bank_id;
            $transaction->credit = $request->disposal_amount;
            $transaction->note = $request->note;
            $transaction->save();

            Alert::toast('Asset Disposal Successfully Done!', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
