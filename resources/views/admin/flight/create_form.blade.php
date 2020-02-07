<div class="content body">
    <form method="POST" action="{{url('/admin/flights/create')}}" class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-elements">
                        <div class="form-group form-element-text">
                            <label for="number" class="control-label">Номер рейса<span class="form-element-required">*</span></label>
                            <div>
                                <input class="form-control" name="number" type="text" id="number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">{!! $elements['types'] !!}</div>
            </div>
            <div class="row">
                <div class="col-md-4">{!! $elements['start_date'] !!}</div>
                <div class="col-md-4">{!! $elements['end_date'] !!}</div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-elements">
                        <div class="form-group form-element-text">
                            <label for="bus_id" class="control-label">Автобус<span class="form-element-required">*</span></label>
                            <div>
                                <select class="form-control" name="bus_id" style="width:100%">
                                    @foreach($busess as $bus)
                                        <option value="{{$bus->id}}">{{$bus->title}} - {{$bus->number}}</option>
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
                                        <option value="{{$driver->id}}">{{$driver->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-4">
                    <div class="form-elements">
                        <div class="form-group form-element-text">
                            <label for="bus_id" class="control-label">Станция отправления<span class="form-element-required">*</span></label>
                            <div>
                                <select id="station" class="js-station" name="station[0][id]" style="width:100%">
                                    @foreach($stations as $station)
                                        <option value="{{$station->id}}">{{$station->title}}</option>
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
                                <input class="form-control timer" data-date-format="HH:mm" data-date-pickdate="false" data-date-picktime="true" data-date-useseconds="false" type="text" name="station[0][departure]">
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
                <div id="stations"></div>
                <div class="clearfix"></div>
                <div class="col-md-4">
                    <div class="form-elements">
                        <div class="form-group form-element-text">
                            <label for="station" class="control-label">Станция прибытия (конечная)<span class="form-element-required">*</span></label>
                            <div>
                                <select class="js-station" name="station[last][id]" style="width:100%">
                                    @foreach($stations as $station)
                                        <option value="{{$station->id}}">{{$station->title}}</option>
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
                                <input class="form-control timer" data-date-format="HH:mm" data-date-pickdate="false" data-date-picktime="true" data-date-useseconds="false" type="text" name="station[last][arrival]">
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
                                <select  class="js-station" name="status" style="width:100%">
                                    <option value="В рейсе">В рейсе</option>
                                    <option value="Снят с рейса">Снят с рейса</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <input name="_token" value="{{csrf_token()}}" type="hidden">
        <input name="_redirectBack" value="{{url('/admin/flights')}}" type="hidden">
        <div class="form-buttons panel-footer">
            <div role="group" class="btn-group">
                <button type="submit" name="next_action" value="save_and_continue" class="btn btn-primary"><i class="fa fa-check"></i> Сохранить</button>
                <div class="btn-group">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn dropdown-toggle"><i class="fa fa-caret-down"></i></button>
                    <div class="dropdown-menu btn-actions">
                        <div class="btn-group-vertical">
                            <button type="submit" name="next_action" value="save_and_create" class="btn btn-info"><i class="fa fa-check"></i> Сохранить и создать</button>
                            <button type="submit" name="next_action" value="save_and_close" class="btn btn-success"><i class="fa fa-check"></i> Сохранить и закрыть</button>
                        </div>
                    </div>
                </div> <a href="{{url('/admin/flights')}}" class="btn btn-warning"><i class="fa fa-ban"></i> Отмена</a>
            </div>
        </div>
    </form>
</div>

