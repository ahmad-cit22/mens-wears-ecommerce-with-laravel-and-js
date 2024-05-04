<?php

namespace App\Http\Controllers;

use App\Models\VatEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class VatEntryController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if (!Auth::user()->vendor) {
            $vat_entries = VatEntry::where('vendor_id', null)->with('order')->latest()->get();
        } else {
            $vat_entries = VatEntry::where('vendor_id', Auth::user()->vendor->id)->with('order')->latest()->get();
        }
        $total_outstanding = $vat_entries->where('is_paid', 0)->sum('vat_amount');
        $total_paid = $vat_entries->where('is_paid', 1)->sum('vat_amount');
        $status = '';
        $date_from = '';
        $date_to = '';

        if (auth()->user()->can('vat.calculate')) {

            return view('admin.vat-entry.index', compact('vat_entries', 'total_outstanding', 'total_paid', 'status', 'date_from', 'date_to'));
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function search(Request $request) {
        if (!Auth::user()->vendor) {
            $vat_entries = VatEntry::where('vendor_id', null)->with('order')->latest()->get();
        } else {
            $vat_entries = VatEntry::where('vendor_id', Auth::user()->vendor->id)->with('order')->latest()->get();
        }
        $total_outstanding = 0;
        $total_paid = 0;
        $status = '';
        $date_from = '';
        $date_to = '';

        if (auth()->user()->can('vat.calculate')) {
            if ($request->status != '' && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = $request->date_from;
                $end_date = $request->date_to;
                $status = $request->status;

                $vat_entries = VatEntry::where('is_paid', $status)
                    ->whereBetween('date_of_sell', [$start_date, $end_date])->with('order')->latest()->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }
            if (($request->status != '' && empty($request->date_from) && empty($request->date_to)) || ($request->status != '' && !empty($request->date_from) && empty($request->date_to)) || ($request->status != '' && empty($request->date_from) && !empty($request->date_to))) {

                $status = $request->status;

                $vat_entries = VatEntry::where('is_paid', $status)->with('order')->latest()->get();
            }
            if ($request->status == '' && !empty($request->date_from) && !empty($request->date_to)) {
                $start_date = $request->date_from;
                $end_date = $request->date_to;
                $vat_entries = VatEntry::whereBetween('date_of_sell', [$start_date, $end_date])->with('order')->latest()->get();

                $date_from = $request->date_from;
                $date_to = $request->date_to;
            }

            if (Auth::user()->vendor) {
                $vat_entries = $vat_entries->where('vendor_id', Auth::user()->vendor->id);
            } else {
                $vat_entries = $vat_entries->where('vendor_id', null);
            }

            $total_outstanding = $vat_entries->where('is_paid', 0)->sum('vat_amount');
            $total_paid = $vat_entries->where('is_paid', 1)->sum('vat_amount');

            return view('admin.vat-entry.index', compact('vat_entries', 'total_outstanding', 'total_paid', 'status', 'date_from', 'date_to'));
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
    public function paid($id) {
        $this->authorize('vat.calculate');

        $vat_entry = VatEntry::find($id);

        if (!is_null($vat_entry)) {
            $vat_entry->is_paid = 1;
            $vat_entry->save();

            Alert::toast('Vat Entry Paid!', 'success');

            return back();
        }
        Alert::toast('Vat Entry Not Found!', 'error');

        return back();
    }

    // destroy function for vat entry
    public function destroy($id) {
        $this->authorize('vat-entry.delete');
        $vat_entry = VatEntry::find($id);

        if ($vat_entry) {
            $vat_entry->delete();

            Alert::toast('Vat Entry Deleted!', 'success');
            return back();
        }

        Alert::toast('Vat Entry Not Found!', 'error');
        return back();
    }
}
