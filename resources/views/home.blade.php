@extends('layouts.app')
@section('title')Покупка билетов онлайн@endsection
@section('content')
<section>
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
        <p class="text-center warn-text">
            Просадка пассажира по электронному билету при оплате картой, производится после предъявления им документа, указанного в процессе оформлении билета: паспорта, прав, и т.д.
        </p>
        </div>
        <div class="clearfix"></div>
        <h3 class="text-center"><i class="fa fa-map-marker" aria-hidden="true"></i>
            Популярные направления</h3>
        <div class="col-md-12">
            <div class="list-group">
                @foreach($flights as $flight)
                    <div class="list-group-item">{{$flight->stationA->title}} - {{$flight->stationB->title}} <span class="badge"> {{$flight->price}} RUB</span></div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<section class="background-gray-lightest">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-2 col-sm-4">
                <div class="post" style="text-align: justify;">
                    <h3>Купить билет на автобус просто</h3>
                    <p class="post__intro">Когда нужно отправиться в другой населенный пункт на автобусе, меньше всего хочется ехать на шумный автовокзал и покупать там билеты. Автобус, на котором вы будете ехать, наверняка станет для вас сюрпризом – у вас вряд ли будет время выбрать модель транспорта перед окном кассы. Мы работаем для своих пассажиров!</p>
                </div>
            </div>
            <div class="col-sm-offset-1 col-sm-4">
                <div class="post" style="text-align: justify;">
                    <h3>Бесплатное бронирование</h3>
                    <p class="post__intro">С помощью нашей системы поиска рейсов вы сможете выбрать нужное направление, маршрут и даже подобрать нужное время автобус. Билеты на нужную дату вы без труда найдете при помощи поля поиска на сайте.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--   *** INTEGRATIONS ***-->
<section>
    <div class="container clearfix">
        <div class="row services">
            <div class="col-md-12">
                <h2 class="text-center">Преимущества бронирования</h2>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="box box-services">
                            <div class="icon"><i class="pe-7s-timer"></i></div>
                            <h4 class="heading">Скорость бронирования</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="box box-services">
                            <div class="icon"><i class="pe-7s-mouse"></i></div>
                            <h4 class="heading">Удобство бронирования</h4>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="box box-services">
                            <div class="icon"><i class="pe-7s-wallet"></i></div>
                            <h4 class="heading">Универсальная оплата</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
