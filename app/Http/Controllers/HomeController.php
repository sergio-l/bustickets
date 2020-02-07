<?php

namespace App\Http\Controllers;

use App\Model\Flight;
use App\Model\FlightPrice;
use App\Model\Page;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use App\Mail\OrderShipped;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $randomFlight = $this->popular();
        $stations = \App\Model\Station::get();
        return view('home', ['flights' => $randomFlight, 'stations' => $stations]);
    }

    public function schedule()
    {
        $flights = Flight::all();
        return view('pages.schedule', ['flights' => $flights]);
    }

    public function page(Request $request)
    {
        $alias = $request->path();
        $page = Page::where(['alias' => $alias])->limit(1)->get();

        return view('pages.page', ['page' => $page]);
    }

    /**
     * Страницы вкладки Услуги
     * @param Request $request
     * @return mixed
     */
    public function servicePage(Request $request)
    {
        $alias = $request->segment(2);

        if(!$alias){
            return redirect('/');
        }

        $places = $this->getPlaceByAlias($alias);

        $page = Page::where(['alias' => $alias])->limit(1)->get();
        $buses = \App\Model\Bus::limit(4)
            ->inRandomOrder()
            ->whereIn('places', $places)->get();

        return view('pages.service', ['page' => $page, 'buses' => $buses]);
    }

    /**
     * Узнаем примерную вместимость автобуса по алиасу http://gp41.ru/service/order_minibus
     * @param $alias
     */
    protected function getPlaceByAlias($alias)
    {
        switch($alias){
            case 'order_minibus': return [18,20,25];
            case 'order_bus': return [33,37,45,43];
            case 'order_organization': return [39];
        }
    }

    protected function popular()
    {
        return $flightPrice = FlightPrice::with('stationA', 'stationB')
            ->orderByRaw('RAND()')
            ->take(7)
            ->get();
    }

    public function route($id)
    {
        $flight = Flight::find($id);

        return view('pages.showRoute',['flight' => $flight]);
    }

    public function feedback(Request $request)
    {
        $data = $request->all();

        $secret = env('RECAPTCHA_SECRET_KEY');
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$data['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if($responseData->success == true){
            Mail::send('email.feedback', array('name' => $data['name'], 'email' => $data['email'],
                'msg' => $data['message']), function ($message) {
                $message->to('mejgorod@mail.ru')->subject('Обратная связь: Новое сообщения');
            });

            return \Response::json('ok');
        }
    }

    public function about()
    {
        return view('pages.about');
    }

    public function service()
    {
        return view('pages.service');
    }

    public function avtopark()
    {
        $buses = \App\Model\Bus::whereNotIn('id', [5,6,7,9,11,12,13])->get();

        return view('pages.avtopark', ['buses' => $buses]);
    }

    /**
     * Заказать автобус форма отправки
     * @param Request $request
     */
    public function orderBus(Request $request)
    {
        if($request->name && $request->phone) {
            Mail::send('email.order_bus', array('name' => $request->name, 'phone' => $request->phone,
                'bus' => $request->bus), function ($message) {
                //mejgorod@mail.ru
                $message->to('tursto@mail.ru')->subject('Заказать автобус');
                $message->to('mejgorod@mail.ru')->subject('Заказать автобус');
            });

            return \Response::json('ok');
        }
    }

    public function getBus($id)
    {
        $bus  = \App\Model\Bus::find($id);
        return view('pages.bus', ['bus' => $bus]);
    }
}
