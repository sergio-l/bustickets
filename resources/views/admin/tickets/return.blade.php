<form method="POST" action="{{url('admin/order/'.$order->id.'/return')}}" class="panel panel-default">
    <div class="form-elements" id="t">
        <div class="panel-body">
            <div class="form-elements">
                <div class="row">
                    <div class="col-md-12">
                        @foreach($order->tickets as $ticket)
                            <h5>Билет № {{$loop->iteration}}</h5>
                            <table class="table table-info" style="font-size: 13px;">
                                <tr>
                                    @if($ticket->children == 1)
                                        <td colspan="4"><strong style="color:red;">&laquo;Детский&raquo; </strong></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td><strong>№ рейса:</strong> {{$ticket->flight->number}}</td>
                                    <td><strong>{{$ticket->stationA->title .' - '. $ticket->stationB->title}}</strong></td>
                                    <td><strong></strong></td>
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
                                <tr>
                                    <td rowspan="4">
                                        @php
                                            $departure = new DateTime($ticket->date);
                                            $now = new DateTime();
                                            //$now->setDate(2018, 11, 29);
                                            //$now->setTime(18,0,15);
                                            //var_dump($now);
                                            $diff = date_diff($now,$departure);

                                            $percent = 1;
                                            if($now < $departure && $diff->h >= 2){
                                               $percent = 5;
                                            }elseif($now < $departure && $diff->h < 2 ){
                                               $percent = 15;
                                            }elseif($now > $departure){
                                                $percent = 25;
                                            }
                                        @endphp
                                        @if(!in_array($ticket->id, $arr_returned))
                                            <checkbox-return id="{{$ticket->id}}"
                                                             it="{{$loop->iteration}}"
                                                             sumaUpdated="suma = $event"
                                                             percent="{{$percent}}"
                                                             suma="{{$ticket->price + $ticket->baggage_price}}"></checkbox-return>
                                            <input type="hidden" name="percent" value="{{$percent}}">
                                        @else
                                            @foreach($returned_tickets as $item)
                                                @if($item->ticket_id == $ticket->id)
                                                    <p><strong style="color: red">Билет возвращен: {{date_format($item->created_at, 'd.m.Y H:i:s')}} </strong></p>
                                                    <p><strong style="color: red">Сумма возврата: {{number_format($item->suma,2) .' руб. ( %'.$item->percent.')'}}  </strong>

                                                    @break
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <hr>
                        @endforeach
                    </div>
                    @if(in_array($ticket->id, $arr_returned))
                        <div class="col-md-8">
                            <h4 style="color:green;">Общая сумма возврата по заказу: {{$returned_tickets->sum('suma')}} руб.
                                (билетов:{{$returned_tickets->count()}})</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <input name="_token" value="{{csrf_token()}}" type="hidden">
    <input name="_redirectBack" value="{{url('admin/order/'.$order->id.'/return')}}" type="hidden">
    <div class="form-buttons panel-footer">
        <div role="group" class="btn-group">
            @if($order->tickets->count() != count($arr_returned))
                <div class="btn-group">
                    <button type="submit" name="next_action" value="save_and_continue" class="btn btn-primary"><i class="fa fa-check"></i> Сделать возврат</button>
                </div>
            @endif
            <div class="btn-group">
                <a href="{{url('admin/orders/')}}" class="btn btn-warning"><i class="fa fa-ban"></i> Отмена</a>
            </div>
        </div>

    </div>
</form>

