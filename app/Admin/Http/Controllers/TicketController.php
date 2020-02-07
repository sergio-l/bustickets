<?php

namespace App\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use SleepingOwl\Admin\Admin;
use Validator;
use AdminSection;
use AdminFormElement;

use App\Model\Flight;
use App\Model\FlightPrice;
use App\Model\Station;
use App\Model\Ticket;
use App\Model\ReturnedTicket;
use App\Model\Order;
use Auth;
use Barryvdh\DomPDF\PDF;
use App;

class TicketController extends \SleepingOwl\Admin\Http\Controllers\AdminController
{

    public function get($id, Request $request   )
    {
        $tickets = Ticket::where(['order_id' => $id])->get();
        echo AdminSection::view(view('admin.tickets.show', ['order_id' => $id,'tickets' => $tickets]));
    }

    public function toPDF($id)
    {
        $order = Order::find($id);
        $tickets = Ticket::where(['order_id' => $order->id])->get();

        view()->share(['order' => $order, 'tickets' => $tickets]);
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.tickets.show');
        return $pdf->stream();
    }

    public function searchFlight(Request $request)
    {
        $data = $request->all();
        $data['date'] = Carbon::createFromFormat('d.m.Y', $data['date'])->toDateString();

        $flight = Flight::with(['stations' => function($query) use ($data){
            $query->whereIn('station_id', array($data['from'], $data['to']));
            if(isset($data['time']) && !empty($data['time'])){
                $query->where('');
            }
        }, 'price' => function($query) use ($data){
            $query->where('stationA_id', '=', $data['from']);
            $query->where('stationB_id', '=', $data['to']);
        }, 'allStationsFlight'])
            ->where('start_date', '<=', $data['date'])
            ->where('end_date', '>=', $data['date'])
            ->get();

        return view('admin.tickets.ajaxData',['flights' => $flight, 'data' => $data]);
        //return \Response::view('admin.tickets.ajaxData',['flight' => $flight]);
    }


    /**
     * Страница с формой оформления билета
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createForm(Request $request)
    {

        echo AdminSection::view(view('admin.tickets.form'));
    }

    public function update($id,Request $request)
    {
        $new_status = '';
        $data = $request->all();
        $orders = Order::findOrFail($id);
        if( $orders->status != $data['status']){
            $new_status = $data['status'];
        }
        $orders->status = $data['status'];
        $orders->phone  = $data['phone'];
        $orders->save();

        foreach ($data['tickets'] as $value)
        {
            $ticket = Ticket::find($value['id']);
            $ticket->last_name = $value['last_name'];
            $ticket->name = $value['name'];
            $ticket->middle_name = $value['middle_name'];
            $ticket->passport = $value['passport'];
            if($new_status == 'processing'){
                $ticket->status = 'return';

                $returned = new ReturnedTicket();
                $returned->ticket_id = $ticket->id;
                $returned->suma = $ticket->price + $ticket->baggage_price;
                $returned->percent =  0;
                $returned->station_id = Auth::user()->isCashier() ? Auth::user()->station_id : null;
                $returned->save();
            }
            $ticket->save();
        }

        $this->saveHistory($id, $new_status);

        if($data['next_action'] == 'save_and_close'){
            return redirect('/admin/orders');
        }
        return redirect($data['_redirectBack']);
    }


    public function returnTicket($id)
    {
        $order = Order::find($id);
        $tickets_id = $order->tickets()->pluck('tickets.id')->toArray();
        $returned_tickets = ReturnedTicket::whereIn('ticket_id', $tickets_id)->get();
        $arr_returned = $returned_tickets->pluck('ticket_id')->toArray(); //Массив возвращенных билетов

         return AdminSection::view(view('admin.tickets.return', [
             'order' => $order,
             'returned_tickets' => $returned_tickets,
             'arr_returned' => $arr_returned
        ]),'Заказ №:'.$order->id);
    }

    public function returnTicketPost(Request $request)
    {
        $data = $request->all();

        foreach($data['ticket'] as $item)
        {
            if(!isset($item['id']) || empty($item['id'])){
                continue;
            }
            
            $ticket = Ticket::find($item['id']);
            $ticket->status = 'return';
            $ticket->save();


            $returned = new ReturnedTicket();
            $returned->ticket_id = $ticket->id;
            $returned->suma = $item['sum'];
            $returned->percent =  $data['percent'];
            $returned->station_id = Auth::user()->isCashier() ? Auth::user()->station_id : null;
            $returned->save();

        }

        return redirect($data['_redirectBack']);
    }

    /**
     * Save edit history order
     * @param $id - order
     */
    protected function saveHistory($id, $new_status)
    {
        $item = new \App\Model\UserHistory();
        $item->user_id = Auth::user()->id;
        $item->actions = 'update';
        $item->page = url()->current();
        $item->status = !empty($new_status) ? $new_status : null;
        $item->order_id = $id;
        $item->save();
    }

}