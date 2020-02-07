<style>
    body { font-family: DejaVu Sans, sans-serif; }
</style>
<div class="pabel panel-default">
    <div class="panel-body" style="background: #fff;">
        <hr>
        @foreach($tickets as $ticket)
            <h5>Билет № {{$loop->iteration}}</h5>
            <table class="table table-info" style="font-size: 11px;">
                <tr>
                    <td colspan="1"><strong>Заказ №:{{$ticket->order->id}}</strong></td>
                    @if($ticket->children == 1)
                        <td colspan="3"><strong style="color:red;">&laquo;Детский&raquo; </strong></td>
                    @elseif($ticket->cashless == 1)
                        <td colspan="3"><strong style="color:red;">&laquo;По безналу&raquo; </strong></td>
                    @endif
                </tr>
                <tr>
                    <td><i>Дата продажи:</i> {{date_format($ticket->created_at,'d.m.Y H:i')}}</td>
                    <td>
                        @if($order->saleStation)
                            <i>Место продажи:{{$order->saleStation->title}}</i>
                        @endif
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>№ рейса:</strong> {{$ticket->flight->number}}</td>
                    <td><strong>{{$ticket->stationA->title .' - '. $ticket->stationB->title}}</strong></td>
                    <td></td>
                    <td><strong>Дата: </strong>{{date('d.m.Y H:i',strtotime($ticket->date))}}</td>
                </tr>
                <tr>
                    <td><strong>Паспорт:</strong> {{$ticket->passport}}</td>
                    <td><strong>Место:</strong> {{$ticket->number}}</td>
                    <td></td>
                    <td><strong>Прибытия:</strong> {{date('d.m.Y H:i',strtotime($ticket->date_arrival))}}</td>
                </tr>
                <tr>
                    <td><strong>ФИО:</strong> {{$ticket->last_name . ' '. $ticket->name .' '. $ticket->middle_name}}</td>
                    <td><strong>Багаж:</strong> {{$ticket->baggage == 'yes' ? 'Есть (мест:'. $ticket->baggage_place .')' : 'Нет'}}</td>
                    <td></td>
                    <td>Code:{{$ticket->token}}</td>
                </tr>
                <tr>
                    <td><strong>Цена:</strong> {{$ticket->price}} руб.</td>
                    <td><strong>За багаж:</strong> {{$ticket->baggage_price != '' ? $ticket->baggage_price . ' руб.' : '-'}} </td>
                    <td></td>
                    <td><strong>Всего:</strong> {{$ticket->price + $ticket->baggage_price}} руб.</td>

                </tr>
                @if($ticket->status == 'return')
                    <tr>
                        <td rowspan="4"><h2 style="color: red">ВОЗВРАЩЕН</h2></td>
                    </tr>
                @endif
            </table>

            <p style="border:0.1px solid #3c3c3c"></p>
            <p style="font-size: 10px; font-style: italic">
                ООО «Городской автопарк»  ИНН 4101098695 ОГРН 1044100660273 683030, Россия, Камчатский край
                г. Петропавловск-Камчатский, Площадь Труда, д. 23/1
            </p>
            <hr>
        @endforeach
    </div>
</div>