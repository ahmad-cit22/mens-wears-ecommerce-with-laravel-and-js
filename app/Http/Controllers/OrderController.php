<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductStock;
use Illuminate\Http\Request;

use Auth;
use PDF;
use Session;
use Alert;
use Carbon\Carbon;
use DataTables;

class OrderController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if (auth()->user()->can('order.index')) {
            $orders = Order::orderBy('id', 'DESC')->where('is_final', 0)->get();
            if ($request->ajax()) {
                $data = Order::orderBy('id', 'DESC')->where('is_final', 0)->get();
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('order.edit', $row->id) . '">' . $row->code . '</a>';

                        return $code;
                    })
                    ->addColumn('status', function ($row) {

                        $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                          <a href="' . route('order.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['code', 'status', 'date', 'action'])
                    ->make(true);
            }
            return view('admin.order.index', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function sell_index(Request $request) {
        if (auth()->user()->can('order.index')) {
            $orders = Order::orderBy('id', 'DESC')->where('is_final', 1)->get();
            if ($request->ajax()) {
                $data = Order::orderBy('id', 'DESC')->where('is_final', 1)->get();
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('order.edit', $row->id) . '">' . $row->code . '</a>';

                        return $code;
                    })
                    ->addColumn('status', function ($row) {

                        $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                               <a href="' . route('order.invoice.pos.generate', $row->id) . '" class="btn btn-success" title="Print Invoice"><i class="fas fa-print"></i></a>
                          <a href="' . route('order.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                          <a href="' . route('order.return', $row->id) . '" class="btn btn-danger" title="Product Return"><i class="fas fa-undo"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['code', 'status', 'date', 'action'])
                    ->make(true);
            }
            return view('admin.order.sell.index', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function all_orders() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::orderBy('id', 'DESC')->get();
            return view('admin.order.all-orders', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function current_year() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::orderBy('id', 'DESC')->whereYear('created_at', Carbon::now()->year)->get();
            return view('admin.order.current-year', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function current_month() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::orderBy('id', 'DESC')->whereYear('created_at', Carbon::now()->year)->get();
            return view('admin.order.current-month', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function today() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::orderBy('id', 'DESC')->whereDate('created_at', Carbon::today())->get();
            return view('admin.order.today', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request) {
        if (auth()->user()->can('order.index')) {
            if (!empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;

                $orders = Order::where('order_status_id', $order_status_id)
                    ->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            }
            if ((!empty($request->order_status_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->date_from) && !empty($request->date_to))) {

                $order_status_id = $request->order_status_id;

                $orders = Order::where('order_status_id', $order_status_id)->orderBy('id', 'DESC')->get();
            }
            if (empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;
                $orders = Order::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            }
            if (empty($request->order_status_id) && (empty($request->date_from) || empty($request->date_to))) {
                $orders = Order::orderBy('id', 'DESC')->get();
            }

            // 2nd step filter
            $district_id = $request->district_id;

            if (!empty($request->district_id) && !empty($request->area_id)) {

                $orders = $orders->where('district_id', $request->district_id)->where('area_id', $request->area_id);
            }
            if (!empty($request->district_id) && empty($request->area_id)) {
                $orders = $orders->where('district_id', $request->district_id);
            }

            $orders = $orders->where('is_final', 0);

            if ($request->ajax()) {
                return Datatables::of($orders)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('order.edit', $row->id) . '">' . $row->code . '</a>';

                        return $code;
                    })
                    ->addColumn('status', function ($row) {

                        $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                          <a href="' . route('order.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['code', 'status', 'date', 'action'])
                    ->make(true);
            }
            return view('admin.order.index', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function sell_search(Request $request) {
        if (auth()->user()->can('order.index')) {
            if (!empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;

                $orders = Order::where('order_status_id', $order_status_id)
                    ->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            }
            if ((!empty($request->order_status_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->date_from) && !empty($request->date_to))) {

                $order_status_id = $request->order_status_id;

                $orders = Order::where('order_status_id', $order_status_id)->orderBy('id', 'DESC')->get();
            }
            if (empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;
                $orders = Order::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();
            }
            if (empty($request->order_status_id) && (empty($request->date_from) || empty($request->date_to))) {
                $orders = Order::orderBy('id', 'DESC')->get();
            }

            // 2nd step filter
            $district_id = $request->district_id;

            if (!empty($request->district_id) && !empty($request->area_id)) {

                $orders = $orders->where('district_id', $request->district_id)->where('area_id', $request->area_id);
            }
            if (!empty($request->district_id) && empty($request->area_id)) {
                $orders = $orders->where('district_id', $request->district_id);
            }

            $orders = $orders->where('is_final', 1);

            if ($request->ajax()) {
                return Datatables::of($orders)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('order.edit', $row->id) . '">' . $row->code . '</a>';

                        return $code;
                    })
                    ->addColumn('status', function ($row) {

                        $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                          <a href="' . route('order.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['code', 'status', 'date', 'action'])
                    ->make(true);
            }
            return view('admin.order.sell.index', compact('orders'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::find($id);
            if (!is_null($order)) {
                return view('admin.order.edit', compact('order'));
            } else {
                Alert::toast('Order Not Found', 'error');
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (auth()->user()->can('order.delete')) {
            $order = Order::find($id);
            if (!is_null($order)) {
                foreach ($order->order_product as $product) {
                    $product->delete();
                }
                $order->delete();
                Alert::toast('Order deleted successfully!', 'success');
                return redirect()->route('order.index');
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function change_status(Request $request, $id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::find($id);
            if (!is_null($order)) {
                if ($request->order_status_id == 5) {
                    $validatedData = $request->validate(
                        [
                            'note' => 'required',
                        ],
                        [
                            'note.required' => 'Please Provide a Cancellation Reason',
                        ]
                    );
                }
                $order->order_status_id = $request->order_status_id;
                $order->note = $request->note;
                $order->save();
                // $msg = 'Dear Sir/Madam, Your order('. $order->code .') status has been updated to '.$order->status->title.'. Thanks for shopping with us.';
                // $send_sms = $order->send_sms($msg, $order->phone);

                Alert::toast('Status Updated !', 'success');
                return back();
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function change_payment_status(Request $request, $id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::find($id);
            if (!is_null($order)) {
                $order->payment_status = $request->payment_status;
                $order->save();
                Alert::toast('Status Updated !', 'success');
                return back();
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function convert_sell($id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::find($id);
            if (!is_null($order)) {
                $order_products = $order->order_product;
                foreach ($order_products as $product) {
                    $stock = ProductStock::where('product_id', $product->product_id)->where('size_id', $product->size_id)->first();
                    $stock->qty -= $product->qty;
                    $stock->save();
                }
                $order->is_final = 1;
                $order->save();
                Alert::toast('Sell Updated !', 'success');
                return redirect()->route('order.index');
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function take_advance(Request $request, $id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::find($id);
            if (!is_null($order)) {
                $validatedData = $request->validate([
                    'amount' => 'required|numeric',
                ]);
                $order->advance = $request->amount;
                $order->save();
                Alert::toast('Advance Amount Received', 'success');
                return back();
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function generate_invoice($id) {
        $order = Order::find($id);
        if (!is_null($order)) {
            $pdf = PDF::loadView('admin.invoice.generate', compact('order'));
            return $pdf->stream($order->code . '.pdf');
        } else {
            Alert::toast('Invoice Not Found!', 'error');
            return back();
        }
    }

    public function generate_pos_invoice($id) {
        $order = Order::find($id);
        if (!is_null($order)) {
            return view('admin.invoice.pos-generate', compact('order'));
        } else {
            Alert::toast('Invoice Not Found!', 'error');
            return back();
        }
    }

    public function orders_by_status(Request $request, $id) {
        if (auth()->user()->can('order.index')) {
            $orders = Order::where('order_status_id', $id)->orderBy('id', 'DESC')->get();
            if ($request->ajax()) {
                $data = Order::where('order_status_id', $id)->orderBy('id', 'DESC')->where('is_final', 0)->get();
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('order.edit', $row->id) . '">' . $row->code . '</a>';

                        return $code;
                    })
                    ->addColumn('status', function ($row) {

                        $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

                        return $data;
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                          <a href="' . route('order.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';

                        return $btn;
                    })
                    ->rawColumns(['code', 'status', 'date', 'action'])
                    ->make(true);
            }
            return view('admin.order.all-orders', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function return($id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::where('id', $id)->where('is_final', 1)->first();
            if (!is_null($order)) {
                return view('admin.order.sell.return.create', compact('order'));
            } else {
                Alert::toast('Sell Not Found', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function apply_cod(Request $request, $id) {
        $order = Order::find($id);
        if (!is_null($order)) {
            if ($request->submit == 'apply') {
                $order->cod = $order->price * 0.01;
                $order->save();
                Alert::toast('COD applied successfully', 'success');
                return back();
            } else {
                $order->cod = 0;
                $order->save();
                Alert::toast('COD remove successfully', 'success');
                return back();
            }
        } else {
            Alert::toast('Order Not Found!', 'error');
            return back();
        }
    }
}
