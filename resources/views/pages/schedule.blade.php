@extends('layouts.app')

@section('content')

<section>
    <div class="container">
        <div class="col-md-12">
            <table class="table table-bordered table-hover ">
                <tr class="info">
                    <th>№ рейса</th>
                    <th>Рейс</th>
                    <th>Отправления</th>
                    <th>Прибытия</th>
                    <th>Дни рейса</th>
                    <th></th>
                </tr>
                @foreach($flights as $flight)

                <tr>
                    <td class="col-md-1">{{$flight->number}}</td>
                    <td class="col-md-4">
                        {{isset($flight->stations->first()->title) ? $flight->stations->first()->title .' - '. $flight->stations->last()->title : ''}}
                    </td>
                    <td class="col-md-2">{{isset($flight->stations->first()->title) ? $flight->stations->first()->pivot->departure : null}}</td>
                    <td class="col-md-2">{{isset($flight->stations->last()->pivot->arrival) ? $flight->stations->last()->pivot->arrival : null}}</td>
                    <td>
                        @foreach($flight->types as $type)
                            {{$type->type}}
                        @endforeach
                    </td>
                    <td><a href="{{url('schedule/'.$flight->id.'/route')}}" target="_blank">Посмотреть маршрут</a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</section>
@endsection