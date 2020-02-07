@extends('layouts.app')

@section('title'){{$page->first()->title}}@endsection

@section('content')
<section>
    <div class="container">
        @if(Request::is('contacts'))
        <div class="col-md-8">
            {!! $page->first()->content !!}
        </div>
        <div class="col-md-4">
            <h3>Обратная связь</h3>
            <p class="statusMsg"></p>
            <form role="form">
                <input type="hidden" name="_token" id="token" value="{{csrf_token()}}">
                <div class="form-group">
                    <label for="inputName">Имя</label>
                    <input type="text" class="form-control" id="inputName" placeholder="Ваше имя"/>
                </div>
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="Ваш email адресс"/>
                </div>
                <div class="form-group">
                    <label for="inputMessage">Сообщения</label>
                    <textarea class="form-control" id="inputMessage" placeholder="Введите текст сообщения"></textarea>
                </div>
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                <div class="form-group"><button type="button" class="btn btn-primary submitBtn" onclick="submitContactForm()">Отправить</button></div>
            </form>
        </div>
        @else
        <div class="col-md-12">
            {!! $page->first()->content !!}
        </div>
        @endif
    </div>
</section>
@endsection