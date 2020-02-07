@if(!empty($flights) && $flights->count() > 0)
@foreach($flights as $flight)
    @if($flight->stations->count() < 2) @continue @endif
    @if($flight->price->count() == 0) @continue @endif
    @if($flight->types->count() == 0) @continue @endif
    @php
        $places = $flight->buses->places - $flight->tickets->count();
        \Carbon\Carbon::setLocale('ru');
         $startTime = \Carbon\Carbon::parse($flight->stations->first()->pivot->departure);
         $finishTime = \Carbon\Carbon::parse($flight->stations->last()->pivot->arrival);
         $totalDuration = $finishTime->diffInSeconds($startTime);
    @endphp
    <tr>
        <td>{{$flight->number}}</td>
        <td>{{$flight->stations->first()->title  .' - '. $flight->stations->last()->title}}</td>
        <td>отправления:{{$startTime->format('H:i')}},<br>прибытия:{{$finishTime->format("H:i")}}</td>
        <td> <i class="fa fa-clock-o"></i> {{gmdate("H:i", $totalDuration)}}<br></td>
        <td>{{$flight->tickets->count()}} из {{$flight->buses->places}}</td>
        <td>{{$flight->price->first()->price}} руб.</td>
        <td>
            @if($places > 0)
            <form method="post" action="{{'/admin/orders/checkout'}}">
                {{csrf_field()}}
                 <input type="hidden" name="flight_id" value="{{$flight->id}}">
                 <input type="hidden" name="date" value="{{$date}}">
                 <input type="hidden" name="place" value="{{$place}}">
                 <input type="hidden" name="flight_price" value="{{$flight->price->first()->price}}">
                 <input type="hidden" name="from" value="{{$flight->stations->first()->id}}">
                 <input type="hidden" name="to" value="{{$flight->stations->last()->id}}">
                 <input type="hidden" name="departure" value="{{date('H:i',strtotime($flight->stations->first()->pivot->departure))}}">
                 <input type="hidden" name="arrival" value="{{date('H:i',strtotime($flight->stations->last()->pivot->arrival))}}">
                <button class="btn btn-warning">Оформить</button>
            </form>
            @endif
        </td>
    </tr>
@endforeach
@endif
