<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionSupplier;
use App\Models\ProductionAccessory;
use App\Models\ProductionCost;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\Accessory;
use App\Models\AccessoryAmount;
use Illuminate\Http\Request;
use Auth;
use Alert;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('product.index')) {
            $productions = Production::orderBy('id', 'DESC')->get();
            return view('admin.production-sheet.index', compact('productions'));
        }
        else {
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
        if (auth()->user()->can('product.create')) {
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            $categories = Category::orderBy('id', 'ASC')->get();
            $products = Product::orderBy('id', 'DESC')->get();
            $accessories = Accessory::orderBy('id', 'DESC')->get();
            return view('admin.production-sheet.create', compact('suppliers', 'categories','products', 'accessories'));
        }
        else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->can('product.create')) {
            $production = new Production;
            $production->product_code = $request->product_code;
            $production->category_id = $request->category_id;
            $production->date = $request->production_date;
            $production->output_units = $request->output_units;
            $production->save();

            $total = $request->fabric_amount + $request->contrast_amount + $request->swing_amount + $request->printing_amount;
            // Fabric
            if (!is_null($request->fabric_supplier_id) && !is_null($request->fabric_amount)) {
                $supplier = new ProductionSupplier;
                $supplier->production_id = $production->id;
                $supplier->supplier_id = $request->fabric_supplier_id;
                $supplier->qty = $request->fabric_qty;
                $supplier->amount = $request->fabric_amount;
                $supplier->type = 'fabric';
                $supplier->save();
            }    
            // Contrast Fabric
            if (!is_null($request->contrast_supplier_id) && !is_null($request->contrast_amount)) {
                $supplier = new ProductionSupplier;
                $supplier->production_id = $production->id;
                $supplier->supplier_id = $request->contrast_supplier_id;
                $supplier->qty = $request->contrast_qty;
                $supplier->amount = $request->contrast_amount;
                $supplier->type = 'contrast';
                $supplier->save();
            }    
            // Swing
            if (!is_null($request->swing_supplier_id) && !is_null($request->swing_amount)) {
                $supplier = new ProductionSupplier;
                $supplier->production_id = $production->id;
                $supplier->supplier_id = $request->swing_supplier_id;
                $supplier->qty = $request->swing_qty;
                $supplier->amount = $request->swing_amount;
                $supplier->type = 'swing';
                $supplier->save();
            }
            // Printing
            if (!is_null($request->printing_supplier_id) && !is_null($request->printing_amount)) {
                $supplier = new ProductionSupplier;
                $supplier->production_id = $production->id;
                $supplier->supplier_id = $request->printing_supplier_id;
                $supplier->qty = $request->printing_qty;
                $supplier->amount = $request->printing_amount;
                $supplier->type = 'printing';
                $supplier->save();
            }
            

            $i = 0;
            foreach ($request->accessory_id as $item) {
                if (!is_null($item) || !is_null($request->accessory_amount[$i])) {
                    $accessory = new ProductionAccessory;
                    $accessory->production_id = $production->id;
                    $accessory->accessory_id = $item;
                    $accessory->amount = $request->accessory_amount[$i];
                    $accessory->save();
                    $total += $request->accessory_amount[$i];

                    $stock = new AccessoryAmount;
                    $stock->accessory_id = $item;
                    $stock->debit = $request->accessory_amount[$i];
                    $stock->note = 'Production Sheet';
                    $stock->save();
                }
                $i += 1;
            }

            $i = 0;
            foreach ($request->cost_name as $item) {
                if (!is_null($item) || !is_null($request->cost_amount[$i])) {
                    $cost = new ProductionCost;
                    $cost->production_id = $production->id;
                    $cost->name = $item;
                    $cost->amount = $request->cost_amount[$i];
                    $cost->save();
                    $total += $request->cost_amount[$i];
                }
                $i += 1;
            }
            $production->unit_cost = ($total / $production->output_units);
            $production->save();
            Alert::toast('Production Sheet Created!', 'success');
            return back();
        }
        else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Production  $production
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (auth()->user()->can('product.view')) {
           $production = Production::find($id);
           if (!is_null($production)) {
               $fabric = ProductionSupplier::where('production_id', $production->id)->where('type', 'fabric')->first();
               $contrast = ProductionSupplier::where('production_id', $production->id)->where('type', 'contrast')->first();
               $swing = ProductionSupplier::where('production_id', $production->id)->where('type', 'swing')->first();
               $printing = ProductionSupplier::where('production_id', $production->id)->where('type', 'printing')->first();
               $suppliers = Supplier::orderBy('name', 'ASC')->get();
               return view('admin.production-sheet.show', compact('production', 'fabric', 'contrast', 'swing', 'printing', 'suppliers'));
           }
           else {
            Alert::toast('Production Sheet Not Found!', 'error');
            return back();
           }
        }
        else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function recalculate($id)
    {
        $production = Production::find($id);
        if (!is_null($production)) {
            $total = 0;
            $total += $production->suppliers->sum('amount') + $production->costs->sum('amount') + $production->accessories->sum('amount');
            $production->unit_cost = $total/$production->output_units;
            $production->save();
            Alert::toast('Production sheet updated', 'success');
            return back();
        }
    }

    public function supplier(Request $request, $id)
    {
        $production = Production::find($id);
        if (!is_null($production)) {
            $supplier = new ProductionSupplier;
            $supplier->production_id = $production->id;
            $supplier->supplier_id = $request->supplier_id;
            $supplier->qty = $request->qty;
            $supplier->amount = $request->amount;
            $supplier->type = $request->type;
            $supplier->save();
            Alert::toast('Production sheet updated', 'success');
            return back();
        }
    }

    public function accessory(Request $request, $id)
    {
        $production = Production::find($id);
        if (!is_null($production)) {
            $accessory = new ProductionAccessory;
            $accessory->production_id = $production->id;
            $accessory->accessory_id = $request->accessory_id;
            $accessory->amount = $request->amount;
            $accessory->save();

            $stock = new AccessoryAmount;
            $stock->accessory_id = $request->accessory_id;
            $stock->debit = $request->amount;
            $stock->note = 'Production Sheet';
            $stock->save();
            Alert::toast('Production sheet updated', 'success');
            return back();
        }
    }

    public function cost(Request $request, $id)
    {
        $production = Production::find($id);
        if (!is_null($production)) {
            $cost = new ProductionCost;
            $cost->production_id = $production->id;
            $cost->name = $request->name;
            $cost->amount = $request->amount;
            $cost->save();
            Alert::toast('Production sheet updated', 'success');
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Production  $production
     * @return \Illuminate\Http\Response
     */
    public function edit(Production $production)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Production  $production
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Production $production)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Production  $production
     * @return \Illuminate\Http\Response
     */
    public function destroy(Production $production)
    {
        //
    }
}
