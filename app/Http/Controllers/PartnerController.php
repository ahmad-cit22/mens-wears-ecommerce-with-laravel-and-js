<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerTransaction;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Accessory;
use App\Models\AccessoryAmount;
use App\Models\Asset;
use App\Models\AssetDeduction;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Expense;
use App\Models\ExpenseEntry;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Auth;
use Alert;
use Carbon\Carbon;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('setting.index')) {
            $partners = Partner::orderBy('name', 'ASC')->with('transactions')->get();
            $orders = Order::where('is_final', 1)->where('order_status_id', '!=', 5)->with('order_product')->get();
            $order_amount = $orders->sum('price');
            $production_cost = 0;
            $other_income = BankTransaction::where('other_income', 1)->get();
            $expenses = ExpenseEntry::orderBy('id', 'DESC')->get();
            foreach ($orders as $order) {
                $production_cost += $order->order_product->sum(function ($t) {
                    $qty = $t->qty - $t->return_qty;
                    return $t->production_cost * $qty;
                });
            }
            return view('admin.partner.index', compact('partners', 'order_amount', 'production_cost', 'other_income', 'expenses'));
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
        if (auth()->user()->can('setting.index')) {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'share_portion' => 'required|numeric',
                'opening_balance' => 'required|numeric',
            ]);

            $partner = new Partner;
            $partner->name = $request->name;
            $partner->share_portion = $request->share_portion;
            $partner->save();

            $transaction = new PartnerTransaction;
            $transaction->partner_id = $partner->id;
            $transaction->credit = $request->opening_balance;
            $transaction->note = 'Opening Balance';
            $transaction->date = Carbon::today()->format('Y-m-d');
            $transaction->save();
            Alert::toast('Partner Listed', 'success');
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
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('setting.index')) {
            $partner = Partner::find($id);
            if (!is_null($partner)) {
                $validatedData = $request->validate([
                    'name' => 'required|string',
                    'share_portion' => 'required|numeric',
                ]);

                $partner->name = $request->name;
                $partner->share_portion = $request->share_portion;
                $partner->save();
                Alert::toast('Partner Updated', 'success');
                return back();
            }
            else {
                Alert::toast('Partner Not Found', 'success');
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
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        //
    }
}
