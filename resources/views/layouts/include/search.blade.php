<div class="jumbotron main-jumbotron">
    <div class="container">
        <div class="content">
            <h1>Билеты на автобус</h1>
            <div class="searchForm">
                <form class="form-horizontal search" action="{{url('/search22')}}" method="get">
                    <div class="col-md-3">
                        <select class="form-control" id="st1" name="from" placeholder="Откуда">
                            @foreach($stations as $station)
                                <option value="{{$station->id}}">{{$station->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="st2" name="to" placeholder="Куда">
                            @foreach($stations as $station)
                                <option value="{{$station->id}}">{{$station->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 dtpicker">
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker'>
                                <input type='text' class="form-control"  />
                                <input type='hidden'  id="date" name="date" value="{{date('Y-m-d')}}" />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 ml10">
                        <div class="form-group">
                            <select class="form-control" name="person">
                                <option value="1">1 пассажир</option>
                                <option value="2">2 пассажира</option>
                                <option value="3">3 пассажира</option>
                                <option value="4">4 пассажира</option>
                                <option value="5">5 пассажиров</option>
                                <option value="6">6 пассажиров</option>
                                <option value="7">7 пассажиров</option>
                                <option value="8">8 пассажиров</option>
                                <option value="9">9 пассажиров</option>
                                <option value="10">10 пассажиров</option>
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

