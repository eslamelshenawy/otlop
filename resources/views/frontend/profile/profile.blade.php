@extends('frontend.layouts.master')

@section('title',trans('web.Home'))

@section('content')

    @push('js')
        <script>

            $(".image").change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.image-preview').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(this.files[0]);
                }
            });
        </script>
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
            $("#Update").on("click", validate);
        </script>

    @endpush
    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Dashboard')</li>
            </ol>
        </div>
    </div>

    @include('frontend.component.message')
    <!-- START Dashboard -->
    <section class="page_dashboard">
        <div class="block less-spacing gray-bg top-padd30">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="sec-box">
                            <div class="dashboard-tabs-wrapper">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-lg-4">
                                        <div class="profile-sidebar brd-rd5 wow fadeIn" data-wow-delay="0.2s">
                                            <div class="profile-sidebar-inner brd-rd5">
                                                <div class="user-info red-bg">
                                                    <h3>@lang('web.My Account')</h3>
                                                </div>
                                                <ul class="nav nav-tabs">

                                                    <li class="active">
                                                        <a href="#account-settings" data-toggle="tab">
                                                            <i class="fa fa-user"></i>
                                                            @lang('web.Account information')
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#my-orders" data-toggle="tab">
                                                            <i class="fa fa-shopping-basket"></i>
                                                            @lang('web.My Orders')
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#wallet" data-toggle="tab">
                                                            <i class="fa fa-google-wallet"></i>
                                                            @lang('web.Wallet')
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="#dateWallet" data-toggle="tab">
                                                            <i class="fa fa-google-wallet"></i>
                                                            @lang('web.Charging Wallet')
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-lg-8">
                                        <div class="tab-content">

                                            <div class="tab-pane fade active in" id="account-settings">
                                                <div class="tabs-wrp account-settings brd-rd5">
                                                    <div class="account-settings-inner">
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                <div class="profile-info-form-wrap">
                                                                        {!! Form::open(['url'=>route('web.post.users.profile'),'id'=>'formLock','method'=>'post','class'=>'profile-info-form','files'=>true]) !!}
                                                                        <div class="row mrg20">
                                                                            <div class="col-md-6 col-sm-12 col-lg-6">
                                                                                <label>@lang('web.First Name')
                                                                                    <sup>*</sup></label>
                                                                                <input class="brd-rd3" type="text" name="firstName"
                                                                                       value="{!! $data->firstName !!}"
                                                                                       placeholder="@lang('web.First Name')">
                                                                            </div>
                                                                            <div class="col-md-6 col-sm-12 col-lg-6">
                                                                                <label>@lang('web.Last Name')
                                                                                    <sup>*</sup></label>
                                                                                <input class="brd-rd3" type="text" name="lastName"
                                                                                       value="{!! $data->lastName !!}"
                                                                                       placeholder="@lang('web.Last Name')">
                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                <label>@lang('web.Email')
                                                                                    <sup>*</sup></label>
                                                                                <input id="email" class="brd-rd3" type="email" name="email"
                                                                                       value="{!! $data->email !!}"
                                                                                       placeholder="@lang('web.Email')">
                                                                                <span id="result"></span>
                                                                            </div>

                                                                            <div class="col-md-6 col-sm-12 col-lg-6">
                                                                                <label>@lang('web.City name')
                                                                                    <sup>*</sup></label>
                                                                                <select class="form-control" name="city_id">
                                                                                    <option >@lang('web.Select city name')</option>
                                                                                    @foreach($city as $key => $value)
                                                                                        <option value="{!! $value->id !!}" {!! $value->id == $data->city_id ? 'selected' : '' !!}>{!! $value->translate(App::getLocale())->name !!}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-6 col-sm-12 col-lg-6">
                                                                                <label>@lang('web.Address')
                                                                                    <sup>*</sup></label>
                                                                                <input class="brd-rd3" type="text" name="address"
                                                                                       value="{!! $data->address !!}"
                                                                                       placeholder="@lang('web.Address')">
                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                <label>@lang('web.Phone')
                                                                                    <sup>*</sup></label>
                                                                                <input class="brd-rd3" type="text"
                                                                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                                                                       this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                                                       value="{!! $data->phone !!}"
                                                                                       name="phone"
                                                                                       placeholder="@lang('web.Phone')">
                                                                            </div>

                                                                            <div class="col-md-12 col-sm-12 col-lg-12"  data-text="Select your file!">
                                                                                @if($data->image)
                                                                                    <img src="{{$data->imagePath}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">

                                                                                @else
                                                                                    <img src="{{asset('upload/images/default.png')}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">


                                                                                @endif
                                                                                <input type="file" class="form-control image" name="image" >

                                                                            </div>
                                                                            <div class="col-md-12 col-sm-12 col-lg-12">
                                                                                <button id="Update"
                                                                                        class="btn btn_style1">@lang('web.Update')</button>
                                                                            </div>
                                                                        </div>
                                                                  {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="my-orders">
                                                <div class="tabs-wrp brd-rd5">
                                                    <h4 itemprop="headline">@lang('web.MY ORDERS')</h4>

                                                    <div class="order-list">

                                                        @foreach($orders as $key => $value)
                                                        <div class="order-item brd-rd5">

                                                            <div class="order-info">
                                                                <h4 itemprop="headline"><a href="#" title="" itemprop="url">@lang('web.OrderID#') {!! $value->id !!}</a>
                                                                </h4>

                                                                <span class="price">{!! $value->amount + $value->tax + $value->amount_delivery !!} @lang('S.R')</span>
                                                                @if($value->status == 'pending')
                                                                    <span class="processing brd-rd3">@lang('web.pending')</span>

                                                                @elseif($value->status == 'complete')
                                                                    <span class="completed  brd-rd3">@lang('web.complete')</span>

                                                                @elseif($value->status == 'cancel')
                                                                    <span class="completed  brd-rd3">@lang('web.cancel')</span>

                                                                @elseif($value->status == 'delivery')
                                                                    <span class="completed  brd-rd3">@lang('web.delivery')</span>

                                                                @endif
                                                                <a class="brd-rd2" type="button" data-toggle="modal"
                                                                   data-target=".order_details_modal_{!! $value->id !!}">@lang('web.Order Detail')</a>

                                                                <!-- Modal Orders Cards -->
                                                                <div class="modal fade sec_modal order_details_modal_{!! $value->id !!}" tabindex="-1" role="dialog"
                                                                     aria-labelledby="myLargeModalLabel">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">@lang('web.Order Details')</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">

                                                                                <div class="statement-table">
                                                                                    <table>
                                                                                        <thead>
                                                                                        <tr>
                                                                                            <th>@lang('web.ID')</th>
                                                                                            <th>@lang('web.Image')</th>
                                                                                            <th>@lang('web.Name')</th>
                                                                                            <th>@lang('web.Date')</th>
                                                                                            <th>@lang('web.Price')</th>
                                                                                            <th>@lang('web.Quantity')</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        @foreach($value->orderDetails()->get() as $key =>$item)
                                                                                        <tr>
                                                                                            <td>#{!! $item->id !!}</td>
                                                                                            <td><img src="{!! \App\MenuDetails::find($item->menu_details_id)->imagePath !!}" alt=""></td>
                                                                                            <td>{!! \App\MenuDetails::find($item->menu_details_id)->translate(App::getLocale())->name !!}</td>
                                                                                            <td>{!! date('d-m-Y',strtotime($item->created_at)) !!}</td>
                                                                                            <td><span class="red-clr">{!! percentageOrder($item->price) !!}</span></td>
                                                                                            <td>{!! $item->qty !!}</td>
                                                                                        </tr>
                                                                                       @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div><!-- Statement Table -->

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- //== Modal Orders Cards -->

                                                            </div>



                                                        </div>
                                                         @endforeach

                                                    </div>
                                                </div>
                                            </div>


                                            <div class="tab-pane fade" id="wallet">
                                                <div class="tabs-wrp brd-rd5">
                                                    <h4 itemprop="headline">@lang('web.Wallet')</h4>

                                                    <div class="statement-table">
                                                        <table>
                                                            <thead>
                                                            <tr>
                                                                <th>@lang('web.Date')</th>
                                                               {{-- <th>@lang('web.Order ID#')</th>--}}
                                                                <th>@lang('web.Previous Balance')</th>
                                                                <th>@lang('web.Current Balance')</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach(\App\UserWalletDetails::where('user_id',Auth::guard('web')->user()->id)->get() as $key =>$value)
                                                            <tr>
                                                                <td>{!! date('d-m-Y',strtotime($value->date)) !!}</td>
                                                                {{--<td>@lang('web.OrderID#'){!! $value->order_id !!}</td>--}}
                                                                <td>{!! $value->balance !!}</td>
                                                                <td><span class="red-clr">{!! $value->previous !!}</span></td>
                                                            </tr>
                                                           @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div><!-- Statement Table -->

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="dateWallet">
                                                <div class="tabs-wrp brd-rd5">
                                                    <h4 itemprop="headline">@lang('web.Wallet')</h4>

                                                    <div class="statement-table">
                                                        <table>
                                                            <thead>
                                                            <tr>
                                                                <th>@lang('web.Date')</th>
                                                                <th>@lang('web.Package Name')</th>
                                                                <th>@lang('web.Money')</th>
                                                                <th>@lang('web.Point')</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($chargingWallet as $key => $value)
                                                            <tr>
                                                                <td>{!! date('d-m-Y',strtotime($value->created_at)) !!}</td>
                                                                <td>{!! \App\Package::find($value->package_id)->translate(App::getLocale())->name !!}</td>
                                                                <td>{!! \App\Package::find($value->package_id)->price !!} @lang('web.S.R')</td>
                                                                <td><span class="red-clr">{!! \App\Package::find($value->package_id)->point !!}</span></td>
                                                            </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div><!-- Statement Table -->

                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Section Box -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //==END Dashboard -->


@endsection