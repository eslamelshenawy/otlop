
@extends('frontend.layouts.master')

@section('title',trans('web.Contact Us'))

@section('content')
    @push('js')

        <script type="text/javascript">
            $(document).ready(function () {
                @if(old('city_id'))
                $.ajax({
                    url:'{!! route('web.contact.us') !!}',
                    type:'get',
                    dataType:'html',
                    data:{city_id:'{!! old('city_id') !!}',select:'{!! old('state_id') !!}'},
                    success: function (data) {
                        $('.showData').removeClass('hidden');
                        $('.state').html(data);
                        $('.DataState').addClass('hidden');
                    }
                });
                @endif
                $(document).on('change','.city_id',function () {
                    var city = $('.city_id option:selected').val();

                    if (city > 0){
                        $.ajax({
                            url:'{!! route('web.contact.us') !!}',
                            type:'get',
                            dataType:'html',
                            data:{city_id:city,select:''},
                            success: function (data) {
                                $('.showData').removeClass('hidden');
                                $('.state').html(data);
                                $('.DataState').addClass('hidden');

                            }
                        });
                    }
                    else{
                        $('.DataState').removeClass('hidden');
                        $('.showData').addClass('hidden');

                    }
                })
            })
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#selectUserType').val("owner").attr('selected', 'selected');
                $('.radioDelivery').addClass('hidden');
                @if(old('type'))
                $('#selectUserType').val("{!! old('type') !!}").attr('selected', 'selected');
                @endif
                $('#selectUserType').change(function (e) {
                    //console.log(e.target.value);
                    var selectOption = e.target.value;
                    if (selectOption === 'owner')
                    {
                        $('.typeRestaurant').removeClass('hidden');
                        $('.restaurantName').removeClass('hidden');
                        $('.radioRestaurantName').removeClass('hidden');
                        $('.radioDelivery').addClass('hidden');
                    }
                    else
                    {
                        $('.typeRestaurant').addClass('hidden');
                        $('.restaurantName').addClass('hidden');
                        $('.radioRestaurantName').addClass('hidden');
                        $('.radioDelivery').removeClass('hidden');

                    }

                });
            });

            $(document).ready(function () {
                @if(session('type') == 'owner')
                $('.typeRestaurant').removeClass('hidden');
                $('.restaurantName').removeClass('hidden');
                $('.radioRestaurantName').removeClass('hidden');
                $('.radioDelivery').addClass('hidden');
                @endif
            });
            $(document).ready(function () {
                @if(session('type') == 'delivery')
                $('.typeRestaurant').addClass('hidden');
                $('.restaurantName').addClass('hidden');
                $('.radioRestaurantName').addClass('hidden');
                $('.radioDelivery').removeClass('hidden');
                @endif
            });

        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
            function testInput(event) {
                var value = String.fromCharCode(event.which);
                var pattern = new RegExp(/[a-zåäö ]/i);
                return pattern.test(value);
            }
            $('#name').bind('keypress', testInput);
            $('#firstName').bind('keypress', testInput);
            $('#lastName').bind('keypress', testInput);
            $('#res_name').bind('keypress', testInput);
            function validateEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
            function validate() {
                var $result = $("#result");
                var $result2 = $("#result2");
                var email = $("#email").val();
                var email2 = $("#email2").val();
                $result.text("");
                $result2.text("");
                if (!validateEmail(email)) {
                    $result.text(email + '{!! trans('web.is not valid') !!}');
                    $result.css("color", "red");
                }
                else
                {
                    return true;
                }
                if (!validateEmail(email2)) {
                    $result2.text(email2 + '{!! trans('web.is not valid') !!}');
                    $result2.css("color", "red");
                }
                else
                {
                    return true;
                }
                return false;
            }
            $("#btnJoin").on("click", validate);
            $("#btnMessage").on("click", validate);
        </script>
    @endpush
    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Contact Us')</li>
            </ol>
        </div>
    </div>
    @include('frontend.component.message')

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    <section class="sec_contact">
        <div class="container">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="{!! session('profile') == 'active' ? '' : 'active' !!}"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">@lang('web.Contact us')</a></li>
                <li role="presentation" class="{!! session('profile') == 'active' ? 'active' : '' !!}"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">@lang('web.Join us')</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane {!! session('profile') == 'active' ? '' : 'active' !!}" id="home">
                    <section class="page_contact">
                        <div class="contact_content">
                            <h2 class="">@lang('web.Leave Your Message For Help')</h2>
                            {!! Form::open(['url'=>route('web.post.contact.us'),'method'=>'post']) !!}
                            <div class="form-group">
                                {!! Form::select('type',['vendor'=>trans('web.Vendor')
                                ,'customer'=>trans('web.User'),'delivery'=>trans('web.Delivery')
                                ,'others'=>trans('web.Others')],old('type'),['class'=>'form-control','placeholder'=>trans('web.Select user type')]) !!}

                            </div>
                            <div class="form-group">
                                <input type="text" id="name" name="name" value="{!! old('name') !!}" class="form-control" placeholder="@lang('web.Full Name')">
                                @if ($errors->has('name'))
                                    <span class="help-block" style="margin-top:30px;">
                                           <strong>{{ $errors->first('name') }}</strong>
                                       </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="email" id="email" name="email" value="{!! old('email') !!}" class="form-control"
                                       placeholder="@lang('web.Email Address')">
                                <!--<span id="result"></span>-->
                                @if ($errors->has('email'))
                                    <span class="help-block" style="margin-top:30px;">
                                           <strong>{{ $errors->first('email') }}</strong>
                                       </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="text" name="mobile"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                       this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       value="{!! old('mobile') !!}" class="form-control" placeholder="@lang('web.Mobile Number')">

                                @if ($errors->has('mobile'))
                                    <span class="help-block" style="margin-top:30px;">
                                           <strong>{{ $errors->first('mobile') }}</strong>
                                       </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="text" name="subject" value="{!! old('subject') !!}" class="form-control" placeholder="@lang('web.Subject')">
                                @if ($errors->has('subject'))
                                    <span class="help-block" style="margin-top:30px;">
                                           <strong>{{ $errors->first('subject') }}</strong>
                                       </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <textarea name="message"  class="form-control" placeholder="@lang('web.Message')">{!! old('message') !!}</textarea>
                                @if ($errors->has('message'))
                                    <span class="help-block" style="margin-top:30px;">
                                           <strong>{{ $errors->first('message') }}</strong>
                                       </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button id="btnMessage" type="submit"  class="btn btn-primary">@lang('web.Send Message')</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </section>
                </div>
                <div role="tabpanel" class="tab-pane p-5 {!! session('profile') == 'active' ? 'active' : '' !!}" id="profile">
                    <h1>{!! $page->translate(App::getLocale())->title !!}</h1>

                    {!! html_entity_decode( $page->translate(App::getLocale())->description) !!}

                    <hr>

                    {!! Form::open(['url'=>route('web.request.working'),'method'=>'post','files'=>true,'id'=>'formLock']) !!}

                    <div class="form-group selectUserType">
                        <label for="">@lang('web.User type')</label>
                        {!! Form::select('type',[
                                 'owner'=>trans('web.Owner'),
                                 'delivery'=>trans('web.Delivery')
                                 ],
                                 old('type'),
                                 ['id'=>'selectUserType','class'=>'form-control']) !!}

                    </div>

                    <div class="form-group">
                        <input type="text" id="firstName" name="firstName" value="{!! old('firstName') !!}" class="form-control"
                               placeholder="@lang('web.First name')">
                        @if ($errors->has('firstName'))
                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('firstName') }}</strong>
                                       </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="text" id="lastName" name="lastName" value="{!! old('lastName') !!}" class="form-control"
                               placeholder="@lang('web.Last name')">
                        @if ($errors->has('lastName'))
                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('lastName') }}</strong>
                                       </span>
                        @endif
                    </div>
                    <div class="form-group restaurantName">
                        <input type="text" id="res_name" name="res_name" value="{!! old('res_name') !!}"
                               class="form-control" placeholder="@lang('web.Restaurant name')">
                        @if ($errors->has('res_name'))
                            <span class="help-block" style="margin-top:30px;Color:red">
                                           <strong>{{ $errors->first('res_name') }}</strong>
                                       </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">@lang('web.City name')</label>
                        <select class="form-control city_id" name="city_id">
                            <option>@lang('web.Select city name')</option>
                            @foreach($city as $key =>$value)
                                <option value="{!! $value->id !!}" {!! old('city_id') == $value->id ? 'selected':''  !!}>{!! $value->translate(App::getLocale())->name !!}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('city_id'))
                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('city_id') }}</strong>
                                       </span>
                        @endif
                    </div>

                    <div class="form-group DataState">
                        <label for="">@lang('web.State name')</label>
                        <select class="form-control " >
                            <option>@lang('web.Select state name')</option>
                        </select>
                    <!--@if ($errors->has('dataState'))-->
                        <!--              <span class="help-block" style="margin-top:30px;">-->
                    <!--                  <strong>{{ $errors->first('dataState') }}</strong>-->
                        <!--              </span>-->
                        <!--         @endif-->
                    </div>

                    <div class="form-group showData hidden">
                        <label for="">@lang('web.State name')</label>
                        <span class="state">

                            </span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="address" value="{!! old('address') !!}" placeholder="@lang('web.Address')">
                        @if ($errors->has('address'))
                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('address') }}</strong>
                                       </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                   this.value = this.value.replace(/(\..*)\./g, '$1');"
                               name="phone" value="{!! old('phone') !!}" placeholder="@lang('web.Phone number')">
                        @if ($errors->has('phone'))
                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('phone') }}</strong>
                                       </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="email" id="email2" class="form-control" name="email" value="{!! old('email') !!}"
                               placeholder="@lang('web.Email')">
                        @if ($errors->has('email'))
                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('email') }}</strong>
                                       </span>
                    @endif
                    <!--<span id="result2"></span>-->
                    </div>
                    <div class="form-group typeRestaurant">
                        <label for="">@lang('web.Cuisine offered by your restaurant')</label>
                        <select class="form-control" name="type_id">
                            <option>@lang('web.Select type of  restaurant')</option>
                            @foreach($type as $key =>$value)
                                <option value="{!! $value->id !!}" {!! old('type_id') == $value->id ? 'selected':''  !!}>{!! $value->translate(App::getLocale())->name !!}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('type_id'))
                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('type_id') }}</strong>
                                       </span>
                        @endif
                    </div>
                    <div class="form-group ml-5 radioRestaurantName">
                            <span class="radio-box cash-popup-btn">
                                <input type="radio" name="method" {!! old('method') == 'fileOwner'  ? 'checked' : '' !!} value="fileOwner" class="chk_show" id="pay1-14">
                                <label for="pay1-14">
                                    <span>@lang('web.I am the owner \ restaurant manager')</span>
                                </label>
                                <div class="card_show show_div">
                                    <h6>@lang('web.Upload Image Here ...')</h6>
                                    <div class="file_upload_style" data-text="Upload Image *">
                                        <input class="brd-rd3 file_input"   name="upload_file" type="file">
                                         @if ($errors->has('upload_file'))
                                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('upload_file') }}</strong>
                                       </span>
                                        @endif
                                    </div>
                                </div>
                            </span>
                    </div>
                    <div class="form-group ml-5 radioDelivery">
                            <span class="radio-box cash-popup-btn">
                                <input type="radio" name="method" {!! old('method') == 'fileDelivery' ? 'checked' : '' !!}  value="fileDelivery" class="chk_show2" id="pay1-13">
                                <label for="pay1-13">
                                    <span>@lang('web.Do you have a delivery service...?')</span>
                                </label>
                                <div class="card_show show_div2">
                                    <h6>@lang('web.Upload Image Here ...')</h6>
                                    <div class="file_upload_style" data-text="Upload Image *">
                                        <input class="brd-rd3 file_input" name="upload_file" type="file">
                                          @if ($errors->has('upload_file'))
                                            <span class="help-block" style="margin-top:30px; Color:red">
                                           <strong>{{ $errors->first('upload_file') }}</strong>
                                       </span>
                                        @endif
                                    </div>
                                </div>
                            </span>
                    </div>

                    <div class="form-group">
                        <button  id="btnJoin" class="btn btn-primary btn_more" type="submit" >@lang('web.Join Now')</button>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>

        </div>
    </section>

    {{-- <section class="page_contact">
         <div class="container">
             <div class="contact_content">
                 <h2 class="">@lang('web.Leave Your Message For Help')</h2>
                {!! Form::open(['url'=>route('web.post.contact.us'),'method'=>'post']) !!}
                     <div class="form-group">
                         <input type="text" name="name" value="{!! old('name') !!}" class="form-control" placeholder="@lang('web.Full Name')">
                          @if ($errors->has('name'))
                                        <span class="help-block" style="margin-top:30px;">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                   @endif
                     </div>
                     <div class="form-group">
                         <input type="email" name="email" value="{!! old('email') !!}" class="form-control" placeholder="@lang('web.Email Address')">
                          @if ($errors->has('email'))
                                        <span class="help-block" style="margin-top:30px;">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                   @endif
                     </div>
                     <div class="form-group">
                         <input type="text" name="mobile"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                value="{!! old('mobile') !!}" class="form-control" placeholder="@lang('web.Mobile Number')">
                                 @if ($errors->has('mobile'))
                                        <span class="help-block" style="margin-top:30px;">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                   @endif
                     </div>
                     <div class="form-group">
                         <input type="text" name="subject" value="{!! old('subject') !!}" class="form-control" placeholder="@lang('web.Subject')">
                         @if ($errors->has('subject'))
                                        <span class="help-block" style="margin-top:30px;">
                                            <strong>{{ $errors->first('subject') }}</strong>
                                        </span>
                                   @endif
                     </div>
                     <div class="form-group">
                         <textarea name="message"  class="form-control" placeholder="@lang('web.Message')">{!! old('message') !!}</textarea>
                         @if ($errors->has('message'))
                                        <span class="help-block" style="margin-top:30px;">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </span>
                                   @endif
                     </div>

                     <div class="form-group">
                         <button type="submit" class="btn btn-primary">@lang('web.Send Message')</button>
                     </div>
                 {!! Form::close() !!}
             </div>
         </div>
     </section>--}}


@endsection