<div class="pabel panel-default">
  <div class="panel-body">
@if($flights->count() > 0)
  <table class="table table-striped">
      <tr>
          <th>№ рейса</th>
          <th>Откуда/Куда</th>
          <th>Дата</th>
          <th>Отправление/Прибытия</th>
          <th>В пути</th>
          <th>Свободных мест</th>
          <th></th>
      </tr>
    @foreach($flights as $flight)
        @if($flight->stations->count() < 2 || $flight->price->count() == 0)
            @continue
        @endif
        @php
            \Carbon\Carbon::setLocale('ru');
                $startTime = \Carbon\Carbon::parse($flight->stations->first()->pivot->departure);
                $finishTime = \Carbon\Carbon::parse($flight->stations->last()->pivot->arrival);
                $totalDuration = $finishTime->diffForHumans($startTime);
        @endphp
        <tr>
            <td>{{$flight->number}}</td>
            <td>{!! $flight->stations->first()->title .'/'. $flight->stations->last()->title !!}</td>
            <td></td>
            <td>
                <strong>{{date('H:i',strtotime($flight->stations->first()->pivot->departure))}}</strong> -
                <strong>{{date('H:i',strtotime($flight->stations->last()->pivot->arrival))}}</strong>
            </td>
            <td><span>{{ str_replace('после',' ',$totalDuration) }}</td>
            <td></td>
            <td>
                <form action="{{route('admin.tickets.checkout')}}" method="GET">
                    <input type="hidden" name="flight" value="{{$flight->id}}">
                    <input type="hidden" name="from" value="{{$flight->stations->first()->id}}">
                    <input type="hidden" name="to" value="{{$flight->stations->last()->id}}">
                    <input type="hidden" name="date" value="{{$data['date']}}">
                    <input type="hidden" name="time" value="{{$data['time']}}">
                    <input type="hidden" name="stationA" value="{{$flight->stations->first()->title}}">
                    <input type="hidden" name="stationB" value="{{$flight->stations->last()->title}}}">
                    <input type="hidden" name="departure" value="{{$flight->stations->first()->pivot->departure}}">
                    <input type="hidden" name="departure" value="{{$flight->stations->last()->pivot->arrival}}">
                    <button class="btn btn-primary">Выбрать</button>
                </form>
            </td>
        </tr>
    @endforeach
  </table>
@endif
    </div>
</div>