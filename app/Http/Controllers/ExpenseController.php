<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Auth;
use Alert;

class ExpenseController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (auth()->user()->can('expense_type.index')) {
            $expenses = Expense::all();
            return view('admin.expense.index', compact('expenses'));
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
        if (auth()->user()->can('expense.create')) {
            $validatedData = $request->validate([
                'type' => 'required|max:255',
            ]);
            $expense = new Expense;
            $expense->type = $request->type;
            $expense->save();
            Alert::toast('New Expense Type Added !', 'success');
            return redirect()->route('expense.index');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (auth()->user()->can('expense.edit')) {
            $this->validate($request, [
                'type' => 'required',
            ]);

            $expense = Expense::find($id);

            if (!is_null($expense)) {
                $expense->type = $request->type;

                $expense->save();
                Alert::toast('Expense Type has been updated !', 'success');
                return redirect()->route('expense.index');
            } else {
                Alert::toast('Expense Type Not Found !', 'warning');
                return redirect()->route('expense.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('expense.create')) {
            $expense = Expense::find($id);

            if (!is_null($expense)) {
                if (count($expense->entries) < 1) {
                    $expense->delete();
                    Alert::toast('Expense Type has been deleted !', 'success');
                    return redirect()->route('expense.index');
                } else {
                    Alert::toast('Expense type can not be deleted because there is expense entries added under this type!', 'warning');
                    return redirect()->route('expense.index');
                }
            } else {
                Alert::toast('Expense Not Found !', 'warning');
                return redirect()->route('expense.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
