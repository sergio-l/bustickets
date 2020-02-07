
$(document).ready(function() {
    $('.js-station').select2({ width: 'resolve'});
    $('.timer').datetimepicker({
        format: 'hh:mm'
    });
    AjaxSearch();

    var k = 0; //Счетчик станций

    $('#add_st').on('click', function(){
        k++;
        var row = document.createElement('div');
        var st = document.getElementById('stations');
        row.className = 'mrow';
        st.appendChild(row);

        createElement('select', 'station['+k+'][id]','Станция', 4, false);
        createElement('input', 'station['+k+'][arrival]','Время прибытия', 3, false);
        createElement('input', 'station['+k+'][departure]', 'Время отправления', 3, true);

        AjaxSearch();
        $('.timer2').datetimepicker({ format: 'hh:mm' });
    });

    function createElement(typeElement, name, labelName, rows, btn)
    {
        var col_div = document.createElement('div');
        var elements_div = document.createElement('div');
        var form_group = document.createElement('div');
        var label = document.createElement('label');
        var div = document.createElement('div');
        var element = document.createElement(typeElement);

        col_div.className='col-md-'+rows;
        elements_div.className='form-elements';
        form_group.className = 'form-group form-element-text';
        label.className = 'control-label';
        label.textContent = labelName;

        element.className = typeElement == 'select' ?'ajax-station' : 'form-control timer2';
        element.style.width = '100%';
        element.setAttribute('name', name);
        if(typeElement == 'input'){
            element.setAttribute('data-date-format', 'HH:mm');
        }

        div.appendChild(element);
        form_group.appendChild(label);
        form_group.appendChild(div);

        elements_div.appendChild(form_group);
        col_div.appendChild(elements_div);

        var st = document.getElementById('stations').lastChild;
        st.appendChild(col_div);

        if(btn) {
            var remove_block = document.createElement('div');
            var button = document.createElement('button');
            remove_block.className = 'col-md-1';
            button.className = 'btn btn-danger remove';
            button.setAttribute('type', 'button');
            button.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';
            remove_block.appendChild(button);
            st.appendChild(remove_block);
        }

    }

    $(document).on('click','.remove', function(){
        console.log($(this).parent().parent());
        $(this).parent().parent().remove();
    });


    function AjaxSearch() {
        $('.ajax-station').select2(
            {
                ajax: {
                    url: '/admin/station/search',
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            search: params.term,
                        }

                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            }
        );
    }

//Оформления билета в админке
    $('#search_flight').on('click',function () {
        var stationA = document.getElementById('from').value;
        var stationB = document.getElementById('to').value;
        var date = document.getElementById('date').value;
        var place = document.getElementById('place').value;

        $.ajax({
            url: '/admin/order/search',
            method: 'POST',
            data: {
                from: stationA,
                to: stationB,
                date: date,
                place: place
            },
            success:function (data) {
                $('#result').find("tr:gt(0)").remove();
                $('#result').append(data);
            }
        })
    });


    $(".add-more").click(function(){
        var html = $(".copy-fields").html();
        var sum = $('#child-1').find('option:selected').data('price');
        var curr = $('input[name=suma]').val();

        $('input[name=suma]').val(parseFloat(sum) + parseFloat(curr));
        $('input[name=suma2]').val(parseFloat(sum) + parseFloat(curr));

        $('input[name=start]').val($('#child-1 option:selected').data('start'));
        $('input[name=end]').val($('#child-1 option:selected').data('end'));
        $('input[name=price]').val($('#child-1 option:selected').data('price'));

        $(".after-add-more").after(html);
    });

    $("body").on("click",".remove",function(){
        var sum = $('#child-1').find('option:selected').data('price');
        var curr = $('input[name=suma]').val();
        $('input[name=suma]').val(parseFloat(curr) - parseFloat(sum));
        $('input[name=suma2]').val(parseFloat(curr) - parseFloat(sum));
        $(this).parents(".row").remove();
    });

    $('form').on('submit', function(){
        $('.copy-fields').remove();
    });


    $('#accounting').on('submit',function (e) {
        e.preventDefault();

        $.ajax({
            url: '/admin/stat/get',
            method: 'GET',
            data: {
                from: $(this).find('input[name=start_date]').val(),
                to:  $(this).find('input[name=end_date]').val(),
            },
            success:function (data) {
                $('#searchResult').html(data);
            }
        })
    });

    $('#history').on('submit',function (e) {
        e.preventDefault();
        $('#history_result').html('');
        var flight = $(this).find('select[name=flight_id] option:selected');
        var date = $(this).find('input[name=date]').val();

        $.ajax({
            url: '/admin/history/get',
            method: 'GET',
            data: {
                flight: flight.val(),
                date: date,
            },
            success:function (data) {
                var html = '<a href="/admin/flight/' + data.flight.id + '/'+data.date+'" class="list-group-item">'+flight.text()+
                    '<span class="badge">'+data.count + ' из ' + data.places + '</span></a>';
                $('#history_result').html(html);
            }
        })
    });


    //Раздел->Статистика
    $('#statForm').on('submit',function (e) {
        e.preventDefault();
        var start = $(this).find('input[name=start_date]').val();
        var end = $(this).find('input[name=end_date]').val();
        var cashier = $(this).find('select[name=cashier]').val();

        $.ajax({
            url: '/admin/getStat',
            method: 'GET',
            data: {
                date_start: start,
                date_end: end,
                cashier:cashier
            },

            success:function(result){
                $('#res').html("<strong>Продано: "+ result.count + " билетов, на сумму: " +result.suma + "</strong>");
            }
        });
    });

    /*
    $('#station_form').on('submit',function (e) {
        e.preventDefault();
        var date = $(this).find('input[name=date]').val();
        $.ajax({
            url: '/admin/getStationsTable',
            method: 'GET',
            data: {
                date: date
            },

            success:function(result){
                $('#table1').find('tr[class=item]').remove();
                $('#table1 tr:first').append(result.petropavlovsk);
                console.log(result.petropavlovsk);
            }
        });
    });*/

    //Dashboart Проданные билеты на сегодняшние рейсы
    $('#get_flight').submit(function (e) {
        e.preventDefault();
        var date = $('input[name=date]').val();

        $.ajax({
            type: 'GET',
            url: 'admin/getFlightDate',
            data: {'date' : date},
            success:function(data){
                var table = $('#main_flight tbody').empty();
                table.html(data);
            }
        });
    });

    $(document).on( "click", '.add_button',function(e) {
        var title = $(this).data('title');
        var departure = $(this).data('departure');
        var arrival = $(this).data('arrival');
        var id = $(this).data('id');
        var date = $(this).data('date');

        $("#flight_title").val(title + ' (отпр.' + departure + ' )');
        $("#flight_id").val(id);
        $("#start_date").attr('data-date-start-date', date);

    });

    $('#dop_flight').submit(function (e) {
        e.preventDefault();
        var formData = {
            bus_id     : $('select[name=bus_id]').val(),
            flight_id  : $('input[name=flight_id]').val(),
            driver_id  : $('select[name=driver_id]').val(),
            start_date : $('input[name=start_date]').val(),
            time       : $('input[name=time]').val(),
            _token      : $('input[name=_token]').val()
        };

        $.ajax({
            type: 'POST',
            url: 'admin/dopFlight',
            data: formData,
            success:function(data){
                if(data.status == 'success'){
                    $('#alert').removeClass('hidden');
                    $('#alert a').after("Дополнительный рейс успешно добавлен");
                }
                $('select[name=bus_id]').val(0),
                    $('#myModal').modal('hide');
                location.reload();
            }
        });
    });

    $(document).on( "click", '.edit_button',function(e) {
        var title = $(this).data('title');
        var departure = $(this).data('departure');
        var bus = $(this).data('bus');
        var driver = $(this).data('driver');
        var id = $(this).data('id');
        var date = $(this).data('date');
        var time = $(this).data('time');

        $("#e_flight_title").val(title + ' (отпр.' + departure + ' )');
        $("#e_flight_id").val(id);
        $("#e_start_date").val(date);
        $("#e_bus_id").val(bus).attr('selected', 'true');
        $("#e_driver_id").val(driver).attr('selected', 'true');
        $("#e_time").val(time);

        console.log(title);
    });

    $('#dop_flight_edit').submit(function (e) {
        e.preventDefault();

        var formData = {
            bus_id     : $('#e_bus_id').val(),
            flight_id  : $('#e_flight_id').val(),
            driver_id  : $('#e_driver_id').val(),
            start_date : $('#e_start_date').val(),
            time       : $('#e_time').val(),
            _token      : $('input[name=_token]').val()
        };

        console.log(formData);

        $.ajax({
            type: 'POST',
            url: 'admin/dopFlight/edit',
            data: formData,
            success:function(data){
                console.log(data);
            }
        });
    });


    $('#datetimepicker1').datetimepicker({
        format: 'DD.MM.YYYY'
    });

    $('#datetimepicker2').datetimepicker({
        format: 'DD.MM.YYYY'
    });

    $('#datetimepicker3').datetimepicker({
        format: 'DD.MM.YYYY'
    });
    


});


