<style>
    body { font-family: DejaVu Sans, sans-serif; }
    table { width:50%;  border-collapse: collapse;  }
    table, th, td {  border: 1px solid black; font-size: 11px; }
    p{font-size: 14px; text-align: center;}
    .table{width:100%; }
    .text-center{text-align:center}
</style>

<h5 class="text-center"><strong>{{ $stationName->title .'  '. $date .' '. date("H:i")}}</strong></h5>
<table class="table table-striped table-bordered table-hover">
    <tr class="info">
        <td><strong>Кассир</strong></td>
        <td><strong>ФИО</strong></td>
        <td><strong>Детский</strong></td>
        <td><strong>Отправления</strong></td>
        <td><strong>Прибытия</strong></td>
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
    @foreach($station as $ticket)
        <tr class="item">
            <td>
                @if($ticket->order->user_id)
                    {{$cashiers->whereIn('id', $ticket->order->user_id)->first()->full_name}}
                @endif
            </td>
            <td>{{$ticket->full_name}}</td>
            <td>{{$ticket->children ? 'Да' : null}}</td>
            <td>{{$ticket->stationA->title}}</td>
            <td>{{$ticket->stationB->title}}</td>
            <td>
                @if($ticket->order->payment_method == 'cash' || $ticket->order->payment_method == 'not-paid')
                    @php $offline += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}} руб.
                @endif
            </td>
            <td>
                @if($ticket->order->payment_method == 'paid')
                    @php $online += $ticket->price + $ticket->baggage_price @endphp
                    {{$ticket->price + $ticket->baggage_price}} руб.
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
        <td colspan="2" class="text-center">{{$return = $returned->sum('suma')}} руб.</td>
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