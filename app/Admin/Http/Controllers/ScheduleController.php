<?php

namespace App\Admin\Http\Controllers;

use App\Model\Flight;
use App\Model\FlightPrice;
use App\Model\Station;
use App\Model\Driver;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use AdminSection;

class ScheduleController extends \SleepingOwl\Admin\Http\Controllers\AdminController
{

    public function sсhedule($id)
    {
        $flight = Flight::with('drivers')->find($id);
        $drivers = Driver::all();

        return AdminSection::view(view('admin.flight.schedule',[
            'flight' => $flight,
            'drivers' => $drivers
        ]),'Расписание водителей на маршруте: ' . $flight->number);
    }

    public function save(Request $request)
    {
       $data = $request->all();
       $flight = Flight::find($data['flight_id']);
       $start_date = date('Y-m-d', strtotime($data['date_start']));
       //$end_date = date('Y-m-d', strtotime($data['date_end']));

       $flight->drivers()->attach($data['driver_id'], [
           'date' => $start_date,
           //'date_end' => $end_date
       ]);

       return Response(['status'=> 'ok']);
    }

    public function detachDriver(Request $request)
    {
        $data = $request->all();

        $flight = Flight::find($data['flight_id']);
        $flight->drivers()->wherePivot('id', $data['id'])->detach();

        return Response(['status'=> 'ok']);
    }

    public function getJson(Request $request)
    {
        if($request->has('id')){
            $flight = Flight::with('drivers')->find($request->get('id'));
            $data= [];


            foreach($flight->drivers as $drivers){
                $data [] = array('title' => $drivers->full_name,
                    'id' => $drivers->pivot->id,
                    'start' => $drivers->pivot->date);
            }

            return Response($data);
        }


    }

}