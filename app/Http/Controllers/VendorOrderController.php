<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductStock;
use App\Models\WorkTrackingEntry;
use App\Models\OrderProduct;
use App\Models\ProductStockHistory;
use Illuminate\Http\Request;

use Auth;
use Session;
use Alert;
use App\Models\Bank;
use App\Models\Category;
use App\Models\OrderReturn;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use App\Exports\SellListExport;
use App\Exports\WholeSaleListExport;
use App\Models\CourierName;
use App\Models\Setting;
use App\Models\VatEntry;
use Maatwebsite\Excel\Facades\Excel;

class VendorOrderController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request) {
    //     $date_from = '';
    //     $date_to = '';

    //     if (auth()->user()->can('order.index')) {
    //         $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->where('is_final', 0)->get();
    //         if ($request->ajax()) {
    //             $data = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->where('is_final', 0)->get();
    //             return Datatables::of($data)
    //                 // ->addIndexColumn()
    //                 ->addColumn('code', function ($row) {

    //                     $code = '<a href="' . route('vendor_sell.edit', $row->id) . '">' . $row->code . '</a>';

    //                     return $code;
    //                 })
    //                 ->addColumn('status', function ($row) {

    //                     $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';

    //                     return $data;
    //                 })
    //                 ->addColumn('date', function ($row) {

    //                     $data = Carbon::parse($row->created_at)->format('d M, Y g:iA');

    //                     return $data;
    //                 })
    //                 ->addColumn('action', function ($row) {

    //                     $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
    //                       <a href="' . route('vendor_sell.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';

    //                     return $btn;
    //                 })
    //                 ->rawColumns(['code', 'status', 'date', 'action'])
    //                 ->make(true);
    //         }
    //         return view('admin.order.index', compact('orders', 'date_from', 'date_to'));
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }

    public function sell_index(Request $request) {
        $date_from = '';
        $date_to = '';
        $order_status_id = '';
        $courier_name = '';

        if (auth()->user()->can('sell.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->where('is_final', 1)->where('source', '!=', 'Wholesale')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();
            if ($request->ajax()) {
                $data = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->where('is_final', 1)->where('source', '!=', 'Wholesale')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->take(1000)->get();
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('vendor_sell.edit', $row->id) . '">' . $row->code . '</a>';

                        return $code;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->is_return == 1) {
                            $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span> <br> <span class="badge badge-danger">Returned</span>';
                        } elseif ($row->is_return == 2) {
                            $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span> <br> <span class="badge badge-danger">Returned Partially</span>';
                        } else {
                            $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';
                        }

                        return $data;
                    })
                    ->addColumn('cod', function ($row) {

                        $data = $row->cod;

                        return env('CURRENCY') . $data;
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
                        if (!Auth::user()->vendor) {
                            if ($row->price > 0) {
                                $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                               <a href="' . route('order.invoice.pos.generate', $row->id) . '" class="btn btn-success" title="Print Invoice"><i class="fas fa-print"></i></a>
                          <a href="' . route('vendor_sell.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                          <a href="' . route('order.return', $row->id) . '" class="btn btn-danger" title="Product Return"><i class="fas fa-undo"></i></a>';
                            } else {
                                $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                               <a href="' . route('order.invoice.pos.generate', $row->id) . '" class="btn btn-success" title="Print Invoice"><i class="fas fa-print"></i></a>
                          <a href="' . route('vendor_sell.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                            }
                        } else {
                            if ($row->price > 0) {
                                $btn = '<a href="' . route('order.invoice.pos.generate', $row->id) . '" class="btn btn-success" title="Print Invoice"><i class="fas fa-print"></i></a>
                          <a href="' . route('vendor_sell.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                          <a href="' . route('order.return', $row->id) . '" class="btn btn-danger" title="Product Return"><i class="fas fa-undo"></i></a>';
                            } else {
                                $btn = '<a href="' . route('order.invoice.pos.generate', $row->id) . '" class="btn btn-success" title="Print Invoice"><i class="fas fa-print"></i></a>
                          <a href="' . route('vendor_sell.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                            }
                        }

                        return $btn;
                    })
                    ->rawColumns(['code', 'status', 'date', 'action'])
                    ->make(true);
            }
            $categories = Category::all();
            return view('admin.order.sell.index', compact('orders', 'categories', 'date_from', 'date_to', 'order_status_id', 'courier_name'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function sell_export_excel() {
        return Excel::download(new SellListExport, 'sell_list.xlsx');
    }

    public function sell_export_excel2(Request $request, $all) {
        $date_from = '';
        $date_to = '';
        $order_status_id = '';
        $courier_name = '';
        if (auth()->user()->can('sell.index')) {
            if ($all != 0) {
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('is_final', 1)->where('order_status_id', '!=', 5)->where('source', '!=', 'Wholesale')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->latest()->paginate(10);
            } else {
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('is_final', 1)->where('order_status_id', '!=', 5)->where('source', '!=', 'Wholesale')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->latest()->take(1000)->get();
            }

            $categories = Category::all();

            return view('admin.order.sell.index2', compact('orders', 'categories', 'date_from', 'date_to', 'order_status_id', 'courier_name', 'all'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Calculate VAT for the given order ID.
     *
     * @param int $id The ID of the order
     * @throws \Illuminate\Auth\Access\AuthorizationException Unauthorized action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vat_calculate($id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::where('vendor_id', Auth::user()->vendor->id)->with('order_product')->find($id);
            $sold_amount = $order->sold_amount();
            $vat = Setting::first()->vat;
            $vat_amount = ($sold_amount * $vat) / 100;

            if (!is_null($order)) {
                $vat_entry = new VatEntry();
                $vat_entry->date_of_sell = Carbon::parse($order->created_at)->format('Y-m-d');
                $vat_entry->order_id = $order->id;
                $vat_entry->is_paid = 0;
                $vat_entry->vat_amount = $vat_amount;
                $vat_entry->save();
                Alert::toast('Vat Entry created successfully!', 'success');
                return back();
            } else {
                Alert::toast('Order Not Found', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function sell_report(Request $request) {
        $date_from = '';
        $date_to = '';
        $order_status_id = '';

        if (auth()->user()->can('sell.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->where('is_final', 1)->with('order_product', 'order_product.product')->get();
            $categories = Category::all();
            return view('admin.order.sell.sell-report', compact('orders', 'categories', 'date_from', 'date_to', 'order_status_id'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function report_search(Request $request) {
        $date_from = '';
        $date_to = '';
        $order_status_id = '';

        if (auth()->user()->can('sell.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->where('is_final', 1)->get();
            if (!empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;

                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('order_status_id', $order_status_id)
                    ->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if ((!empty($request->order_status_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->date_from) && !empty($request->date_to))) {

                $order_status_id = $request->order_status_id;

                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('order_status_id', $order_status_id)->orderBy('id', 'DESC')->get();
            }
            if (empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if (empty($request->order_status_id) && (empty($request->date_from) || empty($request->date_to))) {
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->get();
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

            $categories = Category::all();
            return view('admin.order.sell.sell-report', compact('orders', 'categories', 'date_from', 'date_to', 'order_status_id'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function all_orders() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->get();
            return view('admin.order.all-orders', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function current_year() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->whereYear('created_at', Carbon::now()->year)->get();
            return view('admin.order.current-year', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function current_month() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->whereMonth('created_at', Carbon::now()->month)->get();
            return view('admin.order.current-month', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function today() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->whereDate('created_at', Carbon::today())->get();
            return view('admin.order.today', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function yesterday() {
        if (auth()->user()->can('order.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->whereDate('created_at', Carbon::yesterday())->get();
            return view('admin.order.yesterday', compact('orders'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function customer_orders($id) {
        $customer = User::find($id);

        if (auth()->user()->can('order.index')) {
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->where('customer_id', $id)->get();
            return view('admin.order.orders_customer', compact('orders', 'customer'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function sell_search(Request $request) {
        $date_from = '';
        $date_to = '';
        $order_status_id = '';
        $courier_name = '';

        if (auth()->user()->can('order.index')) {
            if (!empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;

                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('order_status_id', $order_status_id)
                    ->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if ((!empty($request->order_status_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->date_from) && !empty($request->date_to))) {

                $order_status_id = $request->order_status_id;

                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('order_status_id', $order_status_id)->orderBy('id', 'DESC')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();
            }
            if (empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if (empty($request->order_status_id) && (empty($request->date_from) || empty($request->date_to))) {
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();
            }

            // 2nd step filter
            $district_id = $request->district_id;

            if (!empty($request->district_id) && !empty($request->area_id)) {

                $orders = $orders->where('district_id', $request->district_id)->where('area_id', $request->area_id);
            }
            if (!empty($request->district_id) && empty($request->area_id)) {
                $orders = $orders->where('district_id', $request->district_id);
            }

            if (!empty($request->courier_name)) {
                $courier_name = $request->courier_name;

                $orders = $orders->where('courier_name', $courier_name);
            }

            $orders = $orders->where('is_final', 1)->where('source', '!=', 'Wholesale');

            if ($request->ajax()) {
                return Datatables::of($orders)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('vendor_sell.edit', $row->id) . '">' . $row->code . '</a>';

                        return $code;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->is_return == 1) {
                            $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span> <br> <span class="badge badge-danger">Returned</span>';
                        } elseif ($row->is_return == 2) {
                            $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span> <br> <span class="badge badge-danger">Returned Partially</span>';
                        } else {
                            $data = '<span class="badge badge-' . $row->status->color . '">' . $row->status->title . '</span>';
                        }

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
                        if ($row->price > 0) {
                            $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                               <a href="' . route('order.invoice.pos.generate', $row->id) . '" class="btn btn-success" title="Print Invoice"><i class="fas fa-print"></i></a>
                          <a href="' . route('vendor_sell.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                          <a href="' . route('order.return', $row->id) . '" class="btn btn-danger" title="Product Return"><i class="fas fa-undo"></i></a>';
                        } else {
                            $btn = '<a href="' . route('order.invoice.generate', $row->id) . '" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                               <a href="' . route('order.invoice.pos.generate', $row->id) . '" class="btn btn-success" title="Print Invoice"><i class="fas fa-print"></i></a>
                          <a href="' . route('vendor_sell.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                        }

                        return $btn;
                    })
                    ->rawColumns(['code', 'status', 'date', 'action'])
                    ->make(true);
            }
            $categories = Category::all();
            return view('admin.order.sell.index', compact('orders', 'categories', 'date_from', 'date_to', 'order_status_id', 'courier_name'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function sell_search_export(Request $request, $all) {
        $date_from = '';
        $date_to = '';
        $order_status_id = '';
        $courier_name = '';

        if (auth()->user()->can('order.index')) {
            if (!empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;

                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('order_status_id', $order_status_id)
                    ->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if ((!empty($request->order_status_id) && empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && !empty($request->date_from) && empty($request->date_to)) || (!empty($request->order_status_id) && empty($request->date_from) && !empty($request->date_to))) {

                $order_status_id = $request->order_status_id;

                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('order_status_id', $order_status_id)->orderBy('id', 'DESC')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();
            }
            if (empty($request->order_status_id) && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_from . ' 00:00:00');
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date_to . ' 23:59:59');
                $order_status_id = $request->order_status_id;
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'DESC')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if (empty($request->order_status_id) && (empty($request->date_from) || empty($request->date_to))) {
                $orders = Order::where('vendor_id', Auth::user()->vendor->id)->orderBy('id', 'DESC')->with('order_product', 'order_product.product', 'status', 'created_by', 'vat_entry')->get();
            }

            // 2nd step filter
            $district_id = $request->district_id;

            if (!empty($request->district_id) && !empty($request->area_id)) {

                $orders = $orders->where('district_id', $request->district_id)->where('area_id', $request->area_id);
            }
            if (!empty($request->district_id) && empty($request->area_id)) {
                $orders = $orders->where('district_id', $request->district_id);
            }

            if (!empty($request->courier_name)) {
                $courier_name = $request->courier_name;

                $orders = $orders->where('courier_name', $courier_name);
            }

            if ($all != 0) {
                $orders = $orders->where('is_final', 1)->where('order_status_id', '!=', 5)->where('source', '!=', 'Wholesale');
            } else {
                $orders = $orders->where('is_final', 1)->where('order_status_id', '!=', 5)->where('source', '!=', 'Wholesale')->take(500);
            }

            $categories = Category::all();
            return view('admin.order.sell.index2', compact('orders', 'categories', 'date_from', 'date_to', 'order_status_id', 'courier_name', 'all'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (auth()->user()->can('order.edit')) {
            $products = ProductStock::orderBy('id', 'DESC')->with('product', 'size')->where('vendor_id', Auth::user()->vendor->id)->get();
            $order = Order::with('status', 'order_product', 'order_product.product', 'order_product.product.variation', 'order_product.size')->find($id);
            $couriers = CourierName::all();

            if (!is_null($order)) {
                return view('admin.order.edit', compact('order', 'couriers', 'products'));
            } else {
                Alert::toast('Order Not Found', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
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
    /**
     * Change the status of an order based on the provided request and order ID.
     *
     * @param Request $request The HTTP request containing the new order status and note
     * @param int $id The ID of the order to be updated
     * @throws \Illuminate\Auth\Access\AuthorizationException If the user is not authorized to update the order
     * @return \Illuminate\Http\RedirectResponse
     */
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

                    if ($order->is_final == 1) {
                        $order_products = $order->order_product;
                        foreach ($order_products as $product) {
                            $stock = ProductStock::where('product_id', $product->product_id)->where('size_id', $product->size_id)->first();
                            $stock->qty += $product->qty;
                            $stock->save();

                            $history = new ProductStockHistory;
                            $history->product_id = $product->product_id;
                            $history->size_id = $product->size_id;
                            $history->qty = $product->qty;
                            $history->remarks = 'Order Code - ' . $order->code;
                            $history->note = "Order Cancel";
                            $history->save();
                        }
                        $order->is_final = 0;
                    }
                } else if ($request->order_status_id == 6) {
                    WorkTrackingEntry::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'work_name' => 'print_memo'
                    ]);
                }

                $order->order_status_id = $request->order_status_id;
                $order->note = $request->note;
                $order->save();
                // $msg = 'Dear Sir/Madam, Your order('. $order->code .') status has been updated to '.$order->status->title.'. Thanks for shopping with us.';
                // $send_sms = $order->send_sms($msg, $order->phone);

                Alert::toast('Status Updated!', 'success');
                return back();
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function discount_amount_update(Request $request, $id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::with('order_product')->find($id);
            if (!is_null($order)) {
                $total_price = $order->price + $order->discount_amount ?? 0;

                $order->discount_amount = $request->discount_amount;
                $order->price = $total_price - $request->discount_amount;
                $order->save();

                $percentage = ($request->discount_amount / $total_price) * 100;

                foreach ($order->order_product as $product) {
                    if ($product->qty > 0) {
                        if ($order->source == 'Wholesale') {
                            $product->price = round($product->stock()->wholesale_price - ($product->stock()->wholesale_price * ($percentage / 100)));
                        } else {
                            if ($product->stock()->discount_price != null && $order->source == 'Website') {
                                $product->price = round($product->stock()->discount_price);
                            } else {
                                $product->price = round($product->stock()->price - ($product->stock()->price * ($percentage / 100)));
                            }
                        }
                        $product->save();
                    }
                }

                Alert::toast('Discount Amount Updated!', 'success');

                return back();
            } else {
                Alert::toast('Something Went Wrong!', 'error');
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

                if ($request->payment_status) {
                    $order->order_status_id = 9;

                    WorkTrackingEntry::where('order_id', $order->id)->where('user_id', Auth::id())->where('work_name', 'order_paid')->delete();

                    WorkTrackingEntry::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'work_name' => 'order_paid'
                    ]);
                } else {
                    $order->order_status_id = 8;

                    WorkTrackingEntry::where('order_id', $order->id)->where('user_id', Auth::id())->where('work_name', 'order_paid')->delete();
                }

                $order->save();
                Alert::toast('Status Updated!', 'success');
                return back();
            } else {
                Alert::toast('Something went wrong !', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * A PHP function that updates order products based on the given request and order ID.
     *
     * @param Request $request The request containing the product quantities and details
     * @param int $id The ID of the order to update
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function order_products_update(Request $request, $id) {

        $order = Order::find($id);
        $discount = $order->discount_amount ?? 0;
        $new_total = 0;

        $i = 0;

        for ($i = 0; $i < count($request->qty); $i++) {

            if ($i < count($request->qty) - 1 && $request->qty[$i] == null) {
                return back()->with('qtyError' . $i, 'Quantity is required!');
            }

            $product_stock = ProductStock::find($request->product[$i]);

            if ($request->qty[$i] > 0) {
                if ($order->source == 'Wholesale') {
                    $new_total += $request->qty[$i] * $product_stock->wholesale_price;
                } else {
                    if ($product_stock->discount_price != null && $order->source == 'Website') {
                        $new_total += $request->qty[$i] * $product_stock->discount_price;
                    } else {
                        $new_total += $request->qty[$i] * $product_stock->price;
                    }
                }
            }
        }

        // delete the old order products
        $old_order_products = OrderProduct::where('order_id', $id);
        foreach ($old_order_products->get() as $product) {
            $stock = ProductStock::where('product_id', $product->product_id)->where('size_id', $product->size_id)->first();
            $stock->qty += $product->qty;
            $stock->save();

            $history = new ProductStockHistory;
            $history->product_id = $product->product_id;
            $history->size_id = $product->size_id;
            $history->qty = $product->qty;
            $history->remarks = 'Order Code - ' . $order->code;
            $history->note = "Order Products Change";
            $history->save();
        }

        $old_order_products->delete();

        $order->price = $new_total - $discount;
        $order->save();
        $percentage = ($discount / $new_total) * 100;

        for ($i = 0; $i < count($request->qty); $i++) {
            $product_stock = ProductStock::find($request->product[$i]);

            if ($request->qty[$i] > 0) {
                $order_product = new OrderProduct;
                $order_product->order_id = $id;
                $order_product->product_id = $product_stock->product->id;
                $order_product->size_id = $product_stock->size_id;
                if ($order->source == 'Wholesale') {
                    $order_product->price = round($product_stock->wholesale_price - ($product_stock->wholesale_price * ($percentage / 100)));
                } else {
                    if ($product_stock->discount_price != null && $order->source == 'Website') {
                        $order_product->price = round($product_stock->discount_price);
                    } else {
                        $order_product->price = round($product_stock->price - ($product_stock->price * ($percentage / 100)));
                    }
                }
                $order_product->production_cost = $product_stock->production_cost;
                $order_product->qty = $request->qty[$i];
                $order_product->save();

                $product_stock->qty -= $order_product->qty;
                $product_stock->save();

                $history = new ProductStockHistory;
                $history->product_id = $order_product->product_id;
                $history->size_id = $order_product->size_id;
                $history->qty = $order_product->qty;
                $history->remarks = 'Order Code - ' . $order->code;
                $history->note = "Order Products Added";

                $history->save();
            }
        }

        Alert::toast('Order Products Updated.', 'success');
        return back();
    }

    public function generate_invoice($id) {
        $order = Order::find($id);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'default_font' => 'nikosh',
        ]);

        if (!is_null($order)) {
            $data = [
                'order' => $order
            ];

            $mpdf->WriteHTML(view('admin.invoice.generate', $data));
            $mpdf->Output($order->code . '.pdf', 'I');
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
            $orders = Order::where('vendor_id', Auth::user()->vendor->id)->where('order_status_id', $id)->orderBy('id', 'DESC')->get();
            if ($request->ajax()) {
                $data = Order::where('vendor_id', Auth::user()->vendor->id)->where('order_status_id', $id)->orderBy('id', 'DESC')->where('is_final', 0)->get();
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('code', function ($row) {

                        $code = '<a href="' . route('vendor_sell.edit', $row->id) . '">' . $row->code . '</a>';

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
                          <a href="' . route('vendor_sell.edit', $row->id) . '" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';

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
        if (auth()->user()->can('order.return')) {
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

        if (auth()->user()->can('apply.cod')) {
            $return_products = OrderReturn::where('order_id', $id);
            $return_price = 0;

            if ($return_products->exists()) {
                foreach ($return_products->get() as $key => $product) {
                    $return_price += $product->price * $product->qty;
                };
            }

            $cod = ($order->price - $return_price) * 0.01;

            if (!is_null($order)) {
                if ($request->submit == 'apply') {
                    $order->cod = $order->cod ? $order->cod + $cod : $cod;
                    $order->price -= $cod;
                    $order->save();

                    WorkTrackingEntry::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'work_name' => 'apply_cod'
                    ]);

                    Alert::toast('COD applied successfully', 'success');
                    return back();
                } else {
                    $order->price += $order->cod;
                    $order->cod = 0;
                    $order->save();
                    Alert::toast('COD removed successfully', 'success');
                    return back();
                }
            } else {
                Alert::toast('Order Not Found!', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function remove_discount($id) {
        if (auth()->user()->can('order.edit')) {
            $order = Order::find($id);

            if (!is_null($order)) {
                $order_products = $order->order_product;
                foreach ($order_products as $product) {
                    $stock = ProductStock::where('product_id', $product->product_id)->where('size_id', $product->size_id)->first();

                    $product->price = $stock->price;
                    $product->save();
                }

                $order->discount_amount = null;
                $order->save();
                Alert::toast('Discount Removed!', 'success');

                return back();
            } else {
                Alert::toast('Something went wrong!', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function remove_loss($id) {
        if (auth()->user()->can('order.edit') || auth()->user()->can('add.loss')) {
            $order = Order::find($id);

            if (!is_null($order)) {
                $order->add_loss = 0;
                $order->save();

                Alert::toast('Loss Removed!', 'success');

                return back();
            } else {
                Alert::toast('Something went wrong!', 'error');
                return back();
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
