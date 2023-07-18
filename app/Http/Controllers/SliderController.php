<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Models\Setting;
use Illuminate\Http\Request;
use Auth;
use File;
use Image;
use Alert;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('slider.index')) {
            $sliders = Slider::all();
            $video = Setting::find(1)->video;
            return view('admin.slider.index', compact('sliders', 'video'));
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
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (auth()->user()->can('slider.edit')) {
            $validatedData = $request->validate([
                'position' => 'required|integer',
                'image' => 'image',
                'link' => 'nullable',
            ]);

            $slider = Slider::find($request->position);
            if (!is_null($slider)) {
                // image save
                if ($request->image){
                    if (File::exists('images/slider/'.$slider->image)){
                        File::delete('images/slider/'.$slider->image);
                    }
                    $image = $request->file('image');
                    $img = time() . '.' . $image->getClientOriginalExtension();
                    $location = public_path('images/slider/'. $img);
                    Image::make($image)->save($location);
                    $slider->image = $img;
                }
                if ($request->title != null) {
                    $slider->title = $request->title;
                }
                if ($request->button_text != null) {
                    $slider->button_text = $request->button_text;
                }
                if ($request->link != null) {
                    $slider->link = $request->link;
                }

                $slider->save();
                Alert::toast('Slide has been changed', 'success');
                return redirect()->route('slider.index');
            }
            else {
                session()->flash('error','Something went wrong!');
                return redirect()->route('admin.slider');
            }
        }
        else {
            abort(403, 'Unauthorized action');
        }
    }

    public function update_video(Request $request)
    {
        if (auth()->user()->can('slider.edit')) {
            $validatedData = $request->validate([
                'video' => 'required',
            ]);

            $setting = Setting::find(1);
            if (!is_null($setting)) {
                // Video save
                if ($request->video){
                    if (File::exists('videos/'.$setting->video)){
                        File::delete('videos/'.$setting->video);
                    }
                    $video = $request->file('video');
                    $vid = time() . '.' . $video->getClientOriginalExtension();
                    $request->video->move(public_path('videos'), $vid);
                    $setting->video = $vid;
                }
                $setting->save();
                Alert::toast('Slider video has been changed', 'success');
                return redirect()->route('slider.index');
            }
            else {
                session()->flash('error','Something went wrong!');
                return redirect()->route('slider.index');
            }
        }
        else {
            abort(403, 'Unauthorized action');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        //
    }
}
