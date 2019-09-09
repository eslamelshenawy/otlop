@extends('frontend.layouts.master')

@section('title',trans('web.Login'))

@section('content')
    @if(!\Auth::check())
    @push('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
{{--        <script>--}}
{{--            function validateEmail(email) {--}}
{{--                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;--}}
{{--                return re.test(email);--}}
{{--            }--}}
{{--            function validate() {--}}
{{--                var $result = $("#result");--}}
{{--                var email = $("#email").val();--}}
{{--                $result.text("");--}}

{{--                if (validateEmail(email)) {--}}
{{--                    return true;--}}
{{--                } else {--}}
{{--                    $result.text(email + '{!! trans('web.is not valid') !!}');--}}
{{--                    $result.css("color", "red");--}}
{{--                }--}}
{{--                return false;--}}
{{--            }--}}
{{--            $("#btnLogin").on("click", validate);--}}


{{--        </script>--}}
        @endpush
    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Login')</li>
            </ol>
        </div>
    </div>

    @include('frontend.component.message')

    @if(session()->has('warning'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('warning') }}
        </div>
    @endif

    <!-- Modal Login -->
    <div class="sign-popup-inner login_page">
        <div class="sign-popup-title text-center">
            <h4 itemprop="headline">@lang('web.Login')</h4>
            <span>@lang('web.with your social network')</span>
        </div>
        <div class="popup-social text-center">
            <a class="facebook brd-rd3" href="{{ url('/auth/facebook') }}" title="Facebook" ><i
                        class="fa fa-facebook"></i>
                @lang('web.Facebook')</a>
            <a class="google brd-rd3" href="#" title="Google Plus"><i
                        class="fa fa-google-plus"></i>
                @lang('web.Google')</a>
        </div>
        <span class="popup-seprator text-center"><i class="brd-rd50">@lang('web.or')</i></span>
        {!! Form::open(['url'=>route('web.login.users'),'id'=>'formLock','method'=>'post','class'=>'sign-form']) !!}
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <input id="email" class="brd-rd3" name="email" value="{!! old('email') !!}"
                           type="text" placeholder="@lang('web.Email')">
                    @if ($errors->has('email'))
                        <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <i class="fa fa-eye show_pass"></i>
                    <input class="brd-rd3 pass_in" type="password" name="password" placeholder="@lang('web.Password')">
                    @if ($errors->has('password'))
                        <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <button class="red-bg brd-rd3" id="btnLogin" type="submit">@lang('web.SIGN IN')</button>
                </div>
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <a class="sign-btn" href="{!! route('web.get.register') !!}" title="" itemprop="url">@lang('web.Not a member...? Sign up')</a>
                    <a class="recover-btn" href="{!! route('web.get.reset.password') !!}" title="" itemprop="url">@lang('web.Recover my password')</a>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
        @else


    @endif

@endsection