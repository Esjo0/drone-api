<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medication;
use App\Models\Drone;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return 
     */
    public function index()
    {
        return Medication::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'drone_id' => 'integer',
            'weight' => 'required',
            'code' => 'required|string|unique:medications,code',
            'image_path' => 'required|string'
        ]);

        //Ensure Code is in Uppercase
        strtoupper($request->code);

        return Medication::create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Medication::find($id);
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
        $meds = Medication::find($id);
        $meds->update($request->all());

        return $meds;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return 
     */
    public function destroy($id)
    {
        return Medication::destroy($id);
    }

    /**
     * Search the specified resource from storage.
     *
     * @param  string  $search
     * @return \Illuminate\Http\Response
     */
    public function search($search)
    {
        return Medication::where('name', 'like', '%'.$search.'%')
        ->whereOr('code', 'like', '%'.$search.'%')->get();
    }
}
