@extends('layouts.app')

@section('title')Автобус - {{$bus->title}} @endsection
@section('content')
    <section>
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="{{url('/avtopark')}}">Автопарк</a></li>
            </ul>
            <div class="block">
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-heading"><h3>{{$bus->title}}</h3></div>
                        <div id="owl-demo" class="owl-carousel owl-theme">
                            <div class="item"><img src="{{url($bus->image)}}"></div>
                            @foreach($bus->images as $image)
                                <div class="item"><img src="{{url($image)}}"></div>
                            @endforeach
                        </div>

                        <div style="padding-left: 20px;">
                        <strong>Мест: </strong> {{$bus->places}}
                        {!! $bus->content !!}

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
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $("#owl-demo").owlCarousel({
                navigation : true, // Show next and prev buttons
                slideSpeed : 300,
                paginationSpeed : 400,
                singleItem:true,
                margin:5,
                loop:true
            });
        });
    </script>
@endsection