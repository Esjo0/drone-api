<?php

namespace App\Http\Controllers;

use App\Models\Drone;
use App\Models\Medication;
use Illuminate\Http\Request;

class DroneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {
        return Drone::all();
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
            'serial_number' => 'required|string|unique:drones,serial_number',
            'model' => 'required',
            'weight_limit' => 'required',
            'battery_capacity' => 'required',
            'state' => 'required'
        ]);

        if($request->weight_limit <= 500){
            return Drone::create($request->all());
        }else{
            $response = [
                'message' => 'Drone Weight limit exceeds allowed weight - 500grams'
            ];

            return response($response);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Drone::find($id);
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
        $drone = Drone::find($id);

        if(isset($request->weight_limit) && $request->weight_limit > 500){
            $response = [
                'message' => 'Drone Weight limit exceeds allowed weight - 500grams'
            ];

            return response($response);
        }else{
            $drone->update($request->all());

            return $drone;
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return 
     */
    public function destroy($id)
    {
        return Drone::destroy($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $serial_number
     * @return \Illuminate\Http\Response
     */
    public function search($serial_number)
    {
        return Drone::where('serial_number', 'like', '%'.$serial_number.'%')->get();
    }

    /**
     * Get drone status
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        return Drone::select('state')->where('id', $id)->whereOr('state', $id)->first()->state;
    }

    /**
     * Get drone status as LOADED
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function loaded()
    {
        return Drone::where('state', 'LOADED')->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $med_id
     * @param  string  $drone_id
     * @return \Illuminate\Http\Response
     */
    public function load(Request $request)
    {
        $data = $request->validate([
            'drone_id' => 'required',
            'med_id' => 'required'
        ]);

        $drone = Drone::find($data['drone_id']);

        $medication = Medication::find($data['med_id']);

        $current_weight = Medication::select('weight')->where('drone_id', $data['drone_id'])->sum('weight');
        $available_weight = $drone->weight_limit - $current_weight;

        if(($available_weight >= $medication->weight) && ($drone->battery_capacity >= 25) && ($drone->state == 'LOADING')){
            $medication->update(['drone_id' => $request->drone_id]);
            $response = [
                'message' => 'Loading Successful. Medication has been added to drone.'
            ];
        }else if($available_weight < $medication->weight){
            $response = [
                'message' => 'Drone weight limit will be exceeded if you add this medication, kindly add a medication with lesser weight. Available Weight is '.$available_weight.'gram'
            ];
        }else if($drone->state != 'LOADING'){
            $response = [
                'message' => 'Drone is currently not in LOADING state. It is currently '.$drone->state
            ];
        }else if($drone->battery_capacity < 25){
            $response = [
                'message' => 'Drones Battery is Low. Its currently at '.$drone->battery_capacity.'%'
            ];
        }

        return response($response);
    }
    /**
     * Get all medications loaded on a drone
     *
     * @param [type] $id enter drone id || drone serial number
     * @return void
     */
    public function meds($id){
        return Medication::where('drone_id', $id)->whereOr('serial_number', $id)->get();
    }
}
