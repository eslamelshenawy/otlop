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
                    /*  $result.text(email + " is valid :)");
                      $result.css("color", "green");*/
                } else {
                    $result.text(email + '{!! trans('web.is not valid') !!}');
                    $result.css("color", "red");
                }
                return false;
            }
            $("#btnDelivery").on("click", validate);
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


    <!-- Modal Login -->
    <!-- Modal Register -->
    <div class="modal_register_users register_u">
        <div class="container">
            <div class="sign-popup-inner brd-rd5">

                {!! Form::open(['url'=>route('web.register.delivery'),'method'=>'post','files'=>true,'class'=>'sign-form']) !!}
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <input id="firstName" class="brd-rd3" type="text" name="firstName" value="{!! old('firstName') !!}" placeholder="@lang('web.First Name')">
                    </div>
                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <input id="lastName" class="brd-rd3" type="text" value="{!! old('lastName') !!}" name="lastName" placeholder="@lang('web.Last Name')">
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-6">
                        <input id="email" class="brd-rd3" type="email" value="{!! old('email') !!}"
                               name="email" placeholder="@lang('web.Email')">
                        <span id="result"></span>
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-6 file_upload_style" data-text="Select your file!">
                        <input class="brd-rd3 file_input" type="file" name="image">
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-6">
                        <input class="brd-rd3" type="password" name="password" placeholder="@lang('web.Password')">
                    </div>
                    <div class="col-md-12 col-sm-12 col-lg-6">
                        <input class="brd-rd3" type="password" name="password_confirmation" placeholder="@lang('web.Confirm Password')">
                    </div>

                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <input class="brd-rd3" type="text"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                               name="phone" value="{!! old('phone') !!}" placeholder="@lang('web.Phone')">
                    </div>

                    <div class="col-md-6 col-sm-12 col-lg-6">
                        <input class="brd-rd3" type="text" name="address" value="{!! old('address') !!}" placeholder="@lang('web.Address')">
                    </div>



                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <button class="red-bg brd-rd3" id="btnDelivery" type="submit">@lang('web.REGISTER NOW')</button>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection