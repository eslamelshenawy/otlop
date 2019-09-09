@extends('frontend.layouts.master')

@section('title',trans('web.Reset Password'))

@section('content')
    @push('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
            function validateEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
            function validate() {
                var $result = $("#result");
                var email = $("#email").val();
                $result.text("");

                if (validateEmail(email)) {
                    return true;
                } else {
                    $result.text(email + '{!! trans('web.is not valid') !!}');
                    $result.css("color", "red");
                }
                return false;
            }
            $("#btnReset").on("click", validate);
        </script>
    @endpush
    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Reset Password')</li>
            </ol>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="sign-popup-inner login_page">

        {!! Form::open(['url'=>route('web.post.reset.password'),'id'=>'formLock','method'=>'post','class'=>'sign-form']) !!}
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <input id="email" class="brd-rd3" name="email" value="{!! old('email') !!}" type="text" placeholder="@lang('web.Email')">
               {{-- @if($errors->has('email'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('admin.We need to know your e-mail address!')
                    </div>
                @endif--}}
                <span id="result"></span>
            </div>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <button class="red-bg brd-rd3" id="btnReset" type="submit">@lang('web.Send Password Reset Link')</button>
            </div>
            <div class="col-md-12 col-sm-12 col-lg-12">
                <a class="sign-btn" href="{!! route('web.get.login') !!}" title="" itemprop="url">@lang('web.Already Registered...? Sign in')</a>
                <a class="recover-btn" href="" title="{!! route('web.get.register') !!}" itemprop="url">@lang('web.Not a member...? Sign up')</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
