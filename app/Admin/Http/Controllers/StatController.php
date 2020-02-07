<?php

namespace App\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use AdminSection;
use Auth;
use Carbon\Carbon;
use App\Model\Order;
use App\Model\Ticket;
use App\Model\User;
use App\Model\Station;
use App\Model\ReturnedTicket;
use App;

class StatController extends \SleepingOwl\Admin\Http\Controllers\AdminController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Статистика - http://gp41.ru/admin/stat
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data['users']  = User::whereHas('roles', function($q){
            $q->where('name', 'cashier');
        })->get();

        $date = $request->has('date') ? $request->get('date') : date('Y-m-d');
        $date = date('Y-m-d', strtotime($date));

        $data['stations'] = $this->stationsData($date);
        $data = array_merge($data,$this->getTotalStat($date));
        $data['cashiers'] = $this->getCashiers();

        $data['returned_petropavlovsk'] = $this->returnedStation($date, 1);
        $data['returned_elizovo'] = $this->returnedStation($date, 2);
        $data['returned_milkovo'] = $this->returnedStation($date, 9);
        $data['returned_bolshiretsk'] = $this->returnedStation($date, 13);
        $data['returned_oktybrskiy'] = $this->returnedStation($date, 14);

        $data['returned_ticket'] = $this->getReturned($date);

        $view = view("admin.stat", $data);
        return AdminSection::view($view, 'Статистика');
    }

    protected function getCashiers()
    {
        $users = User::with(['roles' => function($q){
            $q->where('name', 'cashier');
        }])->get();

        return $users;
    }

    /**
     * Данные проданых билетов на выбранную дату по 6-х станциях
     * @param $date
     * @return mixed
     */
    protected function stationsData($date)
    {
        $stations = Ticket::date($date)->get();

        $petropavlovsk_offline = $stations->where('order.sale_station_id', 1)
            ->where('order.payment_method', 'cash');
        $elizovo_offline = $stations->where('order.sale_station_id', 2)
            ->where('order.payment_method', 'cash');
        $milkovo_offline = $stations->where('order.sale_station_id', 9)
            ->where('order.payment_method', 'cash');
        $ust_bolshiretsk_offline = $stations->where('order.sale_station_id', 13)
            ->where('order.payment_method', 'cash');
        $oktybrskiy_offline = $stations->where('order.sale_station_id', 14)
            ->where('order.payment_method', 'cash');

        $petropavlovsk_online = $stations->where('stationA_id', 1)
            ->where('order.sale_station_id', null)
            ->where('order.status', 'success');
        $elizovo_online = $stations->where('stationA_id', 2)
            ->where('order.sale_station_id', null)
            ->where('order.status', 'success');
        $milkovo_online = $stations->where('stationA_id', 9)
            ->where('order.sale_station_id', null)
            ->where('order.status', 'success');
        $ust_bolshiretsk_online = $stations->where('stationA_id', 13)
            ->where('order.sale_station_id', null)
            ->where('order.status', 'success');
        $oktybrskiy_online = $stations->where('stationA_id', 14)
            ->where('order.sale_station_id', null)
            ->where('order.status', 'success');

        $data['petropavlovsk'] = $petropavlovsk_online->merge($petropavlovsk_offline);
        $data['elizovo'] = $elizovo_online->merge($elizovo_offline);
        $data['milkovo'] = $milkovo_online->merge($milkovo_offline);
        $data['ust-bolshiretsk'] = $ust_bolshiretsk_online->merge($ust_bolshiretsk_offline);
        $data['oktybrskiy'] = $oktybrskiy_online->merge($oktybrskiy_offline);

        return $data;
    }


    public function getTotalStat($date)
    {

        $cash_paid = 0; $online_paid = 0; $k1 = 0; $k2 = 0;
        $tickets = Ticket::date($date)->get();

        foreach($tickets as $ticket){
            if($ticket->order->payment_method == 'cash'){
                $cash_paid += $ticket->price;
                $k1++;
            }elseif($ticket->order->payment_method == 'paid'){
                $online_paid += $ticket->price;
                $k2++;
            }
        }

        return ['online_paid' => $online_paid, 'cash_paid' => $cash_paid,
            'online_count' => $k2, 'cash_count' => $k1];
    }

    /**
     * Статистка - Ajax таблица Продано за период
     * @param Request $request
     * @return mixed
     */
    public function getStat(Request $request)
    {
        $data = $request->all();
        $stations = Ticket::dateDuration($data['date_start'], $data['date_end'])->get();

        $orders = $stations->where('order.payment_method', 'cash')
            ->where('order.status', 'success')
            ->when(request('cashier') != 'all', function ($q) {
                return $q->where('order.user_id', request('cashier'));
            });

        $suma = $orders->sum('suma');
        $count  = $orders->count();

        return Response(['suma' => $suma, 'count' => $count]);
    }

    /**
     * Данные в pdf по станции
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function pdfStationsTable($id, Request $request)
    {

        $date = date('Y-m-d', strtotime($request->get('date')));

        $stationName = Station::find($id);
        $stations = Ticket::date($date)->get();
        $cashiers = $this->getCashiers();

        $offline = $stations->where('order.sale_station_id', $id)
            ->where('order.payment_method', 'cash');
        $online = $stations->where('stationA_id', $id)
            ->where('order.payment_method', 'paid');
        $station = $online->merge($offline);
        $returned = $this->returnedStation($date, $id);
        

        view()->share(['station' => $station,
            'date' => $request->get('date'),
            'stationName'=> $stationName,
            'cashiers' => $cashiers,
            'returned' =>  $returned
        ]);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.stationPDF');
        return $pdf->stream();
    }

    /**
     * Данные в pdf по 6-х станциях
     * @param Request $request
     * @return mixed
     */
    public function pdfAllStations(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->get('date')));

        $data['stations'] = $this->stationsData($date);
        $data['date'] = $request->get('date');
        $data = array_merge($data,$this->getTotalStat($date));
        $data['cashiers'] = $this->getCashiers();

        $data['returned_petropavlovsk'] = $this->returnedStation($date, 1);
        $data['returned_elizovo'] = $this->returnedStation($date, 2);
        $data['returned_milkovo'] = $this->returnedStation($date, 9);
        $data['returned_bolshiretsk'] = $this->returnedStation($date, 13);
        $data['returned_oktybrskiy'] = $this->returnedStation($date, 14);

        $data['returned_ticket'] = $this->getReturned($date);

        view()->share($data);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.stationAllPDF');
        return $pdf->stream();
    }

    protected function returnedStation($date, $station_id)
    {
        return ReturnedTicket::with('ticket')->whereDate('created_at', $date)
            ->where('station_id', $station_id)
            ->get();
    }

    public function getReturned($date)
    {
        return ReturnedTicket::with('ticket')->whereDate('created_at', $date)->get();
    }
}