<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipCard;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $members = Member::with('customer', 'customer.orders', 'customer.district', 'card')->latest()->get();

        if (auth()->user()->can('membership.cards')) {
            if ($request->ajax()) {
                return DataTables::of($members)
                    // ->addIndexColumn()
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->created_at)->format('d M, Y');

                        return $data;
                    })
                    ->addColumn('name', function ($row) {

                        $data = $row->customer->name;

                        return $data;
                    })
                    ->addColumn('phone', function ($row) {

                        $data = $row->customer->phone;

                        return $data;
                    })
                    ->addColumn('address', function ($row) {

                        $data = $row->customer->district ? $row->customer->address . ', ' . $row->customer->district->name : $row->customer->address;

                        return $data;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->membership_card_id == 1) {
                            $data = '<span class="badge badge-info">' . $row->card->card_status . '</span>';
                        } elseif ($row->membership_card_id == 2) {
                            $data = '<span class="badge badge-primary">' . $row->card->card_status . '</span>';
                        } else {
                            $data = '<span class="badge badge-success">' . $row->card->card_status . '</span>';
                        }

                        return $data;
                    })
                    ->addColumn('discount_rate', function ($row) {

                        $data = $row->card->discount_rate . '%';

                        return $data;
                    })
                    ->addColumn('purchase', function ($row) {

                        $data = '&#2547; ' . $row->customer->orders->sum('price');

                        return $data;
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="' . route('membership.edit', $row->id) . '" class="btn btn-primary btn-sm" title="Membership Card Details"><i class="fas fa-edit"></i></a><a href="#deleteModal' . $row->id . '" class="btn btn-danger btn-sm ml-1" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal' . $row->id . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Are tou sure you want to delete this entry?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="' . route('membership.destroy', $row->id) . '" method="POST">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>';

                        return $btn;
                    })
                    ->rawColumns(['date', 'status', 'purchase', 'discount_rate', 'action'])
                    ->make(true);
            }
            $customers = User::where('type', 2)->latest()->get();
            $cards = MembershipCard::all();

            return view('admin.membership.index', compact('members', 'customers', 'cards'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    // public function search(Request $request) {
    //     $vat_entries = VatEntry::with('order')->latest()->get();
    //     $total_outstanding = 0;
    //     $total_paid = 0;
    //     $status = '';
    //     $date_from = '';
    //     $date_to = '';

    //     if (auth()->user()->can('membership.cards')) {
    //         if ($request->status != '' && !empty($request->date_from) && !empty($request->date_to)) {
    //             $start_date = $request->date_from;
    //             $end_date = $request->date_to;
    //             $status = $request->status;

    //             $vat_entries = VatEntry::where('is_paid', $status)
    //                 ->whereBetween('date_of_sell', [$start_date, $end_date])->with('order')->latest()->get();

    //             $date_from = $request->date_from;
    //             $date_to = $request->date_to;
    //         }
    //         if (($request->status != '' && empty($request->date_from) && empty($request->date_to)) || ($request->status != '' && !empty($request->date_from) && empty($request->date_to)) || ($request->status != '' && empty($request->date_from) && !empty($request->date_to))) {

    //             $status = $request->status;

    //             $vat_entries = VatEntry::where('is_paid', $status)->with('order')->latest()->get();
    //         }
    //         if ($request->status == '' && !empty($request->date_from) && !empty($request->date_to)) {
    //             $start_date = $request->date_from;
    //             $end_date = $request->date_to;
    //             $vat_entries = VatEntry::whereBetween('date_of_sell', [$start_date, $end_date])->with('order')->latest()->get();

    //             $date_from = $request->date_from;
    //             $date_to = $request->date_to;
    //         }

    //         $total_outstanding = $vat_entries->where('is_paid', 0)->sum('vat_amount');
    //         $total_paid = $vat_entries->where('is_paid', 1)->sum('vat_amount');

    //         return view('admin.vat-entry.index', compact('vat_entries', 'total_outstanding', 'total_paid', 'status', 'date_from', 'date_to'));
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }

    public function create() {
        if (auth()->user()->can('membership.cards')) {
            $customers = User::where('type', 2)->latest()->get();
            $cards = MembershipCard::all();

            return view('admin.membership.create', compact('customers', 'cards'));
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
        if (auth()->user()->can('membership.update')) {
            $validatedData = $request->validate([
                'user_id' => 'required|not_in:0',
                'membership_card_id' => 'required|not_in:0',
                'card_number' => 'required',
            ]);

            $customer = User::find($request->user_id);
            $total_purchase_amount = $customer->orders ? $customer->orders->sum('price') : null;

            $point_percentage = MembershipCard::find($request->membership_card_id)->point_percentage;

            // store a new member
            $member = new Member;
            $member->user_id = $request->user_id;
            $member->membership_card_id = $request->membership_card_id;
            $member->card_number = $request->card_number;

            if ($total_purchase_amount) {
                $member->current_points = round($total_purchase_amount * ($point_percentage / 100));
            }

            $member->save();

            Alert::toast('New Member Added!', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Mark the vat entry as paid.
     * @param int $id ID of the vat entry to be marked as paid
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id) {
        $this->authorize('membership.update');

        $member = Member::where('id', $id)->with('customer', 'customer.orders', 'customer.district', 'card')->first();
        $members = Member::with('customer')->latest()->get();

        if ($member) {
            return view('admin.membership.edit', compact('member', 'members'));
        }
        Alert::toast('Member Not Found', 'error');
        return back();
    }


    public function get_member(Request $request) {
        $this->authorize('membership.update');

        $user = User::where('phone', $request->phone)->first();
        $member = Member::where('user_id', $user->id)->with('customer', 'customer.orders', 'customer.district', 'card')->first();

        if ($member) {
            return response()->json($member->id);
        }
        return response()->json(['errMsg' => 'Member Not Found!']);
    }

    public function destroy($id) {
        $this->authorize('membership.update');

        $member = Member::find($id);
        if ($member) {
            $member->delete();
            Alert::toast('Member Deleted!', 'success');
            return back();
        }
        Alert::toast('Member Not Found!', 'error');
        return back();
    }

    public function purchases(Request $request) {
        $members = Member::with('customer', 'customer.orders')->latest()->get();
        $data = [];
        $sl = 0;

        foreach ($members as $key => $member) {
            // dd($member->customer->orders);
            if ($member->customer->orders->count() > 0) {
                $sl++;
                $orders = $member->customer->orders->toArray();
                array_push($orders, ['key' => $sl]);

                $data[] = collect($orders);
            }
        }
        $data = array_reverse($data);
        $data = collect($data);

        // return $data;

        if (auth()->user()->can('membership.cards')) {
            if ($request->ajax()) {
                return DataTables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('id', function ($row) {

                        $data = $row->last()['key'];

                        return $data;
                    })
                    ->addColumn('date', function ($row) {

                        $data = Carbon::parse($row->first()['created_at'])->format('d M, Y');

                        return $data;
                    })
                    ->addColumn('card_number', function ($row) {

                        $data = '<a class="" href="' . route('membership.edit',  $row->first()['customer']['member']['id']) . '">' .
                            $row->first()['customer']['member']['card_number'] . '</a>';

                        return $data;
                    })
                    ->addColumn('name', function ($row) {

                        $data = $row->first()['customer']['name'];

                        return $data;
                    })
                    ->addColumn('phone', function ($row) {

                        $data = $row->first()['customer']['phone'];

                        return $data;
                    })
                    ->addColumn('memo_number', function ($row) {

                        $data = '<a class="" href="' . route('order.edit',  $row->first()['id']) . '">' .
                            $row->first()['code'] . '</a>';

                        return $data;
                    })
                    ->addColumn('purchase_amount', function ($row) {

                        $data = '&#2547; ' .  $row->first()['price'];

                        return $data;
                    })
                    ->addColumn('discount_rate', function ($row) {

                        $data = $row->first()['discount_rate'] . '%';

                        return $data;
                    })
                    ->addColumn('discount_amount', function ($row) {

                        $data = '&#2547; ' .  $row->first()['membership_discount'];

                        return $data;
                    })
                    ->addColumn('points_used', function ($row) {

                        $data = $row->first()['points_used'];

                        return $data;
                    })
                    ->rawColumns(['date', 'memo_number', 'card_number', 'discount_rate', 'purchase_amount', 'discount_amount'])
                    ->make(true);
            }

            return view('admin.membership.purchases', compact('members'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
