<?php

namespace App\Admin\Http\Controllers;

use App\Model\Flight;
use App\Model\FlightPrice;
use App\Model\Station;
use App\Model\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use AdminSection;

class FlightController extends \SleepingOwl\Admin\Http\Controllers\AdminController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();

        $flight = new Flight();
        $flight->number = $data['number'];
        $flight->start_date = $data['start_date'];
        $flight->end_date = $data['end_date'];
        $flight->buses_id = $data['bus_id'];
        $flight->driver_id = $data['driver_id'];
        $flight->status = $data['status'];
        $flight->save();

        $flight->types()->attach($data['types']);

        foreach ($data['station'] as $station){
            $flight->stations()->attach(1,
                [
                    'station_id' => $station['id'],
                    'arrival'    => isset($station['arrival']) ? $station['arrival'] : null,
                    'departure'  => isset($station['departure']) ? $station['departure'] : null
                ]
            );
        }

        return redirect($request->get('_redirectBack'));
    }

    public function update($id, Request $request)
    {
        $data = $request->all();

        $flight = Flight::findOrFail($id);
        $flight->number = $data['number'];
        $flight->buses_id = $data['bus_id'];
        $flight->driver_id = $data['driver_id'];
        $flight->start_date = date('Y-m-d', strtotime($data['start_date']));
        $flight->end_date = date('Y-m-d', strtotime($data['end_date']));
        $flight->status = $data['status'];
        $flight->save();

        $flight->stations()->detach();
        $flight->types()->sync($data['types']);
        foreach ($data['station'] as $station){

            $flight->stations()->attach($station['id'],
                [
                    'station_id' => $station['id'],
                    'arrival'    => isset($station['arrival']) ? $station['arrival'] : null,
                    'departure'  => isset($station['departure']) ? $station['departure'] : null
                ]
            );
        }
        return redirect($request->get('_redirectBack'));
    }


    /**
     * Вывод всех возможных станций
     * @param $id
     * @return mixed
     */
    public function price($id)
    {
        $flight = Flight::with('price', 'stations')->find($id);

        $stations = $this->changeArrayPivot($flight->stations->toArray());
        $combArray = $this->get_combinations(2, $stations);

        //dd($flight->price->toArray());
        return AdminSection::view(view('admin.flight.price',
            ['flight' => $flight, 'combArray' => $combArray, 'stations' => $stations]),
            'Стоимость маршрута № ' . $flight->number . ':' . $flight->stations->first()->title .' - '. $flight->stations->last()->title );
    }

    /**
     * Установка цены для рейса
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function savePrice($id, Request $request)
    {
        $flight = Flight::find($id);
        $data = $request->all();
        $validation = Validator::make($data, $this->price_rules());

        if ($validation->fails()){
            return redirect('/admin/flights/'.$flight->id.'/price')
                ->withErrors($validation->errors())
                ->withInput();
        }

        $flight->prices()->detach();
        if(isset($data['st'])&& count($data['st']) > 0) {
            foreach ($data['st'] as $value) {
                $flight->prices()->attach($id, [
                    'stationA_id' => $value['id_first'],
                    'stationB_id' => $value['id_second'],
                    'price' => $value['price']
                ]);
            }
        }

        if($request->get('next_action') == 'save_and_continue') {
            return redirect('/admin/flights/'.$id.'/price')
                ->with('success', 'Запись успешно обновлена');
        }else{
            return redirect($request->get('_redirectBack'));
        }
    }

    /**
     * @param $k - количество соединяющих станций
     * @param $xs - массив станций
     * @return array
     */
    private function get_combinations($k, $xs)
    {
        if ($k === 0)
            return array(array());
        if (count($xs) === 0)
            return array();
        $x = $xs[0];
        $xs1 = array_slice($xs,1,count($xs)-1);
        $res1 = $this->get_combinations($k-1,$xs1);
        for ($i = 0; $i < count($res1); $i++) {
            array_splice($res1[$i], 0, 0, $x);
        }
        $res2 = $this->get_combinations($k,$xs1);

        return array_merge($res1, $res2);
    }

    /**
     * Очистка данных pivot таблицы
     * @param $array
     * @return array
     */
    private function changeArrayPivot($array)
    {
        $result = [];
        foreach ($array as $key => $value)
        {
            array_push($result, array('id' => $value['id'], 'title' => $value['title'] ));
        }

        return $result;
    }

    private function price_rules()
    {
        return [
            'st.*.price' => 'required|numeric'
        ];
    }

    public function searchStation($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->searchStation($subarray, $key, $value));
            }
        }

        return $results;
    }

    /**
     * Ajax search station
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $stations = Station::where('title', 'LIKE', '%'.$request->get('search').'%')->limit(5)->get();

        $array = [];

        foreach ($stations as $st) {
            $array[] = ['id' => $st->id, 'text' => $st->title];
        }

        return \Response::json($array);
    }


    /**
     * Добавления дополнительного рейса в Admin Dashboard
     * @param Request $request
     * @return mixed
     */
    public function addFlight(Request $request)
    {
        $flight = Flight::find($request->get('flight_id'));

        $new_flight = new Flight();
        $new_flight->buses_id = $request->get('bus_id');
        $new_flight->driver_id = $request->get('driver_id');
        //$new_flight->flight_type_id = 9;
        $new_flight->status = "В рейсе";
        $new_flight->number = $flight->number .'/1';
        $new_flight->parent_id = $request->get('flight_id');
        $new_flight->start_date = date('Y-m-d', strtotime($request->get('start_date')));
        $new_flight->end_date = date('Y-m-d', strtotime($request->get('start_date')));
        $new_flight->time_dop = $request->get('time');
        $new_flight->save();

        $departure = $flight->stations->first()->pivot->departure;
        $prev = \Carbon\Carbon::createFromFormat('H:s:i', $departure);
        $new = \Carbon\Carbon::createFromFormat('H:i', $request->get('time'));
        $diff = $new->diffInMinutes($prev);

        foreach ($flight->stations as $station){
            $new_flight->stations()->attach(1,
                [
                    'station_id' => $station->pivot->station_id,
                    'arrival'    => isset($station->pivot->arrival) ?  \Carbon\Carbon::createFromFormat('H:s:i', $station->pivot->arrival)->addMinutes($diff) : null,
                    'departure'  => isset($station->pivot->departure) ? \Carbon\Carbon::createFromFormat('H:s:i', $station->pivot->departure)->addMinutes($diff) : null

                ]
            );
        }

        foreach ($flight->price as $value) {
            $new_flight->prices()->attach($flight->id, [
                'stationA_id' => $value->stationA_id,
                'stationB_id' => $value->stationB_id,
                'price' => $value->price
            ]);
        }

        if($new_flight->id && $new_flight->stations->count() > 1){
            return \Response::json(['status' => 'success']);
        }else{
            return \Response::json(['status' => 'error']);
        }

    }

    public function editDopFlight(Request $request)
    {
        $flight = Flight::find($request->get('flight_id'));
        $flight->buses_id   = $request->get('bus_id');
        $flight->driver_id  = $request->get('driver_id');
        $flight->start_date = date('Y-m-d', strtotime($request->get('start_date')));
        $flight->end_date   = date('Y-m-d', strtotime($request->get('start_date')));
        $flight->time_dop   = $request->get('time');
        $flight->save();

        $departure = $flight->stations->first()->pivot->departure;
        $prev = \Carbon\Carbon::createFromFormat('H:s:i', $departure);
        $new = \Carbon\Carbon::createFromFormat('H:i', $request->get('time'));
        $diff = $new->diffInMinutes($prev);

        $parent_flight = Flight::find($flight->parent_id);
        $flight->stations()->detach();

        foreach ($parent_flight->stations as $station){
            $flight->stations()->attach(1,
                [
                    'station_id' => $station->pivot->station_id,
                    'arrival'    => isset($station->pivot->arrival) ?  \Carbon\Carbon::createFromFormat('H:s:i', $station->pivot->arrival)->addMinutes($diff) : null,
                    'departure'  => isset($station->pivot->departure) ? \Carbon\Carbon::createFromFormat('H:s:i', $station->pivot->departure)->addMinutes($diff) : null
                ]
            );
        }
    }


}
