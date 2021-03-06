<?php

namespace App\Http\Controllers;

use App\Vehicle;

use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicles = Vehicle::all()->toArray();
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // form validation
      $vehicle = $this->validate(request(), [
        'reg_no' => 'required',
        'daily_rate' => 'required|numeric',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:500',

    ]);

    // handles image uploading
    if ($request->hasFile('image')){
      // get the file name with the get_loaded_extensions
      $fileNameWithExtension = $request->file('image')->getClientOriginalName();
      // get the file name only
      $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
      // get the extenstion
      $extension = $request->file('image')->getClientOriginalExtension();
      // get the filename to store
      $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

      // upload the image
      $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
    } else {
      $fileNameToStore = 'noImage.jpg';
    }

    // create a Vehicle object and set its values from the input
    $vehicle = new Vehicle;
    $vehicle->reg_no = $request->input('reg_no');
    $vehicle->description = $request->input('description');
    $vehicle->brand = $request->input('brand');
    $vehicle->dailyrate = $request->input('daily_rate');
    $vehicle->created_at = now();
    $vehicle -> image = $fileNameToStore;

    // save the vehicle object
    $vehicle->save();

    // generate a redirectt HTTP response with a success message
    return back()->with('success', 'Vehicle has been added');



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vehicle = Vehicle::find($id);
        return view('vehicles.show',compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $vehicle = Vehicle::find($id);
      $vehicle->delete();
      return redirect('vehicles')->with('success', 'vehicle has been deleted');
    }
}
