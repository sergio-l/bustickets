<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <!-- Font Awesome and Pixeden Icon Stroke icon fonts-->
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pe-icon-7-stroke.css')}}">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="{{asset('css/style.blue.css')}}" id="theme-stylesheet">
    <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.css')}}">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

    @if($settings->site_work == 'disabled')
    <style>
        .jumbotron.main-jumbotron::after {
            content: "ВНИМАНИЕ! САЙТ НАХОДИТСЯ В СТАДИИ ТЕСТИРОВАНИЯ, ЗАКАЗЫ НЕ ПРИНИМАЮТСЯ!";
            display: block;
            color: red;
            font-size: 40px;
            font-weight: bold;
            letter-spacing: 2px;
            background: #fff;
            max-width: 900px;
            margin: 35px auto -140px;
            border-radius: 5px;
        }
    </style>
    @endif
</head>
<body>
<!-- navbar-->
<header class="header">
    <div role="navigation" class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a href="/" class="navbar-brand scroll-to">

                    <span class="logo-wrapper">
                        <span>ООО «Городской автопарк» </span>
                    </span>
                    <!-- <img src="{{asset('images/logo.png')}}" alt="logo" class="hidden-xs hidden-sm">
                    <span class="sr-only">Go to homepage</span> -->
                </a>
                <div class="navbar-buttons">
                    <button type="button" data-toggle="collapse" data-target=".navbar-collapse" class="navbar-toggle navbar-btn">Menu<i class="fa fa-align-justify"></i></button>
                </div>
            </div>
            <div id="navigation" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{url('/')}}"><i style="font-size: 20px;" class="fa fa-home" aria-hidden="true"></i></a></li>
                    <li><a href="{{url('schedule')}}"><i class="fa fa-calendar" aria-hidden="true"></i>Расписание</a></li>
                    <li><a href="{{url('about')}}"><i class="fa fa-info" aria-hidden="true"></i>О нас</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="{{url('service')}}"><i class="fa fa-bus" aria-hidden="true"></i>Услуги</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{url('service/order_bus')}}">Заказать автобус</a></li>
                            <li><a href="{{url('service/order_minibus')}}">Заказать микроавтобус</a></li>
                            <li><a href="{{url('service/order_organization')}}">Заказать вахтовый автобус</a></li>
                        </ul>
                    </li>
                    <li><a href="{{url('avtopark')}}"><i class="fa fa-bus" aria-hidden="true"></i>Автопарк</a></li>
                    <li><a href="{{url('rules')}}"><i class="fa fa-book" aria-hidden="true"></i>Правила оплаты</a></li>
                    <li><a href="{{url('insurance')}}"><i class="fa fa-heart" aria-hidden="true"></i>Страховка</a></li>
                    <li class="dropdown"><a href="{{url('/contacts')}}"><i class="fa fa-send" aria-hidden="true"></i>Контакты</a>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

@widget('search')
@yield('content')

<footer class="footer">

    <div class="footer__copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <p>&copy; {{date('Y')}} Онлайн сервис продажи билетов.</p>
                </div>
                <div class="col-md-4">
                    <p><a href="{{url('/personal')}}">Обработка персональных данных</a></p>
                    <p><a href="{{url('/public_offer')}}">Договор публичной оферты</a></p>
                </div>
                <div class="col-md-4">
                   <img src="{{url('/images/Visa.png')}}" width="80">
                   <img src="{{url('/images/MasterCard.png')}}" width="80">
                   <img src="{{url('/images/PayKeeper.png')}}" width="80">
                </div>
            </div>
        </div>
    </div>
</footer>


<!-- Javascript files-->
<script src="{{asset(('js/jquery-3.2.1.js'))}}"></script>
<script src="{{asset(('js/bootstrap.min.js'))}}"></script>
<script src="{{asset(('js/select2.min.js'))}}"></script>
<script src="{{asset(('js/moment.js'))}}"></script>
<script src="{{asset(('js/bootstrap-datetimepicker.js'))}}"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="{{asset(('js/script.js'))}}"></script>
@yield('scripts')
</body>
</html>