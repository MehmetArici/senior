<!DOCTYPE html><html lang="en"><head>    <meta charset="utf-8">    <meta name="csrf-token" content="{{ csrf_token() }}">    <title>RBPSponge</title>    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">    <link rel="stylesheet" href="{{ asset('css/style.css') }}">    <link rel="stylesheet" href="{{ asset('css/app.css') }}">    @yield('css')</head><body><div class="preloader-wrapper">    <div class="preloader">        <img src="{{ asset("img/preloader.gif") }}" alt="NILA">    </div></div><div class="all">    <nav class="navbar navbar-default" role="navigation">        <div class="container-fluid">            <!-- Brand and toggle get grouped for better mobile display -->            <div class="navbar-header">                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">                    <span class="sr-only">Toggle navigation</span>                    <span class="icon-bar"></span>                    <span class="icon-bar"></span>                    <span class="icon-bar"></span>                </button>                <a class="navbar-brand" href="{{ route('home') }}">                    <img src="{{ asset('img/logo/logo-rotate.png') }}" alt="">                </a>            </div>            <!-- Collect the nav links, forms, and other content for toggling -->            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">                <ul class="nav navbar-nav">                    <li class="pull-left fs-20 {{ Request::is("/") ? 'active-button' : "" }}"><a href="{{ route('home') }}">Home</a></li>                    <li class=" fs-20 {{ Request::is("analysis") ? 'active-button' : "" }}"><a href="{{ route('analysis') }}">Perform Analysis</a></li>                    <li class="fs-20"><a href="#" data-toggle="modal" data-target="#retrieve">Retrive Analysis</a></li>                    <li class="fs-20 {{ Request::is("result") ? 'active-button' : "" }}"><a href="{{ route('result') }}">Precomputed Result</a></li>                    <li class="fs-20"><a href="#">Documentation</a></li>                </ul>            </div><!-- /.navbar-collapse -->        </div><!-- /.container-fluid -->    </nav>    <section class="content" id="content">        <div class="container">            @if ($errors->any())                <div class="alert alert-danger validation-danger">                    <ul>                        @foreach ($errors->all() as $error)                            <li>{{ $error }}</li>                        @endforeach                    </ul>                </div>            @endif            @yield('content')        </div>    </section></div><!-- Modal --><form method="post" action="{{ route("send_job") }}" class="col-md-12 form-inline" enctype="multipart/form-data">    {{ csrf_field() }}    <div class="modal fade" id="retrieve" tabindex="-1" role="dialog" aria-labelledby="retrieveTitle"         aria-hidden="true">        <div class="modal-dialog modal-dialog-centered" role="document">            <div class="modal-content">                <div class="modal-header padding-20-30">                    <h3 class="modal-title" id="exampleModalLongTitle">Retrive Your Precomputed Analysis</h3>                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">                        <span aria-hidden="true">&times;</span>                    </button>                </div>                <div class="modal-body">                    <div class="form-group">                        <label for="email">Please type your email address to find your result</label>                        <input id="email" class="form-control" type="email" name="retrieve_email">                    </div>                </div>                <div class="modal-footer">                    <button type="submit" class="btn btn-primary pull-left">See Result</button>                </div>            </div>        </div>    </div></form><script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script><script type="text/javascript" src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script><script type="text/javascript" src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script><script>    $(document).ready(function ($) {        var Body = $('body');        Body.addClass('preloader-site');    });    $(window).on('load', function () {        $('.preloader-wrapper').fadeOut();        $('body').removeClass('preloader-site');    });</script><script type="text/javascript" src="{{ asset('js/script.js') }}"></script>@yield('js')</body></html>