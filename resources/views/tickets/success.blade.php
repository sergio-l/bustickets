@extends('layouts.app')
@section('title')Заказ@endsection
@section('content')
<section class="background-gray-lightest">
    <div class="container">
        @if($order->payment_method == 'cash')
        <div class="content">
            <h5 class="text-red text-center">Ваш заказ оформлен. Дополнительная информация будет выслана на ваш email, который Вы указали.</h5>
            <p class="text-center"><a href="{{url('/')}}">Перейти на главную страницу</a></p>
        </div>
        @elseif($order->payment_method == 'not-paid')
            <h5 class="text-red text-center">Ваш заказ оформлен. Для оплаты заказа нажмите кнопку Оплатить</h5>
            <form method="POST" action='http://gp41.server.paykeeper.ru/create/'>
                <input type='hidden' name='sum' value='{{$order->suma}}'/> <br />
                <input type='hidden' name='orderid' value='{{$order->id}}'/> <br />
                <input type='hidden' name='phone' value='{{$order->phone}}'/> <br />
                <input type="hidden" name="successURL" value="{{url('successPay')}}">
                <div class="text-center">
                    <input class="btn btn-warning btn-lg" type="submit" value="Оплатить">
                </div>
            </form>
        @endif
    </div>
</section>
@endsection