<?php

namespace App\Admin\Http\Controllers;

use App\Model\Flight;
use App\Model\Orders;
use App\Model\FlightPrice;
use App\Model\Station;
use App\Model\Bus;
use App\Model\Ticket;
use App\Model\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use AdminSection;
use Carbon\Carbon;
use App;

class DashboardController extends \SleepingOwl\Admin\Http\Controllers\AdminController
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
     * @return mixed
     */
    public function index()
    {
        $nowDate =  Carbon::now()->toDateString();
        $dayName = date('l',strtotime($nowDate));

        $drivers = Driver::all();
        $buses   = Bus::where(['published' => 1])->get();

        $flights = Flight::with(['children' => function ($q) use ($nowDate){
            $q->where('start_date', '=', $nowDate);
        },'types' => function($query) use ($dayName){
            $query->where('eng_name', '=', $dayName);
        }])->where('start_date', '=', $nowDate)
            ->where('end_date', '>=', $nowDate)
            ->get();

        $online_paid = \App\Model\Order::where(['payment_method' => 'paid'])->sum('suma');
        $cash_paid = \App\Model\Order::where(['payment_method' => 'cash'])->where(['status' => 'success'])->sum('suma');

        $view = view('admin.dashboard', [
            'flights' => $flights,
            'buses'   => $buses,
            'drivers' => $drivers,
            'online_paid' => $online_paid,
            'cash_paid' => $cash_paid
        ]);

        return AdminSection::view($view, 'Dashboard');
    }

    /**
     * Информация о пассажирах выбраного рейса
     * @param $id
     * @return mixed
     */
    public function showTodayFlight($id, $date)
    {

        if(empty($date)){
            $date = Carbon::now()->toDateString();
        }

        $flight = Flight::with(['stations', 'drivers', 'tickets' => function($query) use ($date){
            $query->whereRaw("DATE(date) = '" .$date. "'");
            $query->where('status','=', 'ok');
        }, 'drivers' => function($q) use($date){
            $q->wherePivot('date', '=', $date);
            //$q->orWherePivot('date_end', '>=', $date);
        } ])->find($id);
        

        $view = view('admin.passengers', ['flight' => $flight, 'date' => $date]);
        $title = 'Рейс:№'.$flight->number . $flight->stations->first()->title .' '.
            $flight->stations->last()->title . ' - '. date('d.m.y', strtotime($date)) .'  '. $flight->stations->first()->pivot->departure;

        return AdminSection::view($view, $title);
    }
    /**
     * Информация о пассажирах выбраного рейса в PDF
     * @param $id
     * @return mixed
     */
    public function toPdfPassengers($id, $date)
    {
        if(empty($date)){
            $date = Carbon::now()->toDateString();
        }

        $flight = Flight::with(['stations', 'drivers', 'buses' , 'tickets' => function($query) use ($date){
            $query->whereRaw("DATE(date) = '" .$date. "'");
            $query->where('status','=', 'ok');
        },'drivers' => function($q) use($date){
            $q->wherePivot('date', '=', $date);
            //$q->orWherePivot('date_end', '>=', $date);
        }])->find($id);


        view()->share(['flight' => $flight, 'date' => date('d.m.y', strtotime($date))]);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.passengersPDF');
        return $pdf->stream();
    }

    /**
     * Учёт проданных билетов
     * @param Request $request
     */
    public function stat(Request $request)
    {
        $data = $request->all();

        $stations = Ticket::dateDuration($data['from'], $data['to'])->get();

        $orders_cash = $stations->where('order.payment_method', 'cash')
            ->where('order.status', 'success');
        $orders_online = $stations->where('order.payment_method', 'paid')
            ->where('order.status', 'success');

        $cash_paid = $orders_cash->sum('suma');
        $online_paid = $orders_online->sum('suma');

        $result = "<p><strong>Онлайн оплачено: $online_paid руб.</strong></p>";
        $result .= "<p><strong>Оплачено в кассу: $cash_paid руб.</strong></p>";
        $result .= "<p><strong>Всего оплачено: " . ($online_paid + $cash_paid) . " руб.</strong></p>";

        echo $result;
    }

    public function history(Request $request)
    {
        $dt = $request->get('date');
        $flight = Flight::with(['tickets' => function($query) use ($dt){
            $query->whereRaw("DATE(date) = '" . date('Y-m-d', strtotime($dt)). "'");
        }])->find($request->get('flight'));


        return Response(['flight' => $flight,
            'count' => $flight->tickets->count(),
            'places' => $flight->buses->places,
            'date'  => date('Y-m-d', strtotime($dt))
        ]);
    }

    /**
     *
     * @param Request $request
     */
    public function getFlightDate(Request $request)
    {
        $date = Carbon::createFromFormat('d.m.Y', $request->get('date'))->toDateString();
        $dayName = date('l',strtotime($date));

        $flights = Flight::with(['children' => function ($q) use ($date){
            $q->where('start_date', '=', $date);
        },'types' => function($query) use ($dayName){
            $query->where('eng_name', '=', $dayName);
        }])->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->get();

        return $this->createTable($flights, $date);
    }

    protected function createTable($flights, $date)
    {
        $html = '';
        foreach ($flights as $flight) {
            if ($flight->types->count() > 0 && is_null($flight->parent_id)) {
                $html .= '<tr>';
                $html .= '<td>' . date('d.m.Y', strtotime($date)) . '</td>';
                $html .= '<td>' . $flight->number . '</td>';
                $html .= '<td>' . $flight->stations->first()->pivot->departure . '</td>';
                $html .= '<td>' . '<a href="/admin/flight/' . $flight->id . '/' . $date . '">' . $flight->stations->first()->title . ' - ' . $flight->stations->last()->title . '</a></td>';
                $html .= '<td>' . $flight->stations->last()->pivot->arrival . '</td>';
                $html .= '<td>' . $flight->buses->number . '</td>';
                $html .= '<td>' . $flight->countTicketDay($date) . ' из ' . $flight->buses->places . '</td>';
                $html .= '<td> <button class="btn btn-sm btn-warning add_button" title="Добавить дополнительный автобус"';
                $html .= 'data-toggle="modal" data-target="#myModal" data-id="' . $flight->id . '"';
                $html .= "data-title='№:" . $flight->number . $flight->stations->first()->title . ' - ' . $flight->stations->last()->title . "'";
                $html .= "data-departure='" . $flight->stations->first()->pivot->departure . "'";
                $html .= "data-arrival='" . $flight->stations->last()->pivot->arrival . "''";
                $html .= "data-date='" . date('d.m.Y') . "'><i class=\"fa fa-plus\"></i></button>";

                foreach ($flight->children as $children){
                    $html .= '<tr class="warning">';
                    $html .= '<td>' . date('d.m.Y', strtotime($date)) . '</td>';
                    $html .= '<td>' . $children->number . '</td>';
                    $html .= '<td>' . $children->stations->first()->pivot->departure . '</td>';
                    $html .= '<td>' . '<a href="/admin/flight/' . $children->id . '/' . $date . '">' . $children->stations->first()->title . ' - ' . $flight->stations->last()->title . '</a></td>';
                    $html .= '<td>' . $children->stations->last()->pivot->arrival . '</td>';
                    $html .= '<td>' . $children->buses->number . '</td>';
                    $html .= '<td>' . $children->countTicketDay($date) . ' из ' . $children->buses->places . '</td>';
                    $html .= '<td> <button class="btn btn-sm btn-primary edit_button" title="Редактировать дополнительный автобус"';
                    $html .= ' data-toggle="modal" data-target="#EditModal" data-id="' . $flight->id . '"';
                    $html .= " data-title='№:" . $children->number . $children->stations->first()->title . ' - ' . $children->stations->last()->title . "'";
                    $html .= " data-departure='" . $children->stations->first()->pivot->departure . "'";
                    $html .= " data-arrival='" . $children->stations->last()->pivot->arrival . "'";
                    $html .= " data-bus='" . $children->buses->id . "'";
                    $html .= " data-time='" . $children->time_dop . "'";
                    $html .= " data-date='" . date('d.m.Y', strtotime($date)) . "'><i class=\"fa fa-pencil\"></i></button>";
                    $html .= " <form action=\"admin/flights/".$children->id."/delete\" method=\"POST\" style=\"display: inline-block;\">";
                    $html .= " <input name=\"_method\" value=\"delete\" type=\"hidden\">";
                    $html .= "<input name=\"_token\" value=\"".csrf_token()."\" type=\"hidden\">";
                    $html .= " <button title=\"\" data-toggle=\"tooltip\" class=\"btn btn-xs btn-danger btn-delete\" data-original-title=\"Удалить\"><i class=\"fa fa-trash\"></i></button></form>";


                    //$html .= " <button class='btn btn-sm btn-danger remove_button' title='Удалить'>";
                    //$html .= 'data-toggle="modal" data-target="#removeModal" data-id="' . $flight->id . '"';
                    //$html .= "<i class='fa fa-remove'></i></button></td>";
                    $html .= '</tr>';
                }

                $html .= '</tr>';
            }
        }

        return $html;
    }
}
