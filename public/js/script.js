
$('#st1').select2({
    placeholder: "Откуда"
});
$('#st2').select2({
    placeholder: "Куда"
});

$('#datetimepicker').datetimepicker({
    locale: 'ru',
    format: 'YYYY-MM-DD',
    minDate:new Date()
}).on('dp.change',function(ev){
    $('#date').val();
});


function submitContactForm(){
    var reg = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    var name = $('#inputName').val();
    var email = $('#inputEmail').val();
    var message = $('#inputMessage').val();
    var token = $('#token').val();
    var recaptcha = $('#g-recaptcha-response').val();

    if(name.trim() == '' ){
        alert('Please enter your name.');
        $('#inputName').focus();
        return false;
    }else if(email.trim() == '' ){
        alert('Please enter your email.');
        $('#inputEmail').focus();
        return false;
    }else if(email.trim() != '' && !reg.test(email)){
        alert('Please enter valid email.');
        $('#inputEmail').focus();
        return false;
    }else if(message.trim() == '' ){
        alert('Please enter your message.');
        $('#inputMessage').focus();
        return false;
    }else{
        $.ajax({
            method:'POST',
            url:'/feedback',
            data:{
               'name':name, 'email':email, 'message':message, '_token':token,'g-recaptcha-response':recaptcha
            },

            beforeSend: function () {
                $('.submitBtn').attr("disabled","disabled");
                $('.modal-body').css('opacity', '.5');
            },
            success:function(msg){
                if(msg == 'ok'){
                    $('#inputName').val('');
                    $('#inputEmail').val('');
                    $('#inputMessage').val('');
                    $('.statusMsg').html('<span style="color:green;">Спасибо, что обратились к нам. В ближайшее время мы Вам ответим.</p>');
                }else{
                    $('.statusMsg').html('<span style="color:red;">Возникла ошибка, повторите попытку.</span>');
                }
                $('.submitBtn').removeAttr("disabled");
                $('.modal-body').css('opacity', '');
            }
        });
    }
}

$(document).on("click", ".order", function () {
    var name = $(this).data('name');
    $('#busName').text(name);
});


$('#sendRequest').submit(function(e){
    e.preventDefault();
    var name = $(this).find('input[name=name]').val();
    var phone = $(this).find('input[name=phone]').val();
    var bus = $('#busName').text();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.statusMsg').html('');

    $.ajax({
        method: 'POST',
        url: '/orderBus',
        data: {
            'name': name, 'phone': phone, 'bus': bus
        },
        success:function(msg){
            if(msg == 'ok'){
                $('#name').val('');
                $('#phone').val('');
                $('.statusMsg').html('<span style="color:green; font-weight: bold;">Спасибо, что обратились к нам. В ближайшее время мы Вам ответим.</p>');
                ('#sendRequest').hide();
            }else{
                $('.statusMsg').html('<span style="color:red;">Возникла ошибка, повторите попытку.</span>');
            }

        }
    });
});

