@extends('layouts.app')

@section('title')Автопарк@endsection
@section('content')
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Выбрать автобус</h3>
                @foreach($buses as $bus)
                <div class="item  col-xs-4 col-lg-3 col-md-3">
                    <div class="thumbnail">
                        @if(!empty($bus->image))
                            <a href="{{url('avtopark/bus/'.$bus->id)}}">
                            <img class="group list-group-image" src="{{$bus->image}}" alt="" />
                            </a>
                        @else
                            <img class="group list-group-image" src="http://placehold.it/400x250/000/fff" alt="" />
                        @endif
                        <div class="caption">
                            <h4 class="group inner list-group-item-heading">{{$bus->title}}</h4>
                            <p class="group inner list-group-item-text">Мест: {{$bus->places}}</p>
                            <p class="group inner list-group-item-text">{!! $bus->content !!}</p>
                            <p></p>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <button class="btn btn-success order"
                                            data-toggle="modal"
                                            data-name="{{$bus->title}}"
                                            data-target="#orderModal" >
                                        Заказать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div id="orderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form id="sendRequest" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Забронировать <span style="color: red" id="busName"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label" for="name">Имя</label>
                        <input type="text" class="form-controll" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label" for="phone">Телефон</label>
                        <input type="text" class="form-controll" name="phone" id="phone">
                    </div>
                    <p class="statusMsg"></p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Отправить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
@section('scripts')
    <script src="{{asset('js/jquery.fancybox.js')}}"></script>
    <script>
        $(document).ready(function() {
            $(".fancybox-button").fancybox({
                prevEffect		: 'none',
                nextEffect		: 'none',
                closeBtn		: false,
                helpers		: {
                    title	: { type : 'inside' },
                    buttons	: {}
                }
            });
        });
    </script>
@endsection