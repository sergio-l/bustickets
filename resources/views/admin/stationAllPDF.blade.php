<style>
    body { font-family: DejaVu Sans, sans-serif; }
    table { width:50%;  border-collapse: collapse;  }
    table, th, td {  border: 1px solid black; font-size: 11px; }
    p{font-size: 14px; text-align: center;}
    .table{width:100%; }
    .text-center{text-align:center}
</style>


@php $date = Request::has('date') ? Request::get('date') : date('d.m.Y H:i'); @endphp

<h5 class="text-center "><strong>Станция Петропавловск-Камчатский {{$date}}</strong></h5>
<table id="table1" class="table table-striped table-bordered table-hover">
    <tr class="info">
        <td><strong>Кассир</strong></td>
        <td><strong>ФИО</strong></td>
        <td><strong>Детский</strong></td>
        <td><strong>Станция отправления</strong></td>
        <td><strong>Станция прибытия</strong></td>
        <td colspan="2" class="text-center"><strong>Стоимость</strong></td>
        <td><strong>Возврат</strong></td>
    </tr>
    <tr class="info">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано в кассе</strong></td>
        <td><strong>Продано online</strong></td>
        <td></td>
    </tr>
    @php $offline = 0; $online = 0; @endphp
    @foreach($stations['petropavlovsk'] as $ticket)
        <tr class="item">
            <td>
                @if($ticket->order->user_id)
                    {{$cashiers->whereIn('id', $ticket->order->user_id)->first()->full_name}}
                @endif
            </td>
            <td>{{$ticket->full_name}}</td>
            <td>{{ $ticket->children ? 'Да' : null}}</td>
            <td>{{$ticket->stationA->title}}</td>
            <td>{{$ticket->stationB->title}}</td>
            <td>@if($ticket->order->payment_method == 'cash')
                    @php $offline += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td>
                @if($ticket->order->payment_method == 'paid')
                    @php $online += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td style="color:red">
                @if($ticket->status == 'return' && $ticket->returnedTicket)
                    {{number_format($ticket->returnedTicket->suma,2)}} руб.
                @endif
            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано</strong></td>
        <td>{{$offline}} р.</td>
        <td>{{$online}} р.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по станции</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online}} р.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Возврат по станции</strong></td>
        <td colspan="2" class="text-center">{{$return = $returned_petropavlovsk->sum('suma')}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по кассе</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
        <td></td>
    </tr>
</table>

<h5 class="text-center"><strong>Станция Елизово {{$date}}</strong></h5>
<table id="table2" class="table table-striped table-bordered table-hover">
    <tr class="info">
        <td><strong>Кассир</strong></td>
        <td><strong>ФИО</strong></td>
        <td><strong>Детский</strong></td>
        <td><strong>Станция отправления</strong></td>
        <td><strong>Станция прибытия</strong></td>
        <td colspan="2" class="text-center"><strong>Стоимость</strong></td>
        <td><strong>Возврат</strong></td>
    </tr>
    <tr class="info">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано в кассе</strong></td>
        <td><strong>Продано online</strong></td>
        <td></td>
    </tr>
    @php $offline = 0; $online = 0; @endphp
    @foreach($stations['elizovo'] as $ticket)
        <tr>
            <td>
                @if($ticket->order->user_id)
                    {{$cashiers->whereIn('id', $ticket->order->user_id)->first()->full_name}}
                @endif
            </td>
            <td>{{$ticket->full_name}}</td>
            <td>{{ $ticket->children ? 'Да' : null}}</td>
            <td>{{$ticket->stationA->title}}</td>
            <td>{{$ticket->stationB->title}}</td>
            <td>@if($ticket->order->payment_method == 'cash')
                    @php $offline += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td>
                @if($ticket->order->payment_method == 'paid')
                    @php $online += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td style="color:red">
                @if($ticket->status == 'return' && $ticket->returnedTicket)
                    {{number_format($ticket->returnedTicket->suma,2)}} руб.
                @endif
            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано</strong></td>
        <td>{{$offline}} р.</td>
        <td>{{$online}} р.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по станции</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online}} р.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Возврат по станции</strong></td>
        <td colspan="2" class="text-center">{{$return = $returned_elizovo->sum('suma')}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по кассе</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
        <td></td>
    </tr>
</table>

<h5 class="text-center"><strong>Станция Мильково {{$date}}</strong></h5>
<table id="table3" class="table table-striped table-bordered table-hover">
    <tr class="info">
        <td><strong>Кассир</strong></td>
        <td><strong>ФИО</strong></td>
        <td><strong>Детский</strong></td>
        <td><strong>Станция отправления</strong></td>
        <td><strong>Станция прибытия</strong></td>
        <td colspan="2" class="text-center"><strong>Стоимость</strong></td>
        <td><strong>Возврат</strong></td>
    </tr>
    <tr class="info">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано в кассе</strong></td>
        <td><strong>Продано online</strong></td>
        <td></td>
    </tr>
    @php $offline = 0; $online = 0; @endphp
    @foreach($stations['milkovo'] as $ticket)
        <tr>
            <td>
                @if($ticket->order->user_id)
                    {{$cashiers->whereIn('id', $ticket->order->user_id)->first()->full_name}}
                @endif
            </td>
            <td>{{$ticket->full_name}}</td>
            <td>{{ $ticket->children ? 'Да' : null}}</td>
            <td>{{$ticket->stationA->title}}</td>
            <td>{{$ticket->stationB->title}}</td>
            <td>@if($ticket->order->payment_method == 'cash')
                    @php $offline += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td>
                @if($ticket->order->payment_method == 'paid')
                    @php $online += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td style="color:red">
                @if($ticket->status == 'return' && $ticket->returnedTicket)
                    {{number_format($ticket->returnedTicket->suma,2)}} руб.
                @endif
            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано</strong></td>
        <td>{{$offline}} руб.</td>
        <td>{{$online}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по станции</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Возврат по станции</strong></td>
        <td colspan="2" class="text-center">{{$return = $returned_milkovo->sum('suma')}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по кассе</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
        <td></td>
    </tr>
</table>

<h5 class="text-center"><strong>Станция У-Большерецк  {{$date}}</strong></h5>
<table id="table4" class="table table-striped table-bordered table-hover">
    <tr class="info">
        <td><strong>Кассир</strong></td>
        <td><strong>ФИО</strong></td>
        <td><strong>Детский</strong></td>
        <td><strong>Станция отправления</strong></td>
        <td><strong>Станция прибытия</strong></td>
        <td colspan="2" class="text-center"><strong>Стоимость</strong></td>
        <td><strong>Возврат</strong></td>
    </tr>
    <tr class="info">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано в кассе</strong></td>
        <td><strong>Продано online</strong></td>
        <td></td>
    </tr>
    @php $offline = 0; $online = 0; @endphp
    @foreach($stations['ust-bolshiretsk'] as $ticket)
        <tr>
            <td> @if($ticket->order->user_id)
                    {{$cashiers->whereIn('id', $ticket->order->user_id)->first()->full_name}}
                @endif
            </td>
            <td>{{$ticket->full_name}}</td>
            <td>{{ $ticket->children ? 'Да' : null}}</td>
            <td>{{$ticket->stationA->title}}</td>
            <td>{{$ticket->stationB->title}}</td>
            <td>@if($ticket->order->payment_method == 'cash')
                    @php $offline += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td>
                @if($ticket->order->payment_method == 'paid')
                    @php $online += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td style="color:red">
                @if($ticket->status == 'return' && $ticket->returnedTicket)
                    {{number_format($ticket->returnedTicket->suma,2)}} руб.
                @endif
            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано</strong></td>
        <td>{{$offline}} руб.</td>
        <td>{{$online}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по станции</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Возврат по станции</strong></td>
        <td colspan="2" class="text-center">{{$return = $returned_bolshiretsk->sum('suma')}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по кассе</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
        <td></td>
    </tr>
</table>

<h5 class="text-center"><strong>Станция п. Октябрский   {{$date}}</strong></h5>
<table id="table5" class="table table-striped table-bordered table-hover">
    <tr class="info">
        <td><strong>Кассир</strong></td>
        <td><strong>ФИО</strong></td>
        <td><strong>Детский</strong></td>
        <td><strong>Станция отправления</strong></td>
        <td><strong>Станция прибытия</strong></td>
        <td colspan="2" class="text-center"><strong>Стоимость</strong></td>
        <td><strong>Возврат</strong></td>
    </tr>
    <tr class="info">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано в кассе</strong></td>
        <td><strong>Продано online</strong></td>
        <td></td>
    </tr>
    @php $offline = 0; $online = 0; @endphp
    @foreach($stations['oktybrskiy'] as $ticket)
        <tr>
            <td>
                @if($ticket->order->user_id)
                    {{$cashiers->whereIn('id', $ticket->order->user_id)->first()->full_name}}
                @endif
            </td>
            <td>{{$ticket->full_name}}</td>
            <td>{{ $ticket->children ? 'Да' : null}}</td>
            <td>{{$ticket->stationA->title}}</td>
            <td>{{$ticket->stationB->title}}</td>
            <td>@if($ticket->order->payment_method == 'cash')
                    @php $offline += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td>
                @if($ticket->order->payment_method == 'paid')
                    @php $online += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}}
                @endif
            </td>
            <td style="color:red">
                @if($ticket->status == 'return' && $ticket->returnedTicket)
                    {{number_format($ticket->returnedTicket->suma,2)}} руб.
                @endif
            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Продано</strong></td>
        <td>{{$offline}} руб.</td>
        <td>{{$online}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по станции</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Возврат по станции</strong></td>
        <td colspan="2" class="text-center">{{$return = $returned_oktybrskiy->sum('suma')}} руб.</td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Итого по кассе</strong></td>
        <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
        <td></td>
    </tr>
</table>

<div class="col-md-8">
    <p><strong>Всего продано в кассе:</strong> {{$cash_count}} билетов на сумму {{$cash_paid}} руб.</p>
    <p><strong>Всего продано онлайн:</strong> {{$online_count}} билетов на сумму {{$online_paid}} руб.</p>
    <p><strong>Всего:</strong> {{$online_count + $cash_count}} билетов на сумму {{$cash_paid + $online_paid}} руб.</p>
    <p><strong>Возвращено:</strong> {{$returned_ticket->count()}} билетов на сумму {{$returned_ticket->sum('suma')}} руб.</p>
    <p><strong>Итоговая сумма:</strong> {{$online_paid + $cash_paid - $returned_ticket->sum('suma')}}  руб.</p>

</div>