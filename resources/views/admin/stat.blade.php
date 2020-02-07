<div id="stat">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-clipboard "></i> Продано за период</div>
                <div class="panel-body">
                    <form id="statForm">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="datetimepicker2">с</label>
                                <div class='input-group input-date' id='datetimepicker1'>
                                    <input type='text' class="form-control" data-date-format="DD.MM.YYYY" data-date-pickdate="true" data-date-picktime="false" data-date-useseconds="false" name="start_date" />
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="datetimepicker2">до</label>
                                <div class='input-group input-date' id='datetimepicker2'>
                                    <input type='text' class="form-control" data-date-format="DD.MM.YYYY" data-date-pickdate="true" data-date-picktime="false" data-date-useseconds="false" name="end_date" />
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Кассир</label>
                            <select class="form-control" name="cashier">
                                    <option value="all">Все</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->last_name}} {{$user->name}} {{$user->middle_name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-2">
                            <span class="input-group-btn">
                            <button type="submit" class="btn btn-default" tabindex="-1">
                                    <i class="fa fa-eye"></i> Показать
                                </button>
                            </span>
                        </div>


                    </form>
                    <div id="res" class="col-md-6"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="#collapse1"><i class="fa fa-clipboard "></i> Статистика по кассам</a>
                    </h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                    <form id="station_form">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="datetimepicker2">Дата</label>
                                <div class='input-group input-date' id='datetimepicker3'>
                                    <input type='text' class="form-control"
                                           data-date-format="DD.MM.YYYY"
                                           data-date-pickdate="true"
                                           data-date-picktime="false"
                                           data-date-useseconds="false"
                                           value="{{Request::has('date') ? Request::get('date') : date('d.m.Y H:i')}}"
                                           name="date" />
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-eye"></i> Показать</button>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div class="clearfix"></div>
                    @php $date = Request::has('date') ? Request::get('date') : date('d.m.Y H:i'); @endphp
                    <div class="table-responsive">
                        <h5 class="text-center "><strong>Станция Петропавловск-Камчатский {{$date}}</strong></h5>
                        <table id="table1" class="table table-striped table-bordered table-hover">
                            <tr class="info">
                                <td><strong>Кассир</strong></td>
                                <td><strong>ФИО</strong></td>
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
                                <td><strong>Продано</strong></td>
                                <td>{{$offline}} руб.</td>
                                <td>{{$online}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
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
                                <td><strong>Возврат по станции</strong></td>
                                <td colspan="2" class="text-center">{{$return = $returned_petropavlovsk->sum('suma')}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Итого по кассе</strong></td>
                                <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a class="btn btn-primary" href="{{url('/admin/stat/station/1/toPDF?date='.$date)}}">Распечатать</a></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h5 class="text-center"><strong>Станция Елизово {{$date}}</strong></h5>
                        <table id="table2" class="table table-striped table-bordered table-hover">
                            <tr class="info">
                                <td><strong>Кассир</strong></td>
                                <td><strong>ФИО</strong></td>
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
                                    <td>{{$ticket->stationA->title}}</td>
                                    <td>{{$ticket->stationB->title}}</td>
                                    <td>@if($ticket->order->payment_method == 'cash' || $ticket->order->payment_method == 'not-paid')
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
                                <td><strong>Продано</strong></td>
                                <td>{{$offline}} руб.</td>
                                <td>{{$online}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
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
                                <td><strong>Возврат по станции</strong></td>
                                <td colspan="2" class="text-center">{{$return = $returned_elizovo->sum('suma')}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Итого по кассе</strong></td>
                                <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a class="btn btn-primary" href="{{url('/admin/stat/station/2/toPDF?date='.$date)}}">Распечатать</a></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h5 class="text-center"><strong>Станция Мильково {{$date}}</strong></h5>
                        <table id="table3" class="table table-striped table-bordered table-hover">
                            <tr class="info">
                                <td><strong>Кассир</strong></td>
                                <td><strong>ФИО</strong></td>
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
                                    <td>{{$ticket->stationA->title}}</td>
                                    <td>{{$ticket->stationB->title}}</td>
                                    <td>@if($ticket->order->payment_method == 'cash' || $ticket->order->payment_method == 'not-paid')
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
                                <td><strong>Продано</strong></td>
                                <td>{{$offline}} руб.</td>
                                <td>{{$online}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
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
                                <td><strong>Возврат по станции</strong></td>
                                <td colspan="2" class="text-center">{{$return = $returned_milkovo->sum('suma')}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Итого по кассе</strong></td>
                                <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a class="btn btn-primary" href="{{url('/admin/stat/station/9/toPDF?date='.$date)}}">Распечатать</a></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h5 class="text-center"><strong>Станция У-Большерецк  {{$date}}</strong></h5>
                        <table id="table4" class="table table-striped table-bordered table-hover">
                            <tr class="info">
                                <td><strong>Кассир</strong></td>
                                <td><strong>ФИО</strong></td>
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
                                <td><strong>Продано в кассе</strong></td>
                                <td><strong>Продано online</strong></td>
                                <td></td>
                            </tr>
                            @php $offline = 0; $online = 0; @endphp
                            @foreach($stations['ust-bolshiretsk'] as $ticket)
                                <tr>
                                    <td>
                                        @if($ticket->order->user_id)
                                            {{$cashiers->whereIn('id', $ticket->order->user_id)->first()->full_name}}
                                        @endif
                                    </td>
                                    <td>{{$ticket->full_name}}</td>
                                    <td>{{$ticket->stationA->title}}</td>
                                    <td>{{$ticket->stationB->title}}</td>
                                    <td>@if($ticket->order->payment_method == 'cash' || $ticket->order->payment_method == 'not-paid')
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
                                <td><strong>Итого по станции</strong></td>
                                <td colspan="2" class="text-center">{{$offline + $online}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
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
                                <td><strong>Итого по кассе</strong></td>
                                <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a class="btn btn-primary" href="{{url('/admin/stat/station/13/toPDF?date='.$date)}}">Распечатать</a></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <h5 class="text-center"><strong>Станция п. Октябрский   {{$date}}</strong></h5>
                        <table id="table5" class="table table-striped table-bordered table-hover">
                            <tr class="info">
                                <td><strong>Кассир</strong></td>
                                <td><strong>ФИО</strong></td>
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
                                    <td>{{$ticket->stationA->title}}</td>
                                    <td>{{$ticket->stationB->title}}</td>
                                    <td>@if($ticket->order->payment_method == 'cash' || $ticket->order->payment_method == 'not-paid')
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
                                <td><strong>Продано</strong></td>
                                <td>{{$offline}} руб.</td>
                                <td>{{$online}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
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
                                <td><strong>Возврат по станции</strong></td>
                                <td colspan="2" class="text-center">{{$return = $returned_oktybrskiy->sum('suma')}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Итого по кассе</strong></td>
                                <td colspan="2" class="text-center">{{$offline + $online - $return}} руб.</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a class="btn btn-primary" href="{{url('/admin/stat/station/14/toPDF?date='.$date )}}">Распечатать</a></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <td><strong>Всего продано в кассах:</strong> {{$cash_count}} билетов на сумму {{$cash_paid}} руб.</td>
                            </tr>
                            <tr>
                                <td><strong>Всего продано онлайн:</strong> {{$online_count}} билетов на сумму {{$online_paid}} руб.</td>
                            </tr>
                            <tr>
                                <td><strong>Всего продано:</strong> {{$online_count + $cash_count}} билетов на сумму {{$cash_paid + $online_paid}} руб.</td>
                            </tr>
                            <tr>
                                <td><strong>Возвращено:</strong> {{$returned_ticket->count()}} билетов на сумму {{$returned_ticket->sum('suma')}} руб.</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Итоговая сумма:</strong> {{$online_paid + $cash_paid - $returned_ticket->sum('suma')}}  руб.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a class="btn btn-primary" href="{{url('/admin/stat/all/toPDF?date='.$date )}}">Распечатать все</a>
                                </td>
                            </tr>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>

    @include('admin.return_ticket', ['returned_ticket' => $returned_ticket])

</div>