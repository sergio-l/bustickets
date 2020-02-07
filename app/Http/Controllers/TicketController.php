<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Model\Flight;
use App\Model\Order;
use App\Model\Ticket;
use App\Model\Payment;
use App\Model\Setting;
use Illuminate\Http\Request;
use Psy\Exception\ErrorException;
use Validator;
use Carbon\Carbon;
use App;
use Barryvdh\DomPDF\PDF;


class TicketController extends Controller
{
    protected $settings;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->settings = Setting::first();
    }

    /**
     * При нажатии кнопки "Выбрать" на странице поиска, записываем поля input hidden в сессию.
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $data = $request->all();
        $request->session()->put('from', $data['from']);
        $request->session()->put('to', $data['to']);
        $request->session()->put('flight', $data['flight']);
        $request->session()->put('date', $data['date']);
        $request->session()->put('person', $data['person']);

        return redirect('tickets/buy');
    }

    /**
     * Страница сформой заполнения данных о заказе. А также сам заказ.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function create(Request $request)
    {

        if(!$request->session()->has('from') || !$request->session()->has('to')
            || !$request->session()->has('flight')){
            return redirect('/');
        }

        $from  = $request->session()->get('from');
        $to    = $request->session()->get('to');
        $person = $request->session()->get('person');

        $data = Flight::with(['buses', 'tickets' ,'stations' => function($query) use ($request){
            $query->whereIn('station_id',
                array($request->session()->get('from'),$request->session()->get('to'))
            );
        }, 'price' => function($query) use ($request){
            $query->where('stationA_id', '=', $request->session()->get('from'));
            $query->where('stationB_id', '=', $request->session()->get('to'));
        },'tickets' => function($query) use ($request){
            $query->whereRaw("DATE(date) = '" . $request->session()->get('date') . "'");
            $query->where('status','=', 'ok');
        }])
            ->where(['id' => $request->session()->get('flight')])
            ->first();

        $bus_place = $data->buses->places;
        $stations_place = $this->getTicket($data->tickets, $data->id);
        $arrPlaces = $this->disabledTicket($stations_place, $from, $to, $bus_place);

        if(($data->buses->places - count($arrPlaces)) <= $person ){
            $person = $data->buses->places - count($arrPlaces);
        }

        //Если к-во мест автобуса больше занятых мест то блокируем первые4 места
        
        if(($data->buses->places - 4) >= count($arrPlaces) + $person  ){
            array_push($arrPlaces, $data->buses->places );
            array_push($arrPlaces, $data->buses->places - 1);
            array_push($arrPlaces, $data->buses->places - 2);
            array_push($arrPlaces, $data->buses->places - 3);
        }

        //dd($arrPlaces);

        $request->session()->put('price', $data->price->first()->price);

        return view('tickets.buy',
            [
                'data' => $data,
                'date' =>  $request->session()->get('date', $data['date']),
                'count' =>  $person,
                'arrPlaces' => $arrPlaces,
                'settings' => $this->settings
            ]
        );
    }

    protected function getTicket($tickets, $flight_id)
    {
        $station_places = [];
        $flight = Flight::find($flight_id);


        $collection = $tickets->map(function($item){
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
     * Метод оформления заказа
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function buy(Request $request)
    {
        $data = $request->all();
        $price = $request->session()->get('price');
        $places = $request->session()->get('person') ? $request->session()->get('person') : 1;
        $discount = 0;

        $validation = Validator::make($data, $this->rules());

        if ($validation->fails()){
            return redirect('/tickets/buy')
                ->withErrors($validation->errors())
                ->withInput();
        }

        if(isset($data['children'])){
            $discount = $price * 0.50 * count($data['children']);
        }
        
        $order = new Order();
        $order->phone = $data['phone'];
        $order->email = $data['email'];
        $order->suma  = ($price *  $places) - $discount ;
        $order->suma += $this->baggageSum($data['baggage'], $price);
        $order->payment_method = $data['payment_type'] == 'cash' ? 'cash' : 'not-paid';
        $order->status = 'new'; //по умолчанию
        $order->token = md5(time().rand());
        $order->save();

        $date = date('Y-m-d',strtotime($request->session()->get('date')));

        for ($i = 0; $i < count($data['last_name']); $i++) {
            $ticket = new Ticket();
            $ticket->price = isset($data['children'][$i]) ? $price * 0.50 : $price;
            $ticket->name = $data['name'][$i];
            $ticket->last_name = $data['last_name'][$i];
            $ticket->middle_name = $data['middle_name'][$i];
            $ticket->passport = $data['passport'][$i];
            if($data['baggage'][$i]['person'] == 'yes') {
                $place = isset($data['baggage'][$i]['place']) ? $data['baggage'][$i]['place'] : 1;
                $ticket->baggage = $data['baggage'][$i]['person'];
                $ticket->baggage_place = $place;
                if($place > 1) {
                    $ticket->baggage_price = ($price * 0.20 * ($place - 1));
                }
            }
            $ticket->order_id = $order->id;
            $ticket->date =  Carbon::parse($date . $data['departure'])->format('Y-m-d H:i:s');
            $ticket->date_arrival =  Carbon::parse($date . $data['arrival'])->format('Y-m-d H:i:s');
            $ticket->flight_id = $request->session()->get('flight');
            $ticket->stationA_id = $request->session()->get('from');
            $ticket->stationB_id = $request->session()->get('to');
            $ticket->children = isset($data['children'][$i]) ? 1 : null;
            $ticket->token = md5(time());
            $ticket->number = $data['places'][$i];
            $ticket->save();
        }

        if($order->payment_method == 'cash') {
            $view = view('email.ticket', array('order' => $order));
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: Gp41 <support@gp41.ru>';
            $headers .= "X-Sender: gp41 < support@gp41.ru >\n";
            mail($order->email, 'Заказ автобусных билетов', $view, $headers);

        }

        $request->session()->flush();

        return redirect('tickets/success')->with('order', $order);
    }

    /**
     * Вычисляем суму багажа и для обновления $order->suma
     * @param $array
     * @param $price
     */
    protected function baggageSum($array, $price)
    {
        $sum = 0;

        for($i = 0; $i < count($array); $i++){
            if($array[$i]['person'] == 'yes'){
                if(isset($array[$i]['place']) && $array[$i]['place'] > 1){
                    $sum += ($price * 0.20) * $array[$i]['place'];
                }
            }
        }

        return $sum;
    }

	/** Билет в формате PDF **/
    public function generate($token)
    {
        $order = Order::where(['token' => $token])->first();
        
        if(!$order) {
            return redirect('/');
        }


        $tickets = Ticket::where(['order_id' => $order->id])->get();
        view()->share(['order' => $order,'tickets' => $tickets]);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('email.showTicket');

        $order->token = '';
        $order->save();
        return $pdf->stream();
    }

    public function successPay(Request $request)
    {
        $data = $request->all();

        $payment = new Payment();
        $payment->order_id = $data['orderid'];
        $payment->price = $data['sum'];
        $payment->transaction = $data['id'];
        $payment->save();
        
        $order = Order::find($payment->order_id);
        if($data['sum'] >= $order->suma){
            $order->payment_method = 'paid';
            $order->status = 'success';
            $order->save();

            $view = view('email.ticket', array('order' => $order));
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: Gp41 <support@gp41.ru>';

            mail($order->email, 'Заказ автобусных билетов' , $view, $headers);
            
        }

    }

    private function rules()
    {
        return [
            'last_name.*' => 'required|',
            'name.*' => 'required|',
            'middle_name.*' => 'required|',
            'passport.*' => 'required|',
            'baggage.person.*' => 'required|',
            'email' => 'required|email',
            'phone' => 'required',
            'payment_type' => 'required|in:prepay,cash'
        ];
    }

    /**
     * После оформления заказа,генерация формы для оплаты
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function formPay()
    {
        $order = session()->get('order');

        if( $order->payment_method == 'cash') {
            /*
            Mail::send('email.ticket', array('order' => $order), function ($message) use ($order) {
                $message->from('support@gp41.ru', 'gp41.ru');
                $message->to($order->email)->cc($order->email);
                $message->subject("Заказ автобусных билетов");
            });
            */
        }
        
        return view('tickets.success',['order' => $order]);
    }

}
