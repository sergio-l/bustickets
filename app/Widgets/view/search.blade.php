<div class="jumbotron main-jumbotron">
    <div class="container">
        <div class="content">
            <h1>Билеты на автобус</h1>
            <div class="searchForm">
                <form class="form-horizontal search" action="{{url('search')}}" method="get">
                    <div class="col-md-3">
                        <select class="form-control " id="st1" name="from" placeholder="Откуда">
                            <option></option>
                            @foreach($stations as $station)
                                <option value="{{$station->id}}" {{Request::query('from') == $station->id ? 'selected' : null}}>
                                    {{$station->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="st2" name="to" placeholder="Куда">
                            <option></option>
                            @foreach($stations as $station)
                                <option value="{{$station->id}}" {{Request::query('to') == $station->id ? 'selected' : null}}>
                                    {{$station->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 dtpicker">
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker'>
                                <input type='text' class="form-control" value="{{Request::query('date') ? Request::query('date') : date('Y-m-d')}}" />
                                <input type='hidden'  id="date" name="date"
                                       value="{{Request::query('date') ? Request::query('date') : date('Y-m-d')}}" />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 ml10">
                        <div class="form-group">
                            <select class="form-control" name="person">
                                <option value="1" {{Request::query('person') == 1 ? 'selected' : null}}>1 пассажир</option>
                                <option value="2" {{Request::query('person') == 2 ? 'selected' : null}}>2 пассажира</option>
                                <option value="3" {{Request::query('person') == 3 ? 'selected' : null}}>3 пассажира</option>
                                <option value="4" {{Request::query('person') == 4 ? 'selected' : null}}>4 пассажира</option>
                                <option value="5" {{Request::query('person') == 5 ? 'selected' : null}}>5 пассажиров</option>
                                <option value="6" {{Request::query('person') == 6 ? 'selected' : null}}>6 пассажиров</option>
                                <option value="7" {{Request::query('person') == 7 ? 'selected' : null}}>7 пассажиров</option>
                                <option value="8" {{Request::query('person') == 8 ? 'selected' : null}}>8 пассажиров</option>
                                <option value="9" {{Request::query('person') == 9 ? 'selected' : null}}>9 пассажиров</option>
                                <option value="10" {{Request::query('person') == 10 ? 'selected' : null}}>10 пассажиров</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary">Поиск</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
