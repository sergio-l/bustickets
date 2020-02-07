<form method="POST" action="{{url('admin/orders/'.$id.'/edit')}}" class="panel panel-default">
<div class="form-elements">
    <div class="panel-body">
        <div class="form-elements">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group form-element-text ">
                        <label for="status" class="control-label">Статус<span class="form-element-required">*</span></label>
                        <select class="form-control" name="status">
                            <option value="new" {{$orders->status == 'new' ? 'selected' : null}}>Новый</option>
                            <option value="processing" {{$orders->status == 'processing' ? 'selected' : null}}>Отменен</option>
                            <option value="success" {{$orders->status == 'success' ? 'selected' : null}}>Выполнен</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-element-text ">
                        <label for="phone" class="control-label">Телефон<span class="form-element-required">*</span></label>
                        <input type="text" name="phone" value="{{$orders->phone}}" class="form-control">

                    </div>
                </div>
            </div>
            @foreach($tickets as $ticket)
                <div class="row">
                <div class="col-md-3">
                  <div class="form-group form-element-text ">
                    <label for="tickets[{{$loop->iteration}}][last_name]" class="control-label">Фамилия<span class="form-element-required">*</span></label>
                    <input id="tickets[{{$loop->iteration}}][last_name]" name="tickets[{{$loop->iteration}}][last_name]" value="{{$ticket->last_name}}" class="form-control" type="text">
                    <input type="hidden" name="tickets[{{$loop->iteration}}][id]" value="{{$ticket->id}}">
                  </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-element-text ">
                        <label for="tickets[{{$loop->iteration}}][name]" class="control-label">Имя<span class="form-element-required">*</span></label>
                        <input id="tickets[{{$loop->iteration}}][name]" name="tickets[{{$loop->iteration}}][name]" value="{{$ticket->name}}" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-element-text ">
                        <label for="tickets[{{$loop->iteration}}][middle_name]" class="control-label">Отчество<span class="form-element-required">*</span></label>
                        <input id="tickets[{{$loop->iteration}}][middle_name]" name="tickets[{{$loop->iteration}}][middle_name]" value="{{$ticket->middle_name}}" class="form-control" type="text">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group form-element-text ">
                        <label for="tickets[{{$loop->iteration}}][passport]" class="control-label">Паспорт<span class="form-element-required">*</span></label>
                        <input id="tickets[{{$loop->iteration}}][[passport]" name="tickets[{{$loop->iteration}}][passport]" value="{{$ticket->passport}}" class="form-control" type="text">
                    </div>
                </div>
                    
                </div>
            @endforeach
        </div>
    </div>
</div>
    <input name="_token" value="{{csrf_token()}}" type="hidden">
    <input name="_redirectBack" value="{{url('admin/orders/'.$id.'/edit')}}" type="hidden">
    <div class="form-buttons panel-footer">
        <div role="group" class="btn-group">
            <button type="submit" name="next_action" value="save_and_continue" class="btn btn-primary"><i class="fa fa-check"></i> Сохранить</button>
            <div class="btn-group">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn dropdown-toggle"><i class="fa fa-caret-down"></i></button>
                <div class="dropdown-menu btn-actions">
                    <div class="btn-group-vertical">
                        <button type="submit" name="next_action" value="save_and_create" class="btn btn-info"><i class="fa fa-check"></i> Сохранить и создать</button>
                        <button type="submit" name="next_action" value="save_and_close" class="btn btn-success"><i class="fa fa-check"></i> Сохранить и закрыть</button>
                    </div>
                </div>
            </div> <a href="{{url('admin/orders/')}}" class="btn btn-warning"><i class="fa fa-ban"></i> Отмена</a>
        </div>
    </div>
</form>