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

    public function card_store(Request $request) {
        if (auth()->user()->can('membership.update')) {

            $validatedData = $request->validate([
                'card_status' => 'required|max:255',
                'discount_rate' => 'required',
                'point_percentage' => 'required',
                'min_point' => 'required',
            ]);

            $card = new MembershipCard;
            $card->card_status = $request->card_status;
            $card->discount_rate = $request->discount_rate;
            $card->min_purchase = $request->min_purchase ?? null;
            $card->point_percentage = $request->point_percentage;
            $card->min_point = $request->min_point;
            $card->save();

            Alert::toast('Membership Card Created', 'success');
            return back();
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
