@extends('frontend.layouts.master')

@section('title',trans('web.Restaurants'))

@section('content')


    @push('js')
            <script>

                @if(Auth::guard('web')->user())

                $(document).on('click', '.addCart', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');
                $.ajax({
                    url: '{!! route('web.data') !!}',
                    type: 'post',
                    data: {menuDetailsId: id , name: name},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        if (data.myAlert === "true") {
                            $('.messageAlert').removeClass('hidden');
                        }
                        else
                        {
                            $('.messageAlert').addClass('hidden');
                            $(".cart_restu").css("display", "none");
                            $('.cart_restu1 ').html(data.cart);
                            $(".myCart").css("display", "none");
                            $('.myCart1 ').html(data.myCart);
                            location.reload();

                        }


                        //window.location.href = "";
                    }
                });

            });
            $(document).on('click', '.removeCart', function (e) {
                e.preventDefault();
                var cartId = $(this).data('id');
                var name = $(this).data('name');
                $.ajax({
                    url: '{!! route('web.data') !!}',
                    type: 'post',
                    data: {cartId: cartId , name: name},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        $('.cart_restu').html(data.cart);
                        $('.myCart ').html(data.myCart);
                        location.reload();

                        //window.location.href = "";
                    }
                });
            });

                $(document).ready(function () {
                    $('.minus').click(function () {
                        var $input = $(this).parent().find('input');
                        var count = parseInt($input.val()) - 1;
                        var minus = $(this).data('id');
                        var name = $(this).data('name');

                        count = count < 1 ? 1 : count;
                        $input.val(count);
                        $input.change();

                        $.ajax({
                            url: '{!! route('web.data') !!}',
                            type: 'post',
                            data: {minus: minus , name: name},
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                                $('.cart_restu').html(data.cart);
                                $('.myCart ').html(data.myCart);
                              //  window.location.href = "";
                                location.reload();

                            }
                        });

                    });
                    $('.plus').click(function () {
                        var $input = $(this).parent().find('input');
                        var plus = $(this).data('id');
                        var name = $(this).data('name');
                        $input.val(parseInt($input.val()) + 1);
                        $input.change();


                        $.ajax({
                            url: '{!! route('web.data') !!}',
                            type: 'post',
                            data: {plus: plus , name: name},
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                                $('.cart_restu').html(data.cart);
                                $('.myCart ').html(data.myCart);
                                location.reload();

                                //  window.location.href = "";
                            }
                        });

                    });
                });


          @endif

        </script>

        <script>
            $(document).ready(function () {
                var _token = $('input[name="_token"]').val();
                post_data('',_token);
                function post_data(id = "" , _token) {
                    $.ajax({
                        url:"{!! route('web.load.data') !!}",
                        method:"POST",
                        data:{id:id,_token:_token,resId:'{!! $restaurants->id !!}'},
                        success: function (data) {
                            $('#load_more_button').remove();
                            $('#post_data').append(data);
                        }
                    });
                }
                $(document).on('click' ,'#load_more_button',function () {
                    var id = $(this).data('id');
                    $('#load_more_button').html('<b> @lang('web.Loading...') </b>');
                    post_data(id,_token);
                })
            })
        </script>
    @endpush
    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title=""
                                               itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Restaurant Details')</li>
            </ol>
        </div>
    </div>

    <!-- START Page Details -->

    <div class="alert alert-warning alert-dismissible alert_msg messageAlert hidden">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-warning"></i> @lang('admin.Warning') !</h4>
        {!! trans('web.Please choose from the same restaurant or clear the cart and choose from this restaurant') !!}
    </div>
    <section class="page_details">
        <div class="block gray-bg top-padd30">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="sec-box">
                            <div class="sec-wrapper">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-lg-12">
                                        <div class="restaurant-detail-wrapper">
                                            <div class="restaurant-detail-info">
                                                <div class="restaurant-detail-title">
                                                    <img src="{!! $restaurants->logoPath !!}" alt="">
                                                    <h1 itemprop="headline">{!! $restaurants->translate(App::getLocale())->name !!}</h1>
                                                    <div class="info-meta">
                                                        <span>

                                                        </span>
                                                    </div>
                                                    <div class="rating_d mt-3">
                                                        @if($rating > 0)
                                                        <span class="" title="">
                                                            @for($i =0 ; $i<$rating; $i++)
                                                            <i class="fa fa-star"></i>
                                                            @endfor

                                                            @lang('web.Rating')
                                                            <i>{!! $rating !!}</i></span>
                                                            @else
                                                            <span class="" title="">
                                                                @lang('web.No Rating')
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="restaurant-detail-tabs">
                                                    <ul class="nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#tab1-1" data-toggle="tab">
                                                                <i class="fa fa-cutlery"></i> @lang('web.Menu')
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab1-3" data-toggle="tab">
                                                                <i class="fa fa-star"></i>
                                                                Reviews
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab1-5" data-toggle="tab">
                                                                <i class="fa fa-info"></i>
                                                                Restaurant Info
                                                            </a>
                                                        </li>
                                                    </ul>

                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="tab-content">

                                                                <!-- Menu Tab-->
                                                                <div class="tab-pane fade in active" id="tab1-1">
                                                                    <div class="row menu_tab_t pt-5 pl-2">
                                                                        <div class="col-md-3">
                                                                            <ul class="nav pt-0 m-0 nav_left_accordion">
                                                                                <li><strong>@lang('web.Menu')</strong></li>
                                                                                @foreach($restaurants->menuRestaurant()->get() as $key => $value)
                                                                                    <li>
                                                                                        <a href="#t{!! $key !!}"><i
                                                                                                    class="fa fa-chevron-right"></i>
                                                                                            {!! $value->translate(App::getLocale())->name!!}
                                                                                        </a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <div class="accordion_categories ">
                                                                                {{-- {!! dd($restaurants->menuRestaurant) !!}--}}
                                                                                @foreach($restaurants->menuRestaurant()->get() as $key => $value)

                                                                                    {{-- {!! dd($value->menuDetailsRestaurant) !!}--}}

                                                                                    <div id="t{!! $key !!}"
                                                                                         class="item">
                                                                                        <h3 class="title_cat"> {!! $value->translate(App::getLocale())->name!!}
                                                                                        </h3>

                                                                                        <div class="desc_cat ">
                                                                                            @foreach($value->menuDetailsRestaurant()->get() as $data)
                                                                                            <div class="item_prod">
                                                                                                    <div class="img_accordion pull-left mr-2">
                                                                                                        <img src="{!! $data->imagePath !!}"
                                                                                                             class="img-fluid"
                                                                                                             alt="">
                                                                                                    </div>

                                                                                                    <div class="info pull-left">
                                                                                                        <strong>{!! $data->translate(App::getLocale())->name !!}</strong>

                                                                                                        <span>{!! $data->translate(App::getLocale())->description !!}</span>
                                                                                                    </div>

                                                                                                    <div
                                                                                                            class="rating_accordion pull-left">
                                                                                                        <strong>Rating</strong>
                                                                                                        <i class="fa fa-star"></i>
                                                                                                        <i class="fa fa-star"></i>
                                                                                                        <i class="fa fa-star"></i>
                                                                                                        <i class="fa fa-star"></i>
                                                                                                        <i class="fa fa-star"></i>
                                                                                                    </div>
                                                                                                    <div class="sec_add_cart">
                                                                                                      <strong class="price_menus">{!! percentageOrder($data->price)  !!} @lang('web.S.R')</strong>

                                                                                                    @if(Auth::guard('web')->check())
                                                                                                        <button type="button"
                                                                                                                class="btn_add_car addCart{{--{!! checkTheSameRestaurant(Auth::guard('web')->user(),$restaurants->id) == true ? 'addCart' :'' !!}--}}"
                                                                                                                data-id="{!! $data->id !!}"
                                                                                                                data-name="{!! $restaurants->translate(App::getLocale())->name !!}"
                                                                                                                data-toggle="modal"
                                                                                                                data-target=".modal_select_to_cart">
                                                                                                            <i class="fa fa-plus"></i>
                                                                                                            @lang('web.Add To Cart')
                                                                                                        </button>

                                                                                                            @else
                                                                                                          {{--  <a href="{!! route('web.add.cart',['id'=>$data->id]) !!}"
                                                                                                                    class="btn_add_car">
                                                                                                                <i class="fa fa-plus"></i>
                                                                                                                @lang('web.Add To Cart')
                                                                                                            </a>--}}
                                                                                                              <a href="{!! route('web.get.login') !!}"
                                                                                                                    class="btn_add_car">
                                                                                                                <i class="fa fa-plus"></i>
                                                                                                                @lang('web.Add To Cart')
                                                                                                            </a>
                                                                                                            @endif
                                                                                                    </div>
                                                                                            </div>
                                                                                            @endforeach


                                                                                        </div>

                                                                                    </div>

                                                                                @endforeach

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Review Tab -->
                                                                <div class="tab-pane fade" id="tab1-3">
                                                                    <div class="">
                                                                        <h4 class="">({!! count($restaurants->ratingRestaurant()->get()) !!}) @lang('web.Reviews')</h4>

                                                                        <div class="row view_rating_reviews">
                                                                            <div class="col-md-3">
                                                                                <div class="rating">
                                                                                    <strong>Rating (5)</strong>

                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>

                                                                                    <p>Order Packaging</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="rating">
                                                                                    <strong>Rating (5)</strong>

                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>

                                                                                    <p>Value for money</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="rating">
                                                                                    <strong>Rating (5)</strong>

                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>

                                                                                    <p>Delivery Time</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="rating no_border">
                                                                                    <strong>Rating (5)</strong>

                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>

                                                                                    <p>Quality of food</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        {!! csrf_field() !!}
                                                                        <div id="post_data" class="row mt-5">

                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <!-- Info Tab-->
                                                                <div class="tab-pane fade" id="tab1-5">
                                                                    <div class="restaurant-info-wrapper">
                                                                        <h3 class="title3" itemprop="headline">
                                                                            {!! $restaurants->translate(App::getLocale())->name !!}
                                                                        </h3>

                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div
                                                                                        class="row border_bottom pt-3 pb-3 pl-3">
                                                                                    <div class="col-md-6">
                                                                                        Minimum Order Amount
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        EGP 0.00
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row border_bottom pt-3 pb-3 pl-3">
                                                                                    <div class="col-md-12">
                                                                                        <div class="accordion_modal">
                                                                                            <div class="item_select_head clearfix">
                                                                                                <strong class="pull-left">@lang('web.Working Hours')</strong>
                                                                                                <i class="fa fa-chevron-down pull-right"></i>
                                                                                            </div>
                                                                                            <div class="item_desc_info">
                                                                                                <div class="row">
                                                                                                    @foreach($restaurants->workingHours()->get() as $key =>$value)
                                                                                                    <div class="col-md-12">

                                                                                                        <div class="row">
                                                                                                            <div class="col-md-6">
                                                                                                                {!! \App\Day::find($value->day_id)->translate(App::getLocale())->name !!}
                                                                                                            </div>
                                                                                                            <div class="col-md-6">
                                                                                                                {!! date('h:m A',strtotime($value->from)) !!} - {!! date('h:m A',strtotime($value->to)) !!}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    @endforeach

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                        class="row border_bottom pt-3 pb-3 pl-3">
                                                                                    <div class="col-md-6">
                                                                                        @lang('web.Delivery Time')
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        @lang('web.15 min')
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                        class="row border_bottom pt-3 pb-3 pl-3">
                                                                                    <div class="col-md-6">
                                                                                        VAT
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        14 %
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                        class="row border_bottom pt-3 pb-3 pl-3">
                                                                                    <div class="col-md-6">
                                                                                        Pre-Order
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        No
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                        class="row border_bottom pt-3 pb-3 pl-3">
                                                                                    <div class="col-md-6">
                                                                                        @lang('web.Payment')
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <img src="{{asset('public/frontend/assets/img/payment/1.jpg')}}"
                                                                                             class="img-fluid payment_img"
                                                                                             alt="">
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                        class="row border_bottom pt-3 pb-3 pl-3">
                                                                                    <div class="col-md-6">
                                                                                        @lang('web.Rating')
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="rating">
                                                                                            <strong>@lang('web.Rating') ({!! $rating !!})</strong>

                                                                                            @for($i=0; $i<$rating; $i++ )
                                                                                            <i class="fa fa-star"></i>
                                                                                                @endfor

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                        class="row border_bottom pt-3 pb-3 pl-3">
                                                                                    <div class="col-md-6">
                                                                                       @lang('web.Cuisines')
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        @foreach($restaurants->menuRestaurant()->limit(3)->get() as $res)
                                                                                            {!! $res->translate(App::getLocale())->name !!}
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            @if(Auth::guard('web')->user())
                                                            <div class="cart_restu hidden_sm" style="display: block;">
                                                                <h5>@lang('web.Your Cart')</h5>
                                                                <span class="name_owner">{!! $restaurants->translate(App::getLocale())->name !!}</span>

                                                                <!-- LOOP THIS YA WALEED -->
                                                                <input type="hidden" value="{!! $Subtotal = 0 !!}">

                                                                @if(count($cart) > 0)
                                                                @foreach($cart as $key =>$value)
                                                                    <div class="qty_order pl-3 pr-3 clearfix">
                                                                        <div class="number_pl">
                                                                            <span class="minus"
                                                                                  data-id="{!! $value->menu_details_id !!}"
                                                                            data-name="{!! $restaurants->translate(App::getLocale())->name !!}">-</span>
                                                                            <input type="text"  value="{!! $value->qty !!}"/>
                                                                            <span class="plus"
                                                                                  data-id="{!! $value->menu_details_id !!}"
                                                                                  data-name="{!! $restaurants->translate(App::getLocale())->name !!}"
                                                                            >+</span>
                                                                        </div>

                                                                        <i class="fa fa-times-circle pull-right removeCart" data-id="{!! $value->id !!}"
                                                                           data-name="{!! $restaurants->translate(App::getLocale())->name !!}"></i>

                                                                        <span class="name_order">@lang('web.Price') :{!!percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price)!!}</span>

                                                                    </div>
                                                                    <input type="hidden"
                                                                           value="{!! $Subtotal +=percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price) * $value->qty  !!}">

                                                                @endforeach

                                                                    <div class="sub_total">
                                                                        <ul class="nav pl-4 pr-4">
                                                                            <li class="clearfix mb-3">
                                                                                <span class="pull-left">@lang('web.Subtotal')</span>
                                                                                <span class="pull-right">{!! $Subtotal !!}</span>
                                                                            </li>
                                                                            <li class="clearfix mb-3">
                                                                                <span class="pull-left">@lang('web.Delivery Fees')</span>
                                                                                <span class="pull-right">{!! deliveryFees()['amountDelivery'] !!}</span>
                                                                            </li>
                                                                            <input type="hidden"
                                                                                   value="{!!  $totalAmount = 0!!}">
                                                                            <input type="hidden"
                                                                                   value="{!!  $totalAmount = $Subtotal + deliveryFees()['amountDelivery']!!}">
                                                                            <li class="clearfix mb-3 mt-5">
                                                                                <span class="pull-left">@lang('web.Total Amount')</span>
                                                                                <span class="pull-right">{!! $totalAmount !!}</span>
                                                                            </li>
                                                                        </ul>
                                                                        <a href="{!! route('web.get.checkout') !!}"
                                                                           class="btn_chk_cart btn btn-primary btn-block">
                                                                            @lang('web.Proceed to Checkout')
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <div class="sub_total">
                                                                        <span class="name_owner"> @lang('web.Cart is Empty')</span>
                                                                    </div>
                                                                    @endif

                                                            </div>

                                                                @else
                                                                <div class="cart_restu hidden_sm">
                                                                    <h5>@lang('web.Your Cart')</h5>
                                                                    <span class="name_owner">{!! $restaurants->translate(App::getLocale())->name !!}</span>

                                                                    <!-- LOOP THIS YA WALEED -->
                                                                    <div class="sub_total">

                                                                        <span class="name_owner"> @lang('web.Cart is Empty')</span>
                                                                    </div>

                                                                </div>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //== END Page Details -->


@endsection