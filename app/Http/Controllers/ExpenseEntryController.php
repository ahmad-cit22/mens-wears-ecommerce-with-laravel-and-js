<?php

namespace App\Http\Controllers;

use App\Models\ExpenseEntry;
use App\Models\BankTransaction;
use App\Models\Bank;
use App\Models\WorkTrackingEntry;
use Illuminate\Http\Request;
use Auth;
use Alert;
use App\Models\Expense;
use App\Models\Order;
use Carbon\Carbon;
use DataTables;

class ExpenseEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date_from = '';
        $date_to = '';

        // return ExpenseEntry::orderBy('created_at', 'desc')->get();

        if (auth()->user()->can('expense.view')) {

            if (!Auth::user()->vendor) {
                $data = ExpenseEntry::where('vendor_id', null)->orderBy('date', 'desc')->with('expense', 'bank', 'created_by', 'created_by.adder')->get();
                $banks = Bank::where('vendor_id', null)->orderBy('name', 'ASC')->get();
                $expense_types = Expense::where('vendor_id', null)->orderBy('type', 'ASC')->get();
            } else {
                $data = ExpenseEntry::orderBy('date', 'desc')->where('vendor_id', Auth::user()->vendor->id)->with('expense', 'bank', 'created_by', 'created_by.adder')->get();
                $banks = Bank::orderBy('name', 'ASC')->where('vendor_id', Auth::user()->vendor->id)->get();
                $expense_types = Expense::orderBy('type', 'ASC')->where('vendor_id', Auth::user()->vendor->id)->get();
            }

            if ($request->ajax()) {
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('expense_type', function ($row) {

                        $data = optional($row->expense)->type;

                        return $data;
                    })
                    ->addColumn('bank', function ($row) {

                        $data = optional($row->bank)->name;

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
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                            $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' . $row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by')
                    ->addColumn('action', function ($row) {
                        $btn = '<button class="btn btn-primary btn-sm edit-expense" data-id="' .  $row->id . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="fas fa-edit"></i></button>
                          <button class="btn btn-danger btn-sm delete-expense" data-id="' .  $row->id . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="fas fa-trash"></i></button>';



                        return $btn;
                    })
                    ->rawColumns(['expense_type', 'bank', 'date', 'action'])
                    ->make(true);
            }
            return view('admin.expense.entry', compact('data', 'banks', 'date_from', 'date_to', 'expense_types'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request)
    {
        $date_from = '';
        $date_to = '';

        if (auth()->user()->can('expense.view')) {
            if (!Auth::user()->vendor) {
                $banks = Bank::where('vendor_id', null)->get();
                $expense_types = Expense::where('vendor_id', null)->orderBy('type', 'ASC')->get();
            } else {
                $banks = Bank::where('vendor_id', Auth::user()->vendor->id)->get();
                $expense_types = Expense::orderBy('type', 'ASC')->where('vendor_id', Auth::user()->vendor->id)->get();
            }
            if (!empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $data = ExpenseEntry::whereBetween('date', [$start_date, $end_date])->orderBy('created_at', 'desc')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            } else {
                $data = ExpenseEntry::orderBy('date', 'desc')->get();
            }

            $bank_id = $request->bank_id;
            if ($bank_id != '') {
                $data = $data->filter(function ($item) use ($bank_id) {
                    return $item->bank_id == $bank_id;
                });
            }

            if (Auth::user()->vendor) {
                $data = $data->where('vendor_id', Auth::user()->vendor->id);
            } else {
                $data = $data->where('vendor_id', null);
            }


            if ($request->ajax()) {

                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('expense_type', function ($row) {

                        $data = optional($row->expense)->type;

                        return $data;
                    })
                    ->addColumn('bank', function ($row) {

                        $data = optional($row->bank)->name;

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
                    ->addColumn('created_by', function ($row) {

                        if ($row->created_by) {
                            $data = '<a href="' . route('user.edit', $row->created_by->user_id) . '">' . $row->created_by->adder->name . '</a>';
                        } else {
                            $data = '--';
                        }

                        return $data;
                    })->escapeColumns('created_by')
                    ->addColumn('action', function ($row) {
                        $btn = '<button class="btn btn-primary btn-sm edit-expense" data-id="' .  $row->id . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="fas fa-edit"></i></button>
                          <button class="btn btn-danger btn-sm delete-expense" data-id="' .  $row->id . '" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"><i class="fas fa-trash"></i></button>';

                        return $btn;
                    })
                    ->rawColumns(['expense_type', 'bank', 'date', 'action'])
                    ->make(true);
            }
            return view('admin.expense.entry', compact('data', 'banks', 'date_from', 'date_to', 'expense_types'));
        } else {
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
            if (Auth::user()->vendor) {
                $expense->vendor_id = Auth::user()->vendor->id;
            }
            $expense->save();

            WorkTrackingEntry::create([
                'expense_entry_id' => $expense->id,
                'user_id' => Auth::id(),
                'work_name' => 'expense_entry'
            ]);

            if ($request->bank_id != '' && $request->bank_id > 0) {
                $transaction = new BankTransaction;
                $transaction->bank_id = $request->bank_id;
                $transaction->expense_id = $expense->id;
                $transaction->note = $request->note;
                $transaction->debit = $request->amount;
                $transaction->date = $request->date;
                if (Auth::user()->vendor) {
                    $transaction->vendor_id = Auth::user()->vendor->id;
                }
                $transaction->save();
            }
            Alert::toast('New expense listed', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function edit_modal($id)
    {
        if (!Auth::user()->vendor) {
            return view('admin.expense.modals.edit', [
                'expense' => ExpenseEntry::find($id),
                'banks' => Bank::where('vendor_id', null)->orderBy('name', 'ASC')->get(),
                'expense_types' => Expense::where('vendor_id', null)->orderBy('type', 'ASC')->get()
            ]);
        } else {
            return view('admin.expense.modals.edit', [
                'expense' => ExpenseEntry::find($id),
                'banks' => Bank::where('vendor_id', Auth::user()->vendor->id)->orderBy('name', 'ASC')->get(),
                'expense_types' => Expense::where('vendor_id', Auth::user()->vendor->id)->orderBy('type', 'ASC')->get()
            ]);
        }
    }

    public function loss_store(Request $request)
    {
        if (auth()->user()->can('expense.create') || auth()->user()->can('add.loss')) {
            $validatedData = $request->validate([
                'amount' => 'required|numeric',
            ]);

            if ($request->amount > 0) {
                $expense = new ExpenseEntry;
                $expense->expense_id = 2;
                $expense->bank_id = $request->bank_id;
                $expense->amount = $request->amount;
                $expense->date = $request->date;
                $expense->note = $request->note;
                if (Auth::user()->vendor) {
                    $expense->vendor_id = Auth::user()->vendor->id;
                }
                $expense->save();

                if ($request->bank_id != '' && $request->bank_id > 0) {
                    $transaction = new BankTransaction;
                    $transaction->bank_id = $request->bank_id;
                    $transaction->expense_id = $expense->id;
                    $transaction->note = $request->note;
                    $transaction->debit = $request->amount;
                    $transaction->date = $request->date;
                    if (Auth::user()->vendor) {
                        $transaction->vendor_id = Auth::user()->vendor->id;
                    }
                    $transaction->save();
                }

                $order = Order::find($request->order_id);
                $order->add_loss = 1;
                $order->save();

                WorkTrackingEntry::create([
                    'order_id' => $order->id,
                    'expense_entry_id' => $expense->id,
                    'user_id' => Auth::id(),
                    'work_name' => 'add_loss'
                ]);

                Alert::toast('Loss added', 'success');
            } else {
                Alert::toast('Enter loss amount correctly!', 'error');
            }

            return back();
        } else {
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseEntry  $expenseEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (auth()->user()->can('expense.edit')) {
            $this->validate($request, [
                'expense_id' => 'required|integer',
                'amount' => 'required|numeric',
            ]);

            $expense = ExpenseEntry::find($request->id);

            if (!is_null($expense)) {
                $expense->expense_id = $request->expense_id;
                $expense->bank_id = $request->bank_id;
                $expense->amount = $request->amount;
                $expense->date = $request->date;
                $expense->note = $request->note;
                $expense->save();

                if ($request->bank_id != '' && $request->bank_id > 0) {
                    $transaction = BankTransaction::where('expense_id', $expense->id)->first();
                    $transaction->bank_id = $request->bank_id;
                    $transaction->note = $request->note;
                    $transaction->debit = $request->amount;
                    $transaction->date = $request->date;
                    $transaction->save();
                }
                Alert::toast('Expense Entry has been updated!', 'success');
                return response()->json(['success' => true]);
            } else {
                Alert::toast('Expense Entry Not Found!', 'warning');
                return response()->json(['error' => true]);
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseEntry  $expenseEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->can('expense.delete')) {
            $expense = ExpenseEntry::find($id);

            if (!is_null($expense)) {
                if (BankTransaction::where('expense_id', $id)->exists()) {
                    BankTransaction::where('expense_id', $id)->delete();
                }

                if ($expense->expense_id == 2) {
                    $order_id = WorkTrackingEntry::where('expense_entry_id', $expense->id)->where('work_name', 'add_loss')->first()->order_id;

                    $order = Order::find($order_id);
                    $order->add_loss = 0;
                    $order->save();
                }

                $expense->delete();
                Alert::toast('Expense entry has been deleted !', 'success');
                return redirect()->route('expenseentry.index');
            } else {
                Alert::toast('Expense Entry Not Found !', 'warning');
                return redirect()->route('expenseentry.index');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
