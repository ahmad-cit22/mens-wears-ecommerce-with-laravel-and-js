<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Auth;
use Alert;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('district.index')) {
            $districts = District::orderBy('id', 'ASC')->get();
            return view('admin.district.index', compact('districts'));
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
        if (auth()->user()->can('district.create')) {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
            ]);
            $district = new District;
            $district->name = $request->name;
            $district->save();
            Alert::toast('One District Added !', 'success');
            return redirect()->route('district.index');
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    public function upload()
    {
        $districts = [
            'Bagerhat',
            'Bandarban',
            'Barguna',
            'Barishal',
            'Bhola',
            'Bogura',
            'Brahmanbaria',
            'Chandpur',
            'Chapai Nawabganj',
            'Chattogram',
            'Chuadanga',
            'Cox\'s Bazar',
            'Cumilla',
            'Dhaka',
            'Dinajpur',
            'Faridpur',
            'Feni',
            'Gaibandha',
            'Gazipur',
            'Gopalganj',
            'Habiganj',
            'Jaipurhat',
            'Jamalpur',
            'Jashore',
            'Jhalokati',
            'Jhenaidah',
            'Khagrachhari',
            'Khulna',
            'Kishoreganj',
            'Kurigram',
            'Kushtia',
            'Lalmonirhat',
            'Lakshmipur',
            'Madaripur',
            'Magura',
            'Manikganj',
            'Meherpur',
            'Mauluvibazar',
            'Munshiganj',
            'Mymensingh',
            'Naogaon',
            'Narail',
            'Narayanganj',
            'Narsingdi',
            'Natore',
            'Netrokona',
            'Nilphamari',
            'Noakhali',
            'Pabna',
            'Panchagarh',
            'Patuakhali',
            'Pirojpur',
            'Rajbari',
            'Rajshahi',
            'Rangamati',
            'Rangpur',
            'Satkhira',
            'Shariatpur',
            'Sherpur',
            'Sirajganj',
            'Sunamganj',
            'Sylhet',
            'Tangail',
            'Thakurgaon'
        ];
        foreach ($districts as $district) {
            $new_district = new District;
            $new_district->name = $district;
            $new_district->save();
        }
        Alert::toast('uploaded', 'success');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('district.edit')) {
            $district = District::find($id);

            if (!is_null($district)) {
                $this->validate($request, [
                    'name' => 'required',
                ]);

                $district->name = $request->name;

                $district->save();
                Alert::toast('District has been updated !', 'success');
                return redirect()->route('district.index');
            }
            else{
                Alert::toast('District Not Found !', 'warning');
                return redirect()->route('district.index');
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
     * @param  \App\Models\District  $district
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->can('district.delete')) {
            $district = District::find($id);
            if (!is_null($district)) {
                
                $district->delete();
                Alert::toast('District has been deleted !', 'success');
                return back();
            }
            else {
                session()->flash('error','Something went wrong !');
                return back();
            }
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }
}
