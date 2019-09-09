@extends('frontend.layouts.master')

@section('title',trans('web.Register'))

@section('content')

    @push('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
            function testInput(event) {
                var value = String.fromCharCode(event.which);
                var pattern = new RegExp(/[a-zåäö ]/i);
                return pattern.test(value);
            }
            $('#firstName').bind('keypress', testInput);
            $('#lastName').bind('keypress', testInput);
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
            $("#btnRegister").on("click", validate);
        </script>

    @endpush
    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Register')</li>
            </ol>
        </div>
    </div>

{{--    @include('frontend.component.message')--}}

    <!-- Modal Login -->
    <!-- Modal Register -->
    <div class="modal_register_users register_u">
        <div class="container">
            <div class="sign-popup-inner brd-rd5">
                <div class="sign-popup-title text-center">
                    <h4 itemprop="headline">@lang('web.SIGN UP')</h4>
                </div>
                <div class="popup-social text-center">
                    <a class="facebook brd-rd3" href="{{ url('/auth/facebook') }}" title="Facebook" ><i
                                class="fa fa-facebook"></i>
                        @lang('web.Facebook')</a>
                    <a class="google brd-rd3" href="#" title="Google Plus" ><i
                                class="fa fa-google-plus"></i>
                        @lang('web.Google')</a>
                </div>
                <span class="popup-seprator text-center"><i class="brd-rd50">@lang('web.or')</i></span>
                {!! Form::open(['url'=>route('web.register.users'),'id'=>'formLock','method'=>'post','files'=>true,'class'=>'sign-form']) !!}
                <div class="row">

                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <input id="firstName" class="brd-rd3"  name="firstName" value="{!! old('firstName') !!}" type="text" placeholder="@lang('web.First Name')">
                        @if ($errors->has('firstName'))
                            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('firstName') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <input id="lastName" class="brd-rd3"   name="lastName" value="{!! old('lastName') !!}" type="text" placeholder="@lang('web.Last Name')">
                        @if ($errors->has('lastName'))
                            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('lastName') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <select class="form-control" name="city_id">
                            <option >@lang('web.Select city name')</option>
                            @foreach($city as $key => $value)
                                <option value="{!! $value->id !!} "  {!! $value->id == old('city_id') ? 'selected' : '' !!} >{!! $value->translate(App::getLocale())->name !!}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('city_id'))
                            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('city_id') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <input id="email" class="brd-rd3"  type="email" value="{!! old('email') !!}" name="email" placeholder="@lang('web.Email')">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <input id="lastName" class="brd-rd3"
                               name="phone" value="{!! old('phone') !!}"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                this.value = this.value.replace(/(\..*)\./g, '$1');"
                               type="text" placeholder="@lang('web.Phone')">
                        @if ($errors->has('phone'))
                            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('phone') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-md-12 col-sm-12 col-lg-6 file_upload_style"  data-text="@lang('web.Avatar')">
                        <input class="brd-rd3 file_input" name="image" type="file">
                        @if ($errors->has('image'))
                            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('image') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-6">
                        <i class="fa fa-eye show_pass"></i>
                        <input class="brd-rd3 pass_in" type="password" name="password"  placeholder="@lang('web.Password')">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-6">
                        <i class="fa fa-eye show_pass"></i>
                        <input class="brd-rd3 pass_in" type="password" name="password_confirmation"  placeholder="@lang('web.Confirm Password')">
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <button class="red-bg brd-rd3" id="btnRegister" type="submit">@lang('web.REGISTER NOW')</button>
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <a class="sign-btn" href="{!! route('web.get.login') !!}" title="" itemprop="url">@lang('web.Already Registered...? Sign in')</a>
                        <a class="recover-btn" href="{!! route('web.get.reset.password') !!}" title="" itemprop="url">@lang('web.Recover my password')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection