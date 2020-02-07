@extends('layouts.app')

@section('content')
<section class="background-gray-lightest">
    <div class="container">
        <div class="col-md-12">
        @if(!empty($flights) && $flights->count() > 0)
            @php $check = false; @endphp
            @foreach($flights as $flight)
                @php $places = $flight->buses->places - $flight->tickets->count(); @endphp
                @if($flight->stations->count() < 2 || $data['person'] > $places) @continue @endif
                @if($flight->price->count() == 0) @continue @endif
                @if($flight->types->count() == 0) @continue @endif

                @if($data['date'] == \Carbon\Carbon::now()->toDateString()
                and $flight->stations->first()->pivot->departure < \Carbon\Carbon::now()->toTimeString()) @continue @endif
                <div class="searchResult">
                   <div class="main">
                    <div class="row">
                        <div class="col-sm-1">
                            <div class="from">
                                <div class="time">
                                    <span>{{date('H:i',strtotime($flight->stations->first()->pivot->departure))}}</span>
                                    <div class="date">{{date('d.m.Y', strtotime($data['date']))}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="station">{{$flight->stations->first()->title}}</div>
                        </div>
                        <div class="col-sm-1">
                            <div class="from">
                                <div class="time">
                                    <span>{{date('H:i',strtotime($flight->stations->last()->pivot->arrival))}}</span>
                                    <div class="date">{{date('d.m.Y', strtotime($data['date']))}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="station">{{$flight->stations->last()->title}}</div>
                        </div>

                        @php
                        \Carbon\Carbon::setLocale('ru');
                            $startTime = \Carbon\Carbon::parse($flight->stations->first()->pivot->departure);
                            $finishTime = \Carbon\Carbon::parse($flight->stations->last()->pivot->arrival);

                            $totalDuration = $finishTime->diffInSeconds($startTime);
                        @endphp
                        <div class="col-sm-2">
                            <div class="takes-time" data-takes-time="true">
                                <i class="fa fa-clock-o"></i> <span>в&nbsp;пути</span> {{gmdate("H:i", $totalDuration)}}<br>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="price">
                                <span class="significant">
                                    @if($data['person'])
                                        {{$flight->price->first()->price * $data['person']}}
                                     @else
                                        {{$flight->price->first()->price}}
                                   @endif
                               </span>
                               <span class="currency">RUB</span>
                            </div>
                            <span class="places">Осталось мест: {{$places  }}</span>

                            @php $currDate = Carbon\Carbon::now();
                                 $diffTime = $currDate->diffInSeconds($startTime);
                            @endphp

                            @if($settings->site_work == 'enabled')
                            <form method="post" action="{{url('/checkout')}}">
                                {{csrf_field()}}
                                <input type="hidden" name="flight" value="{{$flight->id}}">
                                <input type="hidden" name="from" value="{{$flight->stations->first()->id}}">
                                <input type="hidden" name="to" value="{{$flight->stations->last()->id}}">
                                <input type="hidden" name="date" value="{{$data['date']}}">
                                <input type="hidden" name="person" value="{{$data['person']}}">
                                @if(($currDate->format('Y-m-d') == $data['date'] && $diffTime < $settings->book_time * 60) || $places < 1)
                                    <button  class="btn btn-blue" disabled>Выбрать</button>
                                @else
                                    <button  class="btn btn-blue">Выбрать</button>
                                @endif
                            </form>
                            @else
                                Бронирования билетов временно не работает
                            @endif
                        </div>
                            <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
                @php $check = true; @endphp
            @endforeach
                @if( !$check) <h5>По Вашему запросу рейсов не найдено</h5>@endif
        @else
            <h5>По Вашему запросу рейсов не найдено</h5>
        @endif

        </div>
    </div>
</section>

@endsection
