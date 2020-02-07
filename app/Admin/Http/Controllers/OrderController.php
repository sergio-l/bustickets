<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use AdminSection;
use DB;
use Auth;
use Carbon\Carbon;
use App\Model\Flight;
use App\Model\FlightPrice;
use App\Model\Station;
use App\Model\Order;
use App\Model\Ticket;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderController extends \SleepingOwl\Admin\Http\Controllers\AdminController
{

    use HandlesAuthorization;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request, User $user)
    {
        $data = $request->all();

        $data['date'] = !empty($data['date']) ? Carbon::parse($data['date'])->toDateString() : Carbon::now()->toDateString();
        $data['person'] = 1;

        $dayName = date('l',strtotime($data['date']));

        if(!empty($data['from']) && !empty($data['to'])) {
            $flight = Flight::with(['stations' => function ($query) use ($data) {
                $query->whereIn('station_id', array($data['from'], $data['to']));
                $query->orderByRaw(DB::raw("FIELD(station_id," . $data["from"] . "," . $data['to'] . ")"));
            }, 'price' => function ($query) use ($data) {
                $query->where('stationA_id', '=', $data['from']);
                $query->where('stationB_id', '=', $data['to']);
            }, 'allStationsFlight',
                'tickets' => function($query) use ($data){
                    $query->whereRaw("DATE(date) = '" .$data['date']. "'");
                    $query->where('status','=', 'ok');
            },'types' => function($query) use ($dayName){
                $query->where('eng_name', '=', $dayName);
            }])->where('start_date', '<=', $data['date'])
                ->where('end_date', '>=', $data['date'])
                ->get();

            return view('admin.orders.search', [
                'flights' => $flight,
                'date'    =>  $data['date'],
                'place'   => $data['place']]);
        }

    }

    public function checkout(Request $request)
    {
        $data = $request->all();
        $request->session()->put('from', $data['from']);
        $request->session()->put('to', $data['to']);
        $request->session()->put('flight_id', $data['flight_id']);
        $request->session()->put('date', $data['date']);
        $request->session()->put('place', $data['place']);

        return redirect('admin/orders/buy');
    }

    public function create(Request $request)
    {
        $data1 = $request->all();
        $from  = $request->session()->get('from');
        $to    = $request->session()->get('to');

        $data = Flight::with(['buses', 'tickets' ,'stations' => function($query) use ($request){
            $query->whereIn('station_id',
                array($request->session()->get('from'), $request->session()->get('to'))
            );
        }, 'price' => function($query) use ($request){
            $query->where('stationA_id', '=', $request->session()->get('from'));
            $query->where('stationB_id', '=', $request->session()->get('to'));
        },'tickets' => function($query) use ($request){
            $query->whereRaw("DATE(date) = '" . $request->session()->get('date') . "'");
            $query->where('status','=', 'ok');
        }])->where(['id' => $request->session()->get('flight_id')])->first();

        
        $bus_place = $data->buses->places;
        $stations_place = $this->getTicket($data->tickets, $data->id);
        $arrPlaces = $this->disabledTicket($stations_place, $from, $to, $bus_place);
        

        $request->session()->put('price', $data->price->first()->price);

        
        return view('admin.orders.checkout',[
            'flight' => $data1,
            'data' => $data,
            'date' =>  $request->session()->get('date'),
            'count' =>  $request->session()->get('place'),
            'arrPlaces' => $arrPlaces,
            'user' => auth()->user()
        ]);
    }


    protected function getTicket($tickets, $flight_id)
    {
        $station_places = [];
        $flight = Flight::find($flight_id);


        $collection = $tickets->map(function($item) {
            if($item->order->payment_method != 'not-paid') {
                return [
                    'number' => $item->number,
                    'stationA_id' => $item->stationA_id,
                    'stationB_id' => $item->stationB_id
                ];
            }
        });

        foreach ($flight->stations as $station){
            $station_places[$station->id] = [];
            for($i = 0; $i < count($collection); $i++){
                if($collection[$i]['stationA_id'] == $station->id){
                    array_push($station_places[$station->id], $collection[$i]);
                }
            }
        }

        return $station_places;

    }

    protected function disabledTicket($stations_place, $station_departure, $station_arrival,$total_place)
    {
        $free_places = []; //свободные места

        for ($i = 1; $i <= $total_place; $i++) {
            $free_places[] = 1; //заполняем единицами (пока все свободно)
        }

        if ($station_departure < $station_arrival) { //движемся в прямом направлении 1,2,3...
            foreach ($stations_place as $tickets) {
                foreach ($tickets as $ticket) { //перебираем билеты
                    $from = $ticket['stationA_id'];
                    $to = $ticket['stationB_id'];
                    if ($from < $to) { //если билет в нашу сторону
                        if ($station_arrival <= $from || $station_departure >= $to) {
                            //если нет пересечений, то нам эта поездка не мешает
                        } else {
                            //в ином случае помечаем место как занятое
                            $free_places[ $ticket['number'] -1 ] = 0;
                        }
                    }
                }
            }
        } else { //едем в обратную сторону 9,8,7,6..
            foreach ($stations_place as $tickets) {
                foreach ($tickets as $ticket) { //перебираем билеты
                    $from = $ticket['stationA_id'];
                    $to = $ticket['stationB_id'];
                    if ($from > $to) { //если билет в нашу ОБРАТНУЮ сторону
                        if ($station_arrival >= $from || $station_departure <= $to) {
                            //если нет пересечений (все знаки наоборот)
                        } else {
                            $free_places[ $ticket['number'] -1 ] = 0;
                        }
                    }
                }
            }
        }

        $arrPlaces = [];

        for($i = 0; $i < count($free_places); $i++){
            if($free_places[$i] == 0){
                $arrPlaces[] = $i+1;
            }
        }

        return $arrPlaces;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storage(Request $request)
    {
        $data = $request->all();

        $validation = Validator::make($data, $this->rules($request->session()->get('place')));

        if ($validation->fails()){
            return redirect()->back()
                ->withErrors($validation->errors())
                ->withInput();
        }

        $suma = $request->session()->get('price') *  $request->session()->get('place');
        $discount = 0;

        if(isset($data['children'])){
            $discount = $request->session()->get('price') * 0.50 * count($data['children']);
        }elseif(isset($data['cashless'])){
            $discount = $request->session()->get('price') * count($data['cashless']);
        }

        $order = new Order();
        $order->phone = $request->get('phone');
        $order->email = Auth::user()->email;
        $order->user_id = Auth::user()->id;
        $order->suma  = $suma - $discount ;
        $order->suma += $this->baggageSum($data, $request->session()->get('price'));
        $order->payment_method = 'cash';
        $order->status = 'success';
        $order->token = md5(time().rand());
        $order->sale_station_id = Auth::user()->isCashier() ? Auth::user()->station_id : null;
        $order->save();


        //isset($data['children'][$i]) ? $data['price'] * 0.50 : $data['price'];

        $date = date('Y-m-d',strtotime($request->session()->get('date')));


        for ($i = 0; $i < count($data['last_name']); $i++) {
            if(isset($data['cashless'][$i])){
                $ticket_price = 0;
            }else{
                $ticket_price = isset($data['children'][$i]) ? $data['price'] * 0.50 : $data['price'];
            }

            $ticket = new Ticket();
            $ticket->price =  $ticket_price;
            $ticket->name = $data['name'][$i];
            $ticket->last_name = $data['last_name'][$i];
            $ticket->middle_name = $data['middle_name'][$i];
            $ticket->passport = $data['passport'][$i];
            if($data['baggage'][$i]['person'] == 'yes') {
                $place = isset($data['baggage'][$i]['place']) ? $data['baggage'][$i]['place'] : 1;
                $ticket->baggage = $data['baggage'][$i]['person'];
                $ticket->baggage_place = $place;
                if($place > 1) {
                    $ticket->baggage_price = ($request->session()->get('price') * 0.20 * ($place - 1));
                }
            }
            $ticket->order_id = $order->id;
            $ticket->date =  Carbon::parse($date . $data['departure'])->format('Y-m-d H:i:s');
            $ticket->date_arrival = Carbon::parse($date . $data['arrival'])->format('Y-m-d H:i:s');
            $ticket->flight_id = $request->session()->get('flight_id');
            $ticket->stationA_id = $request->session()->get('from');
            $ticket->stationB_id = $request->session()->get('to');
            $ticket->number = $data['places'][$i];
            $ticket->children = isset($data['children'][$i]) ? 1 : null;
            $ticket->cashless = isset($data['cashless'][$i]) ? 1 : null;
            $ticket->token = md5(time());
            $ticket->save();
        }

        return redirect('/admin/orders/')->with(['success' => 'Билет успешно оформлен']);
    }


    private function rules($k)
    {
        return [
            'places' => "required|min:".$k,
            'phone' => "required",
            'last_name.*' => 'required',
            'name.*' => 'required',
            'middle_name.*' => 'required',
            'passport.*' => 'required',
            'baggage.person.*' => 'required',
        ];
    }

    /**
     * Вычисляем суму багажа и для обновления $order->suma
     * @param $array
     * @param $price
     */
    protected function baggageSum($array, $price)
    {
        $sum = 0;

        $array = isset($array['baggage']) ? $array['baggage'] : null;

        for($i = 0; $i < count($array); $i++){
            if($array[$i]['person'] == 'yes'){
                if(isset($array[$i]['place']) && $array[$i]['place'] > 1){
                    $sum += ($price * 0.20) * $array[$i]['place'];
                }
            }
        }

        return $sum;
    }


}