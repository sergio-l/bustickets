<div class="row">
<form id="regForm" action="#">
    <div class="row after-add-more">
        <div class="col-md-3">
            <div class="form-elements">
                <div class="input-group control-group">
                    <label for="from" class="control-label">Станция отправления <span class="form-element-required">*</span></label>
                    <select  class="form-control" id="from" name="from" >
                        @foreach($stations as $station)
                            <option value="{{$station->id}}">{{$station->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-elements">
                <div class="input-group control-group">
                    <label for="to" class="control-label">Станция прибытия <span class="form-element-required">*</span></label>
                    <select  class="form-control" id="to" name="to">
                        @foreach($stations as $station)
                            <option value="{{$station->id}}">{{$station->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-elements">
                <div class="input-group control-group">
                    <label for="to" class="control-label">Мест <span class="form-element-required">*</span></label>
                    <select  class="form-control" id="place" name="place">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-elements">
                <label for="date" class="control-label">
                    Дата<span class="form-element-required">*</span></label>
                <div class="input-date input-group">
                    <input data-date-format="DD.MM.YYYY" data-date-pickdate="true" data-date-picktime="false" data-date-useseconds="false" id="date" name="date" value="{{date('d.m.Y')}}" class="form-control" type="text"> <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button id="search_flight" type="button" class="btn btn-primary">Поиск рейса</button>
        </div>
    </div>
</form>
</div>

<div class="row">
    <table id="result" class="table table-bordered">
        <tr>
            <td>№</td>
            <td>Откуда/Куда</td>
            <td>Дата/Время</td>
            <td>В пути</td>
            <td>Мест</td>
            <td>Цена</td>
        </tr>
    </table>
</div>

<script>

</script>