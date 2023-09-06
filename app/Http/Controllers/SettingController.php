<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Alert;
use Image;
use File;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('setting.business_settings')) {
            $setting = Setting::orderBy('id', 'DESC')->first();
            return view('admin.setting.index', compact('setting'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('setting.edit')) {
            $this->validate($request, [
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                'logo'=> 'nullable',
                'favicon'=> 'nullable',
                'address' => 'required',
                'slider_option' => 'required',
            ]);

            $setting = Setting::find($id);
            
            $setting->name = $request->name;
            $setting->phone = $request->phone;
            $setting->additional_phone = $request->additional_phone;
            $setting->email = $request->email;
            $setting->address = $request->address;
            $setting->slider_option = $request->slider_option;
            $setting->combine_address = $request->combine_address;
            $setting->shipping_charge = $request->shipping_charge;
            $setting->shipping_charge_dhaka_metro = $request->shipping_charge_dhaka_metro;
            $setting->shipping_charge_dhaka = $request->shipping_charge_dhaka;

            $setting->facebook = $request->facebook;
            $setting->instagram = $request->instagram;
            $setting->twitter = $request->twitter;
            $setting->youtube = $request->youtube;
            $setting->linkedin = $request->linkedin;

            // logo save
            if ($request->logo){
                if (File::exists('images/website/'.$setting->logo)){
                    File::delete('images/website/'.$setting->logo);
                }
                $image = $request->file('logo');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/website/'. $img);
                Image::make($image)->save($location);
                $setting->logo = $img;
            }

            // footer_logo save
            if ($request->footer_logo){
                if (File::exists('images/website/'.$setting->footer_logo)){
                    File::delete('images/website/'.$setting->footer_logo);
                }
                $image = $request->file('footer_logo');
                $img = 'footer_'.time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/website/'. $img);
                Image::make($image)->save($location);
                $setting->footer_logo = $img;
            }

            // favicon save
            if ($request->favicon){
                if (File::exists('images/website/'.$setting->favicon)){
                    File::delete('images/website/'.$setting->favicon);
                }
                $image = $request->file('favicon');
                $img = 'favicon_'.time() . '.' . $image->getClientOriginalExtension();
                $location = public_path('images/website/'. $img);
                Image::make($image)->save($location);
                $setting->favicon = $img;
            }

            $setting->save();
            Alert::toast('Settings has been updated !', 'success');
            return back();
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
