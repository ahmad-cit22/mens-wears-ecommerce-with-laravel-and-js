<?php

namespace App\Http\Controllers;

use App\Models\MembershipCard;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MembershipCardController extends Controller {

    public function cards(Request $request) {
        $cards = MembershipCard::all();

        if (auth()->user()->can('membership.cards')) {
            return view('admin.membership.cards', compact('cards'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function card_update(Request $request, $id) {
        if (auth()->user()->can('membership.update')) {

            $validatedData = $request->validate([
                'card_status' => 'required|max:255',
                'discount_rate' => 'required',
                'point_percentage' => 'required',
                'min_point' => 'required',
            ]);

            // update membership card
            $card = MembershipCard::find($id);

            if ($card) {
                $card->card_status = $request->card_status;
                $card->discount_rate = $request->discount_rate;
                $card->min_purchase = $request->min_purchase ?? null;
                $card->point_percentage = $request->point_percentage;
                $card->min_point = $request->min_point;
                $card->save();

                Alert::toast('Membership Card Updated', 'success');
                return back();
            }
            Alert::toast('Membership Card Not Found', 'error');
            return back();
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

    //     if (auth()->user()->can('vat.calculate')) {
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
        if (auth()->user()->can('vat.calculate')) {
            $bkash_nums = BkashNumber::all();
            $bkash_purposes = BkashRecordPurpose::all();

            return view('admin.bkash-panel.create', compact('bkash_nums', 'bkash_purposes'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request) {
    //     if (auth()->user()->can('business_bkash_number.index')) {
    //         $validatedData = $request->validate([
    //             'status' => 'required|not_in:0',
    //             'tr_type' => 'required|not_in:0',
    //             'amount' => 'required',
    //             'tr_purpose_id' => 'required|not_in:0',
    //             'last_digit' => 'required',
    //         ]);

    //         $bkash_record = new BkashRecord;
    //         $bkash_record->status = $request->status;
    //         $bkash_record->tr_type = $request->tr_type;
    //         $bkash_record->amount = $request->amount;
    //         $bkash_record->tr_purpose_id = $request->tr_purpose_id;
    //         $bkash_record->last_digit = $request->last_digit;
    //         $bkash_record->comments = $request->comments;
    //         $bkash_record->save();

    //         WorkTrackingEntry::create([
    //             'bkash_record_id' => $bkash_record->id,
    //             'user_id' => Auth::id(),
    //             'work_name' => 'bkash_record'
    //         ]);
    //         Alert::toast('New Bkash Record Added!', 'success');
    //         // return redirect()->route('bkash_panel.create');
    //         return back();
    //     } else {
    //         abort(403, 'Unauthorized action.');
    //     }
    // }

    /**
     * Mark the vat entry as paid.
     * @param int $id ID of the vat entry to be marked as paid
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function paid($id) {
    //     $this->authorize('vat.calculate');

    //     $vat_entry = VatEntry::find($id);

    //     if (!is_null($vat_entry)) {
    //         $vat_entry->is_paid = 1;
    //         $vat_entry->save();

    //         Alert::toast('Vat Entry Paid!', 'success');

    //         return back();
    //     }
    //     Alert::toast('Vat Entry Not Found!', 'error');

    //     return back();
    // }

    // // destroy function for vat entry
    // public function destroy($id) {
    //     $this->authorize('vat-entry.delete');
    //     $vat_entry = VatEntry::find($id);

    //     if ($vat_entry) {
    //         $vat_entry->delete();

    //         Alert::toast('Vat Entry Deleted!', 'success');
    //         return back();
    //     }

    //     Alert::toast('Vat Entry Not Found!', 'error');
    //     return back();
    // }
}
