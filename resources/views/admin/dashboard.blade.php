<div class="col-md-12">
    <div id="alert" class="alert alert-success alert-dismissible hidden">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

    </div>
</div>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-clipboard "></i> Проданные билеты на сегодняшние рейсы</div>
        <div class="panel-body">
            <div class="col-md-3">
                <form id="get_flight">
                <div class="form-group">
                    <label for="datetimepicker2">Дата</label>
                    <div class='input-group input-date' id='datetimepicker1'>
                        <input type='text' class="form-control" data-date-format="DD.MM.YYYY" data-date-pickdate="true" data-date-picktime="false" data-date-useseconds="false" name="date" value="{{date('d.m.Y')}}" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Поиск</button>
                        </span>
                    </div>
                </div>
                </form>
            </div>

            <table id="main_flight" class="table table-bordered">
                <thead>
                <tr class="info">
                    <th>Дата</th>
                    <th>№ рейса</th>
                    <th>Время</th>
                    <th>Маршрут</th>
                    <th>Прибытие</th>
                    <th>№ автобуса</th>
                    <th>Продано билетов</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($flights as $flight)
                    @if($flight->types->count() == 0) @continue @endif
                    @if(is_null($flight->parent_id) && ($flight->stations->first()))
                    <tr>
                        <td>{{date('d.m.Y')}}</td>
                        <td>{{$flight->number}}</td>
                        <td>{{$flight->stations->first()->pivot->departure}}</td>
                        <td>
                           <a href="{{url('/admin/flight/'.$flight->id.'/'.(date('Y-m-d')))}}">
                               {{ $flight->stations->first()->title .' - '. $flight->stations->last()->title}}
                            </a>
                        </td>
                        <td>{{$flight->stations->last()->pivot->arrival }}</td>
                        <td>{{$flight->buses->number}}</td>
                        <td>{{$flight->countTicketDay(date('Y-m-d'))}} из {{$flight->buses->places}}</td>
                        <td>
                            @if(is_null($flight->parent_id))
                            <button class="btn btn-sm btn-warning add_button" title="Добавить дополнительный автобус"
                                    data-toggle="modal" data-target="#myModal"
                                    data-id="{{$flight->id}}"
                                    data-title="{{'№:'. $flight->number . $flight->stations->first()->title .' - '. $flight->stations->last()->title}}"
                                    data-departure="{{$flight->stations->first()->pivot->departure}}"
                                    data-arrival="{{$flight->stations->last()->pivot->arrival}}"
                                    data-date="{{date('d.m.Y')}}">
                                <i class="fa fa-plus"></i>
                            </button>
                            @endif
                        </td>
                        @foreach($flight->children as $children)
                            <tr class="warning">
                                <td>{{date('d.m.Y',strtotime($children->start_date))}}</td>
                                <td>{{$children->number}}</td>
                                <td>{{$children->stations->first()->pivot->departure}}</td>
                                <td><a href="{{url('/admin/flight/'.$children->id.'/'.(date('Y-m-d')))}}">
                                        {{ $children->parent->stations->first()->title .' - '. $children->parent->stations->last()->title}}
                                    </a>
                                </td>
                                <td>{{$children->stations->last()->pivot->arrival }}</td>
                                <td>{{$children->buses->number}}</td>
                                <td>{{$children->countTicketDay(date('Y-m-d'))}} из {{$children->buses->places}}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit_button" title="Редактировать дополнительный автобус"
                                            data-toggle="modal" data-target="#EditModal"
                                            data-id="{{$children->id}}"
                                            data-title="{{'№:'. $children->number . $children->stations->first()->title .' - '. $children->stations->last()->title}}"
                                            data-departure="{{$children->stations->first()->pivot->departure}}"
                                            data-bus="{{$children->buses->id}}"
                                            data-driver="{{$children->driver->id}}"
                                            data-time="{{$children->time_dop}}"
                                            data-date="{{date('d.m.Y')}}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger remove_button" title="Удалить дополнительный автобус"
                                            data-toggle="modal" data-target="#removeModal"
                                            data-id="{{$children->id}}">
                                           <i class="fa fa-remove"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Copy -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Добавить дополнительный автобус</h4>
                </div>
                <form id="dop_flight">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Маршрут</label>
                            <input type="text" id="flight_title" class="form-control" value="" disabled="disabled">
                        </div>

                        <div class="form-group">
                            <label>Автобус</label><span class="form-element-required">*</span>
                            <select class="form-control" id="bus_id" name="bus_id" required>
                                <option value="0" disabled selected>Выбрать</option>
                                @foreach($buses as $bus)
                                    <option value="{{$bus->id}}">{{$bus->title .' (№: '.$bus->number.') '. $bus->places . ' мест' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Водитель</label><span class="form-element-required">*</span>
                            <select class="form-control" name="driver_id" id="driver_id" style="width:100%">
                                @foreach($drivers as $driver)
                                    <option value="{{$driver->id}}">{{$driver->full_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group form-element-date ">
                            <label for="start_date" class="control-label">Дата отправления дополнительного автобуса<span class="form-element-required">*</span></label>
                            <div class="input-date input-group">
                                <input data-date-format="DD.MM.YYYY" data-date-pickdate="true"  data-date-picktime="false" data-date-min-date="{{date('Y-m-d')}}"  id="start_date" name="start_date" value="" class="form-control" type="text" required>
                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-element-date ">
                                    <label for="start_date" class="control-label">Время отправления<span class="form-element-required">*</span></label>
                                    <div class="input-date input-group">
                                        <input data-date-format="HH:mm" data-date-pickdate="false" data-date-picktime="true" data-date-useseconds="false" id="time" name="time" value="" class="form-control" type="text" required>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="flight_id" name="flight_id" value="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Добавить дополнительный автобус</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Copy -->

    <!-- Modal for Edit button -->
    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Изменить время автобуса</h4>
                </div>
                <form id="dop_flight_edit">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Маршрут</label>
                            <input type="text" id="e_flight_title" class="form-control" value="" disabled="disabled">
                        </div>

                        <div class="form-group">
                            <label>Автобус</label><span class="form-element-required">*</span>
                            <select class="form-control" id="e_bus_id" name="bus_id" required>
                                @foreach($buses as $bus)
                                    <option value="{{$bus->id}}">{{$bus->title .' (№: '.$bus->number.') '. $bus->places . ' мест' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Водитель</label><span class="form-element-required">*</span>
                            <select class="form-control" name="driver_id" id="e_driver_id" style="width:100%">
                                @foreach($drivers as $driver)
                                    <option value="{{$driver->id}}">{{$driver->full_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group form-element-date ">
                            <label for="start_date" class="control-label">Дата отправления<span class="form-element-required">*</span></label>
                            <div class="input-date input-group">
                                <input  id="e_start_date"
                                       name="start_date" value="" class="form-control" type="text"  disabled required>
                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-element-date ">
                                    <label for="start_date" class="control-label">Время отправления<span class="form-element-required">*</span></label>
                                    <div class="input-date input-group">
                                        <input data-date-format="HH:mm" data-date-pickdate="false" data-date-picktime="true"
                                               data-date-useseconds="false"
                                               id="e_time"
                                               name="time" value="" class="form-control" type="text" required>
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="e_flight_id" name="flight_id" value="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Modal for Edit button -->



</div>

@if(Auth::user()->isSuperAdmin())

<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-clipboard "></i> Учёт проданных билетов</div>
        <div class="panel-body">
            <form id="accounting">
                <div class="form-group">
                    <label for="datetimepicker2">период:начало</label>
                    <div class='input-group input-date' id='datetimepicker1'>
                        <input type='text'  class="form-control" data-date-format="DD.MM.YYYY" data-date-pickdate="true"
                               data-date-picktime="false" data-date-useseconds="false" name="start_date" id="start_date" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="datetimepicker2">конец</label>
                    <div class='input-group input-date' id='datetimepicker2'>
                        <input type='text' class="form-control" data-date-format="DD.MM.YYYY" data-date-pickdate="true"
                               data-date-picktime="false" data-date-useseconds="false" name="end_date" id="end_date" />
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="form-group">
                    <button id="el_stat" type="submit" class="btn btn-default" ><i class="fa fa-eye"></i> Показать</button>
                </div>
            </form>
            <div id="searchResult">
               <p><strong>Онлайн оплачено: {{$online_paid}} руб.</strong></p>
                <p><strong>Оплачено в кассу: {{$cash_paid}} руб.</strong></p>
                <p><strong>Всего оплачено: {{$online_paid + $cash_paid}} руб.</strong></p>
            </div>
        </div>
    </div>
</div>


@endif
