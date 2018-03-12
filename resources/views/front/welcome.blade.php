@extends('front.template.master')

@section('title')
    Home
@endsection

@section('head_additional')
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <style>
            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links-item > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: bold;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
@endsection

@section('content')
    <div class="flex-center position-ref full-height">

        <div class="content">
            <div class="title m-b-md">
                Laravel
            </div>

            <div class="links-item">
                {!!Form::text('name', null, array('style'=>'width: 100px; height: 40px; border: 1px solid #d2d2d2; text-align: center;', 'placeholder'=>'Text'))!!}
                <a href="https://laravel.com/docs">
                    Documentation
                </a>
                <a href="https://laracasts.com">
                    Laracasts
                </a>
                <a href="https://laravel-news.com">
                    News
                </a>
                <a href="https://forge.laravel.com">
                    Forge
                </a>
                <a href="https://github.com/laravel/laravel">
                    GitHub
                </a>
            </div>
        </div>
    </div>
@endsection