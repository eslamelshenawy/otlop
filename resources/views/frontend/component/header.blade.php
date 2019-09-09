

<div class="preloader">
    <div id="cooking">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div id="area">
            <div id="sides">
                <div id="pan"></div>
                <div id="handle"></div>
            </div>
            <div id="pancake">
                <div id="pastry"></div>
            </div>
        </div>
    </div>
</div>

<header class="stick header">
    <div class="logo-menu-sec">
        <div class="container">

            <div class="logo">
                <h1 itemprop="headline"><a href="{!! route('web.home') !!}" title="Home" itemprop="url"><img
                                src="{!! setting()->logoPath!!}" alt="logo.png" itemprop="image"></a></h1>
            </div>
            <nav>
                <div class="menu-sec">
                    <ul>
                        <li><a href="{!! route('web.home') !!}" title="@lang('web.Home')">@lang('web.Home')</a></li>
                        <li><a href="{!! route('web.offer') !!}" title="">@lang('web.Offers')</a></li>
                        <li><a href="{!! route('web.most.selling') !!}" title="">@lang('web.Most Selling')</a></li>
                        <li><a href="{!! route('web.get.restaurants') !!}" title="">@lang('web.Restaurant')</a></li>
                        <li><a href="{!! route('web.get.coupon') !!}" title="">@lang('web.Coupon')</a></li>
                    </ul>
                    @if(App::getLocale()=="ar")
                        <a class="mr-5" href="{{url('en')}}"><i
                                    class="fa fa-language ">  English </i>
                        </a>
                    @else
                        <a class="ml-5" href="{{url('ar')}}"><i
                                    class="fa fa-language "> العربية </i>
                        </a>
                        @endif
                        @guest

                            </a>

                    <a class="red-bg brd-rd4" href="{!! route('web.get.register') !!}" title="Register" > <i
                                class="fa fa-sign-out"></i> @lang('web.Register') </a>
                    <a class="red-bg brd-rd5" href="{!! route('web.get.login') !!}" title="Login" > <i
                                class="fa fa-user"></i> @lang('web.Login') </a>


                @else

                        <div class="dropdown pull-right">
                            <a href="javascript:void(0)" class="account">
                                <img src="{!! Auth::guard('web')->user()->imagePath !!}" class="profile-circle" />
                                <span>{!! Auth::guard('web')->user()->firstName.' '.Auth::guard('web')->user()->lastName !!}</span>
                            </a>
                            <div class="submenu" style="display: none; ">
                                <ul class="root">
                                   {{-- <li>
                                        <a href="#">Dashboard</a>
                                    </li>--}}
                                    <li>
                                        <a href="{!! route('web.users.profile') !!}"><i class="fa fa-user"></i> @lang('web.Profile')</a>
                                    </li>
                                   {{-- <li>
                                        <a href="#">Settings</a>
                                    </li>--}}
                                    <li>
                                        <a  href="{!! route('web.logout.users') !!}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <form id="logout-form" action="{{ route('web.logout.users') }}" method="get" style="display: none;">
                                                @csrf
                                            </form>
                                            <i class="fa fa-sign-out"></i> @lang('web.Sign Out') </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endguest
                </div>
            </nav><!-- Navigation -->


            <!-- Cart -->
            @if(Auth::guard('web')->user())
            <div class="cart_h pull-right mr-4 myCart">
                    <span id="btn_show_cart" class="cart_ppt">
                        <i id="show_cart" class="fa fa-shopping-cart fa-lg"></i>
                        <b class="count_cart_ppt">{!! count(myCart(Auth::guard('web')->user()->id)) !!}</b>
                    </span>
                <ul class="cart_down">
                    <i id="close_cart" class="fa fa-times"></i>
                    @if(count(myCart(Auth::guard('web')->user()->id)) > 0)

                    @foreach(myCart(Auth::guard('web')->user()->id) as $key =>$value)
                    <li>
                        <img src="{!! \App\MenuDetails::find($value->menu_details_id)->imagePath !!}" class="img-fluid pull-left" alt="">
                        <div class="info_course_c pull-left">

                            <h6>{!! \App\MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name !!}</h6>

                            <div class="qty-wrap">
                                <input class="qty" min="1" data-name="qty"  data-id="{!! $value->menu_details_id !!}" type="text"  data-value="{!! $value->qty !!}" value="{!! $value->qty !!}">

                            </div>

                            <strong class="price">@lang('web.S.R') {!! percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price )!!}</strong>

                        </div>
                        <i class="fa fa-times removeCart" data-name="removeCart" data-id="{!! $value->id !!}"></i>

                    </li>
                        @endforeach

                    <li>

                        <strong>@lang('web.Total : S.R ') {!!  sumCart(Auth::guard('web')->user()->id) !!}</strong>
                        <a href="{!! route('web.get.checkout') !!}" class="btn_chk_cart">@lang('web.Go To Cart')</a>
                    </li>
                        @else
                        <span class="name_owner text-center"> @lang('web.Cart is Empty')</span>
                    @endif
                </ul>
            </div>
                @else
                <div class="cart_h pull-right mr-4 myCart">
                    <span id="btn_show_cart" class="cart_ppt">
                        <i id="show_cart" class="fa fa-shopping-cart fa-lg"></i>
                        <b class="count_cart_ppt">0</b>
                    </span>
                    <ul class="cart_down">
                        <i id="close_cart" class="fa fa-times"></i>
                        <span class="name_owner text-center"> @lang('web.Cart is Empty')</span>
                    </ul>
                </div>

            @endif


            <!-- // Cart -->

        </div>
    </div><!-- Logo Menu Section -->
</header><!-- Header -->


<!-- Modal Register -->
<div class="modal fade modal_register_users register_u" id="reg_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>

                </button>
                <h4 class="modal-title" id="myModalLabel">@lang('web.Register')</h4>

            </div>
            <div class="modal-body">
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#user-normal" aria-controls="user-normal"
                               role="tab" data-toggle="tab">@lang('web.Register')</a>
                        </li>
                        <li role="presentation">
                            <a href="#delivry-man" aria-controls="delivry-man" role="tab"
                               data-toggle="tab">@lang('web.Delivery man')</a>
                        </li>
                        <li role="presentation">
                            <a href="#owner-man" aria-controls="owner-man" role="tab"
                               data-toggle="tab">@lang('web.Register Owner')</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="user-normal">
                            <div class="sign-popup-inner brd-rd5">
                                <div class="sign-popup-title text-center">
                                    <h4 itemprop="headline">@lang('web.SIGN UP')</h4>
                                </div>
                                <div class="popup-social text-center">
                                    <a class="facebook brd-rd3" href="#" title="Facebook" itemprop="url" target="_blank"><i
                                                class="fa fa-facebook"></i>
                                        @lang('web.Facebook')</a>
                                    <a class="google brd-rd3" href="#" title="Google Plus" itemprop="url" target="_blank"><i
                                                class="fa fa-google-plus"></i>
                                        @lang('web.Google')</a>
                                </div>
                                <span class="popup-seprator text-center"><i class="brd-rd50">@lang('web.or')</i></span>
                              {!! Form::open(['url'=>route('web.register.users'),'method'=>'post','files'=>true,'class'=>'sign-form']) !!}

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <input class="brd-rd3" name="firstName" value="{!! old('firstName') !!}" type="text" placeholder="@lang('web.First Name')">
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <input class="brd-rd3" name="lastName" value="{!! old('lastName') !!}" type="text" placeholder="@lang('web.Last Name')">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-6">
                                            <input class="brd-rd3" type="email" value="{!! old('email') !!}" name="email" placeholder="@lang('web.Email')">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-6 file_upload_style" name="image" data-text="@lang('web.Select your file!')">
                                            <input class="brd-rd3 file_input" type="file">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-6">
                                            <input class="brd-rd3" type="password" name="password"  placeholder="@lang('web.Password')">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-6">
                                            <input class="brd-rd3" type="password" name="password_confirmation"  placeholder="@lang('web.Confirm Password')">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <button class="red-bg brd-rd3" type="submit">@lang('web.REGISTER NOW')</button>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <a class="recover-btn" href="#" title="" itemprop="url">@lang('web.Recover my password')</a>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="delivry-man">
                            <div class="sign-popup-inner brd-rd5">
                                <div class="sign-popup-title text-center">
                                    <h4 itemprop="headline">@lang('web.SIGN UP')</h4>
                                </div>
                                {!! Form::open(['url'=>route('web.register.delivery'),'method'=>'post','files'=>true,'class'=>'sign-form']) !!}
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <input class="brd-rd3" type="text" name="firstName" value="{!! old('firstName') !!}" placeholder="@lang('web.First Name')">
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-lg-6">
                                            <input class="brd-rd3" type="text" value="{!! old('lastName') !!}" name="lastName" placeholder="@lang('web.Last Name')">
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-6">
                                            <input class="brd-rd3" type="email" value="{!! old('email') !!}" name="email" placeholder="@lang('web.Email')">
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
                                            <button class="red-bg brd-rd3" type="submit">@lang('web.REGISTER NOW')</button>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <a class="recover-btn" href="#" title="" itemprop="url">@lang('web.Recover my password')</a>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="owner-man">
                            <div class="sign-popup-inner brd-rd5">

                                {!! Form::open(['url'=>route('web.register.restaurant'),'method'=>'post','files'=>true,'class'=>'sign-form']) !!}
                                    <div class="row">
                                        @foreach(config('translatable.locales') as $locale)

                                            <div class="col-md-6 col-sm-12 col-lg-6">
                                                <input class="brd-rd3" type="text"
                                                       value="{{old($locale.'.name')}}" name="{{$locale}}[name]"
                                                       placeholder="@lang('admin.'.$locale.'.resName')">
                                            </div>

                                        @endforeach

                                            @foreach(config('translatable.locales') as $locale)

                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <input class="brd-rd3" type="text"
                                                           value="{{old($locale.'.address')}}" name="{{$locale}}[address]"
                                                           placeholder="@lang('admin.'.$locale.'.resNameAddress')">
                                                </div>

                                            @endforeach

                                        <div class="col-md-12 col-sm-12 col-lg-6 file_upload_style" data-text="Logo Image">
                                            <input class="brd-rd3 file_input" type="file" name="logo">
                                        </div>
                                            <div class="col-md-6 col-sm-12 col-lg-6">
                                                <input class="brd-rd3" type="text" name="firstName" value="{!! old('firstName') !!}" placeholder="@lang('web.First Name')">
                                            </div>
                                            <div class="col-md-6 col-sm-12 col-lg-6">
                                                <input class="brd-rd3" type="text" value="{!! old('lastName') !!}" name="lastName" placeholder="@lang('web.Last Name')">
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <input class="brd-rd3" type="email" value="{!! old('email') !!}" name="email" placeholder="@lang('web.Email')">
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <input class="brd-rd3" type="password" name="password" placeholder="@lang('web.Password')">
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <input class="brd-rd3" type="password" name="password_confirmation" placeholder="@lang('web.Confirm Password')">
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-lg-6 file_upload_style" data-text="Select your file!">
                                                <input class="brd-rd3 file_input" type="file" name="image">
                                            </div>

                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <button class="red-bg brd-rd3" type="submit">@lang('web.REGISTER NOW')</button>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <a class="recover-btn" href="#" title="" itemprop="url">@lang('web.Recover my password')</a>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="responsive-header">
    <div class="responsive-logomenu">
        <div class="logo">
            <h1 itemprop="headline"><a href="index.html" title="Home" itemprop="url"><img
                            src="assets/img/logo-s.png" alt="logo.png" itemprop="image"></a></h1>
        </div>
        <span class="menu-btn yellow-bg brd-rd4"><i class="fa fa-align-justify"></i></span>
    </div>
    <div class="responsive-menu">
        <span class="menu-close red-bg brd-rd3"><i class="fa fa-close"></i></span>
        <div class="menu-lst">
            <ul>
                <li><a href="{!! route('web.home') !!}" title="@lang('web.Home')">@lang('web.Home')</a></li>
                <li><a href="#" title="#">Offers</a></li>
                <li><a href="#" title="#">Most Selling</a></li>
                <li><a href="#" title="#">Restaurant</a></li>
            </ul>
            <a class="red-bg brd-rd4 sign-popup-btn" href="" title="Register" itemprop="url"> <i
                        class="fa fa-sign-out"></i> Register </a>
            <a class="red-bg brd-rd5 log-popup-btn" href="" title="Login" itemprop="url"> <i class="fa fa-user"></i>
                Login </a>
        </div>
    </div><!-- Responsive Menu -->
</div><!-- Responsive Header -->


@push('js')
    <script>
        $(document).on('click', '.removeCart', function (e) {
            e.preventDefault();
            var removeCart = $(this).data('id');
            var name = $(this).data('name');
            $.ajax({
                url: '{!! route('web.remove.cart') !!}',
                type: 'post',
                data: {removeCart: removeCart , name: name},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $('.cart_down').html(data.cart);
                    $('.cart_down ').html(data.myCart)
                }
            });
        });

        $(document).on('change', '.qty', function (e) {
            e.preventDefault();
            var menu_details_id = $(this).data('id');
            var name = $(this).data('name');
            var qty = $(this).data('value');
            $.ajax({
                url: '{!! route('web.qty.cart') !!}',
                type: 'post',
                data: {menu_details_id: menu_details_id , name: name, qty : qty },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $('.cart_down').html(data.cart);
                    $('.cart_down ').html(data.myCart)
                }
            });
        });
    </script>
@endpush


