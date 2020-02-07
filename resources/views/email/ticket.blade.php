@if(!empty($order->token) && $order->payment_method == 'paid')
<h3>Ваш заказ. Перейдите по ссылки для получения билетов</h3>
<p style="color:red">Внимания ссылка действительна только один раз !!!</p>
<a href="{{url('http://gp41.ru/tickets/'.$order->token)}}">http://gp41.ru/tickets/{{$order->token}}</a>
@elseif($order->payment_method == 'cash')
    Уважаемый пользователь, номер Вашего заказа <strong>№:{{$order->id}}</strong>. Свой билет Вы сможете получить в кассе станции после оплаты. Просим Вас выкупить бронь за 40 минут до отправления автобуса. В противном случае бронь будет аннулирована. Спасибо.
@endif