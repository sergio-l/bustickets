<style>
    body { font-family: DejaVu Sans, sans-serif; }
    table { width:50%;  border-collapse: collapse;  }
    table, th, td {  border: 1px solid black; font-size: 11px; }
    p{font-size: 14px; text-align: center;}
    .table{width:100%; }
</style>
<div class="content body">
    <div class="panel-body">
        <div class="col-md-12">
            <p>Ведомость о пассажирах рейса: <br> <strong>№:{{$flight->number . $flight->stations->first()->title .' - '.
            $flight->stations->last()->title}}</strong><br>
            <strong>Дата:</strong> {{$date .'  '. $flight->stations->first()->pivot->departure }}<br>
            <strong>Водитель: </strong> {{$flight->driver->full_name}} <br>
            <strong>Автобус: </strong> {!! $flight->buses->title .' <small>('. $flight->buses->number .')</small>' !!}

            </p>
            <table class="table">
                <tr class="warning">
                    <td><strong>№</strong></td>
                    <td><strong>ФИО</strong></td>
                    <td><strong>Паспорт</strong></td>
                    <td><strong>Место</strong></td>
                    <td><strong>Багаж (мест)</strong></td>
                    <td><strong>Детский</strong></td>
                    <td><strong>От</strong></td>
                    <td><strong>До</strong></td>
                    <td><strong>Телефон</strong></td>
                    <td colspan="2" style="text-align: center"><strong>Стоимость</strong></td>
                </tr>
                <tr class="info">
                    <td colspan="9"></td>
                    <td><strong>Продано в кассе</strong></td>
                    <td><strong>Продано online</strong></td>
                </tr>
                @php $offline = 0; $online = 0; @endphp
                @foreach($flight->tickets as $ticket)
                    @if($ticket->order->payment_method == 'not-paid')
                        @continue
                    @endif
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$ticket->last_name . ' '. $ticket->name . ' '. $ticket->middle_name}}</td>
                        <td>{{$ticket->passport}}</td>
                        <td>{{$ticket->number}}</td>
                        <td>{{ $ticket->baggage == 'yes' ? $ticket->baggage_place : 0}}</td>
                        <td>{{ $ticket->children ? 'Да' : null}}</td>
                        <td>{{$ticket->stationA->title}}</td>
                        <td>{{$ticket->stationB->title}}</td>
                        <td>{{$ticket->order->phone}}</td>
                        <td style="text-align: center">{{$ticket->order->payment_method == 'cash' ? $ticket->price + $ticket->baggage_price .'руб. '  : '-'}}</td>
                        <td style="text-align: center">{{$ticket->order->payment_method == 'paid' ? $ticket->price  + $ticket->baggage_price .'руб. '  : '-'}}</td>
                        @php
                            $ticket->order->payment_method == 'cash' ? $offline += $ticket->price + $ticket->baggage_price :  0;
                            $ticket->order->payment_method == 'paid' ? $online += $ticket->price  + $ticket->baggage_price : 0;
                        @endphp

                    </tr>
                @endforeach
                <tr>
                    <td colspan="9"><strong>Общая сумма:</strong></td>
                    <td colspan="2" style="text-align: center"><strong>{{$offline + $online}} руб.</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>