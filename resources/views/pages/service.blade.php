@extends('layouts.app')

@section('title') {{$page->first()->title}}  @endsection

@section('content')
<section>
    <div class="container">
        <div class="col-md-12">
            @foreach($buses as $bus)
            <div class="item  col-xs-4 col-lg-3 col-md-3">
                <div class="thumbnail">
                    <img class="group list-group-image" src="{{url($bus->image)}}" alt="" />
                    <div class="caption">
                        <h4 class="group inner list-group-item-heading">{{$bus->title}}</h4>
                        <p class="group inner list-group-item-text">Мест: {{$bus->places}}</p>
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
        <div class="col-md-12">
            {!! $page->first()->content !!}
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