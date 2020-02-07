
<div class="row">
    <div class="col-md-4">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="number" class="control-label">Номер рейса<span class="form-element-required">*</span></label>
                <div>
                    <input class="form-control" type="text" id="number" name="number" value="{{$flight->number}}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group form-element-date ">
            <label for="start_date" class="control-label">Начальная дата дейсвия рейса
                <span class="form-element-required">*</span></label>
            <div class="input-date input-group">
                <input data-date-format="DD.MM.YYYY" data-date-pickdate="true" data-date-picktime="false" data-date-useseconds="false" id="start_date" name="start_date" value="{{date('dd.mm.YYYY',strtotime($flight->start_date))}}" class="form-control" type="text"> <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group form-element-date ">
            <label for="end_date" class="control-label">Конечная дата дейсвия рейса<span class="form-element-required">*</span></label>
            <div class="input-date input-group">
                <input data-date-format="DD.MM.YYYY" data-date-pickdate="true" data-date-picktime="false" data-date-useseconds="false" id="end_date" name="end_date" value="{{date('dd.mm.YYYY',strtotime($flight->end_date))}}" class="form-control" type="text"> <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="bus_id" class="control-label">Автобус<span class="form-element-required">*</span></label>
                <div>
                    <select  class="js-station" name="bus_id" style="width:100%">
                        @foreach($busess as $bus)
                            @if($flight->buses_id == $bus->id)
                                <option selected value="{{$bus->id}}">{{$bus->title}}</option>
                            @else
                                <option value="{{$bus->id}}">{{$bus->title}} - {{$bus->number}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="driver_id" class="control-label">Водитель<span class="form-element-required">*</span></label>
                <div>
                    <select class="form-control" name="driver_id" style="width:100%">
                        @foreach($drivers as $driver)
                            @if($flight->driver_id == $driver->id)
                                <option selected value="{{$driver->id}}">{{$driver->full_name}}</option>
                            @else
                                <option value="{{$driver->id}}">{{$driver->full_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-md-4"></div>
    <div class="clearfix"></div>
    <div class="col-md-4">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="bus_id" class="control-label">Станция отправления<span class="form-element-required">*</span></label>
                <div>
                    <select id="station" class="js-station" name="station[0][id]" style="width:100%">
                        @foreach($stations as $station)
                            @if(isset($flight->stations()->first()->id) && $flight->stations()->first()->id  == $station->id)
                                <option selected value="{{$station->id}}">{{$station->title}}</option>
                            @else
                                <option value="{{$station->id}}">{{$station->title}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="bus_id" class="control-label">Время отправления<span class="form-element-required">*</span></label>
                <div class="input-group">
                    <input data-date-format="HH:mm" data-date-pickdate="false" data-date-picktime="true" data-date-useseconds="false" class="form-control timer" type="text" name="station[0][departure]"
                           value="{{isset($flight->stations->first()->pivot->departure) ? $flight->stations->first()->pivot->departure : null}}">
                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-5">
        <h3>Промежуточные станции</h3>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-3">
        <button id="add_st" class="btn btn-primary" type="button">Добавить станцию</button>
    </div>
    <div class="clearfix"></div>
    <div id="stations">
        @foreach($flight->stations as $station)
            @if ($loop->first || $loop->last)
                @continue;
            @endif
            <div class="mrow">
                <div class="col-md-4">
                    <div class="form-elements">
                        <div class="form-group form-element-text">
                            <label for="station" class="control-label">Станция прибытия<span class="form-element-required">*</span></label>
                            <div>
                                <select class="ajax-station" name="station[e-{{$loop->iteration}}][id]" style="width:100%">
                                    <option value="{{$station->id}}">{{$station->title}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-elements">
                        <div class="form-group form-element-text">
                            <label for="bus_id" class="control-label">Время прибытия<span class="form-element-required">*</span></label>
                            <div class="input-group">
                                <input data-date-format="HH:mm" data-date-pickdate="false" data-date-picktime="true" data-date-useseconds="false" class="form-control timer" type="text" name="station[e-{{$loop->iteration}}][arrival]"
                                       value="{{$station->pivot->arrival}}">
                                <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-elements">
                        <div class="form-group form-element-text">
                            <label for="bus_id" class="control-label">Время отправления</label>
                            <div class="input-group">
                                <input data-date-format="HH:mm" data-date-pickdate="false" data-date-picktime="true" data-date-useseconds="false" class="form-control timer" type="text" name="station[e-{{$loop->iteration}}][departure]"
                                       value="{{$station->pivot->departure}}">
                                <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
            </div>
            <div class="clearfix"></div>
        @endforeach

    </div>
    <div class="clearfix"></div>
    <div class="col-md-4">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="bus_id" class="control-label">Станция прибытия<span class="form-element-required">*</span></label>
                <div>
                    <select id="station" class="js-station" name="station[last][id]" style="width:100%">
                        @foreach($stations as $station)
                            @if(isset($flight->stations->last()->id) && $flight->stations->last()->id == $station->id)
                                <option selected value="{{$station->id}}">{{$station->title}}</option>
                            @else
                                <option value="{{$station->id}}">{{$station->title}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="bus_id" class="control-label">Время прибытия<span class="form-element-required">*</span></label>
                <div class="input-group">
                    <input class="form-control timer" data-date-format="HH:mm" data-date-pickdate="false" data-date-picktime="true" data-date-useseconds="false" type="text" name="station[last][arrival]"
                           value="{{isset($flight->stations->last()->pivot->arrival) ? $flight->stations->last()->pivot->arrival : null}}">
                    <span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4">
        <div class="form-elements">
            <div class="form-group form-element-text">
                <label for="status" class="control-label">Статус рейса<span class="form-element-required">*</span></label>
                <div>
                    <select class="js-station" name="status" style="width:100%">
                        @if($flight->status == 'В рейсе')
                            <option selected value="В рейсе">В рейсе</option>
                        @elseif($flight->status == 'Снят с рейса')
                            <option selected value="Снят с рейса">Снят с рейса</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>

</div>
