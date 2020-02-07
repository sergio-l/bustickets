<div class="content body">
    @if(Session::has('success'))
        <div class="alert alert-success alert-message">
            <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button> <i class="fa fa-check fa-lg"></i> {{ Session::get('success') }}
        </div>
    @endif

    <div class="panel panel-default">
        <div class="form-elements">
            <div class="panel-body">
                <div id="fullcalendar" data-id="{{$flight->id}}"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add button -->
<div class="modal fade" id="addDriverModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Добавить водителя на рейс</h4>
            </div>
            <form id="driver_form">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-elements">
                                <div class="form-group form-element-text">
                                    <label for="stf" class="control-label">Дата:</label>
                                    <div class="input-date input-group">
                                        <input class="form-control" type="text"
                                               data-date-start-date="15.12.2018"
                                               data-date-format="DD.MM.YYYY"
                                               data-date-pickdate="true"
                                               data-date-picktime="false"
                                               data-date-useseconds="false"
                                               value="">
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-elements">
                                <div class="form-group form-element-text">
                                    <label for="stf" class="control-label">Водитель</label>
                                    <div>
                                        <select class="form-control" name="driver_id">
                                            @foreach($drivers as $driver)
                                                <option value="{{$driver->id}}">{{$driver->full_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden"  name="flight_id" value="{{$flight->id}}">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal for Add button -->

<!-- Modal for remove button -->
<div class="modal fade" id="removeDriverModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Убрать водителя с рейса ?</h4>
            </div>
            <form id="driver_remove">
                {{csrf_field()}}
                <div class="modal-footer">
                    <input type="hidden"  name="id" value="" id="pivot_id">
                    <input type="hidden"  name="flight_id" value="{{$flight->id}}">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Потверждаю</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal for Edit button -->
