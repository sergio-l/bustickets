<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse2"><i class="fa fa-handshake-o "></i> Возврат</a>
                </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse in">
                <table class="table table-striped table-bordered table-hover">
                    <tr class="warning">
                        <td><strong>ФИО</strong></td>
                        <td><strong>Станция отправления</strong></td>
                        <td><strong>Станция прибытия</strong></td>
                        <td><strong>Cтоимость</strong></td>
                        <td><strong>Возврат</strong></td>
                        <td><strong>%</strong></td>
                        <td><strong>Дата</strong></td>
                    </tr>
                    @foreach($returned_ticket as $item)
                    <tr>
                        <td>{{$item->ticket->full_name}}</td>
                        <td>{{$item->ticket->stationA->title}}</td>
                        <td>{{$item->ticket->stationB->title}}</td>
                        <td>{{$item->ticket->price + $item->ticket->baggage_price}} руб.</td>
                        <td style="color: red">{{number_format($item->suma,2)}} руб.</td>
                        <td>{{$item->percent}}%</td>
                        <td>{{date_format($item->created_at, 'd.m.Y H:i')}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
    </div>
</div>