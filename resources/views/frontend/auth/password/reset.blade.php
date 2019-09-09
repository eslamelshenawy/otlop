@extends('frontend.layouts.master')

@section('title',trans('web.Reset Password'))

<div class="bread-crumbs-wrapper">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
            <li class="breadcrumb-item active">@lang('web.Reset Password')</li>
        </ol>
    </div>
</div>

@section('content')

    <!-- Modal Login -->
    <div class="sign-popup-inner login_page">


        {!! Form::open(['url'=>route('web.post.reset',$data->token),'id'=>'formLock','method'=>'post','class'=>'sign-form']) !!}
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <input class="brd-rd3" name="email" value="{{$data->email}}" type="text" placeholder="@lang('web.Email')">
            </div>
            <div class="col-md-12 col-sm-12 col-lg-12">
                <input class="brd-rd3" type="password" name="password" placeholder="@lang('web.Password')">
            </div>
            <div class="col-md-12 col-sm-12 col-lg-12">
                <input class="brd-rd3" type="password" name="password_confirmation" placeholder="@lang('admin.Retype password')">
            </div>
            <div class="col-md-12 col-sm-12 col-lg-12">
                <button id="btnRestPassword" class="red-bg brd-rd3" type="submit">@lang('admin.Reset')</button>
            </div>
            <div class="col-md-12 col-sm-12 col-lg-12">
                <a class="sign-btn" href="{!! route('web.get.register') !!}" title="" itemprop="url">@lang('web.Not a member...? Sign up')</a>
                <a class="recover-btn" href="{!! route('web.get.reset.password') !!}" title="" itemprop="url">@lang('web.Recover my password')</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>


{{--<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>--}}
@endsection
