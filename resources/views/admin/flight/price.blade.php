<div class="content body">
    @if(Session::has('success'))
    <div class="alert alert-success alert-message">
        <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button> <i class="fa fa-check fa-lg"></i> {{ Session::get('success') }}
    </div>
    @endif

    <form method="POST" action="{{url("/admin/flights/".$flight->id."/price")}}" class="panel panel-default">
        <div class="panel-body">

          @if($flight->price->count() > 1)
                @for($i = 0; $i < count($combArray); $i++)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-elements">
                                <div class="form-group form-element-text">
                                    <label for="stf{{$i}}" class="control-label">От</label>
                                    <div>
                                        <input type="hidden" name="st[{{$i}}][id_first]" value="{{$combArray[$i][0]}}">
                                        <input class="form-control" type="text" id="stf{{$i}}" value="{{$combArray[$i][1]}}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-elements">
                                <div class="form-group form-element-text">
                                    <label for="stl{{$i}}" class="control-label">До</label>
                                    <div>
                                        <input type="hidden" name="st[{{$i}}][id_second]" value="{{$combArray[$i][2]}}">
                                        <input class="form-control" type="text" id="stl{{$i}}" value="{{$combArray[$i][3]}}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-elements">
                                <div class="form-group {!! $errors->has("st.".$i.".price") ? ' has-error' : '' !!}">
                                    <label for="number" class="control-label">Цена</label>
                                    <div>
                                        @foreach($flight->price as $value)
                                         @if($combArray[$i][0] == $value['stationA_id'] && $combArray[$i][2] == $value['stationB_id'])
                                            <input class="form-control" type="text" name="st[{{$i}}][price]" id="price"
                                               value="{{old("st.".$i.".price", isset($value->price) ? $value->price : null)}}">
                                            @break
                                        @endif
                                        @if($loop->last)
                                             <input class="form-control" type="text" name="st[{{$i}}][price]" id="price"
                                                    value="{{old("st.".$i.".price")}}">
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
          @else
                @for($i = 0; $i < count($combArray); $i++)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-elements">
                                <div class="form-group form-element-text">
                                    <label for="stf{{$i}}" class="control-label">От</label>
                                    <div>
                                        <input type="hidden" name="st[{{$i}}][id_first]" value="{{$combArray[$i][0]}}">
                                        <input class="form-control" type="text" id="stf{{$i}}" value="{{$combArray[$i][1]}}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-elements">
                                <div class="form-group form-element-text">
                                    <label for="stl{{$i}}" class="control-label">До</label>
                                    <div>
                                        <input type="hidden" name="st[{{$i}}][id_second]" value="{{$combArray[$i][2]}}">
                                        <input class="form-control" type="text" id="stl{{$i}}" value="{{$combArray[$i][3]}}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-elements">
                                <div class="form-group {!! $errors->has("st.".$i.".price") ? ' has-error' : '' !!}">
                                    <label for="number" class="control-label">Цена</label>
                                    <div>
                                        <input class="form-control" type="text" name="st[{{$i}}][price]" id="price"
                                               value="{{old("st.".$i.".price")}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
          @endif



        </div>
        <input name="_token" value="{{csrf_token()}}" type="hidden">
        <input name="_redirectBack" value="{{url("/admin/flights")}}" type="hidden">
        <div class="form-buttons panel-footer">
            <div role="group" class="btn-group">
                <button type="submit" name="next_action" value="save_and_continue" class="btn btn-primary"><i class="fa fa-check"></i> Сохранить</button>
                <div class="btn-group">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn dropdown-toggle"><i class="fa fa-caret-down"></i></button>
                    <div class="dropdown-menu btn-actions">
                        <div class="btn-group-vertical">
                            <button type="submit" name="next_action" value="save_and_close" class="btn btn-success"><i class="fa fa-check"></i> Сохранить и закрыть</button>
                        </div>
                    </div>
                </div> <a href="{{url("/admin/flights")}}" class="btn btn-warning"><i class="fa fa-ban"></i> Отмена</a>
            </div>
        </div>
    </form>
</div>