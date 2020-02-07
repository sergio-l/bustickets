<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Маршрут рейса - {{$flight->stations->first()->title . ' - ' . $flight->stations->last()->title}} </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.blue.css')}}" id="theme-stylesheet">

</head>
<body>
<div class="row">
    <div class="col-md-6 col-md-offset-2">
        <h5 class="text-center"> Маршрут рейса № {{$flight->number}} : {{$flight->stations->first()->title . ' - ' . $flight->stations->last()->title}}</h5>
    <table class="table table-bordered table-hover">
        <tr class="info">
            <th></th>
            <th class="col-md-1">Прибытия</th>
            <th class="col-md-1">Отправления</th>
        </tr>
        @foreach($flight->stations as $station)
        <tr>
            <td class="col-md-3">{{$station->title}}</td>
            <td class="col-md-1">{{!empty($station->pivot->arrival) ? date('H:i',strtotime($station->pivot->arrival)) : null}}</td>
            <td class="col-md-1">{{!empty($station->pivot->departure) ? date('H:i',strtotime($station->pivot->departure)) : null}}</td>
        </tr>
        @endforeach
    </table>
    </div>
</div>

</body>
</html>