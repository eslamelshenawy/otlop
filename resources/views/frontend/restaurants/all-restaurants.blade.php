@extends('frontend.layouts.master')

@section('title',trans('web.Restaurants'))

@section('content')
@push('js')
    <script>
        $(document).on('click', '.allTypeRestaurants', function (e) {
            var name = $(this).data('name');
            $.ajax({
                url: '{!! route('web.filter.type.restaurant') !!}',
                type: 'post',
                data: { name: name},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data)
                {
                $('.restaurants-list').html(data)
                }
            });

        });

        $(document).on('click', '.filterTypeRestaurants', function (e) {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $.ajax({
                url: '{!! route('web.filter.type.restaurant') !!}',
                type: 'post',
                data: {id: id , name: name},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data)
                {
                    $('.restaurants-list').html(data)
                }
            });

        });
        $(document).ready(function () {
            var search = $(this).data('query');
            function fetch_data(search = '') {
                $.ajax({
                    url:"{!! route('web.search.ajax') !!}",
                    method:"GET",
                    data:{query:search},
                    dataType:'json',
                    success:function (data) {
                        $('.restaurants-list').html(data.dataResult)
                    }
                });
            }
        })

    </script>
  {{--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

    <script type="text/javascript">
        var route = "{{ route('web.autocomplete') }}";
        console.log(route);
        $('#search').typeahead({
            source:  function (term, process) {
                return $.get(route, { term: term }, function (data) {
                    return process(data);
                });
            }
        });
    </script>--}}

@endpush
    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Restaurant Result')</li>
            </ol>
        </div>
    </div>
    <!-- START Section Search Result -->
    <sectoin class="page_search_result">
        <div class="block gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="sec-box">
                            <div class="sec-wrapper">
                                <div class="row">
                                    <div class="col-md-9 col-sm-12 col-lg-9">
                                        <div class="tabs-wrp brd-rd5">
                                            <h4 itemprop="headline">@lang('web.Restaurants')</h4>

                                            <div class="sort_by">
                                                <span>@lang('web.Sort By :')</span>
                                                <a href="{!! route('web.sort.rating.restaurants') !!}"
                                                   onclick="event.preventDefault();
                                                     document.getElementById('sort3-form').submit();">
                                                    <form id="sort3-form" action="{!! route('web.sort.rating.restaurants') !!}" method="get">
                                                    </form>@lang('web.Rating')</a>
                                                <a href="{!! route('web.asc.restaurants') !!}"
                                                   onclick="event.preventDefault();
                                                     document.getElementById('sort-form').submit();">
                                                    <form id="sort-form" action="{!! route('web.asc.restaurants') !!}" method="get">
                                                    </form>
                                                    @lang('web.A to Z')</a>
                                                <a href="{!! route('web.desc.restaurants') !!}"
                                                   onclick="event.preventDefault();
                                                     document.getElementById('sort2-form').submit();">
                                                    <form id="sort2-form" action="{!! route('web.desc.restaurants') !!}" method="get">
                                                    </form>@lang('web.Newest')</a>
                                            </div>

                                            <div class="restaurants-list">
                                                @if(count($restaurants) > 0)
                                                @foreach($restaurants as $key =>$value)

                                                <div class="featured-restaurant-box style3 brd-rd5">
                                                    <div class="featured-restaurant-thumb"><a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}" title=""
                                                                                              itemprop="url"><img
                                                                    src="{!! $value->logoPath !!}"
                                                                    alt="restaurant-logo1-1.png" itemprop="image"></a></div>
                                                    <div class="featured-restaurant-info">
                                                        <h4 itemprop="headline" class="mb-3"><a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}" title=""
                                                           itemprop="url">{!! $value->translate(App::getLocale())->name !!}</a></h4>
                                                        <span class="red-clr">{!! $value->translate(App::getLocale())->addess !!}</span>

                                                        <div class="rating">
                                                            @if($value->ratingRestaurant()->count() > 0)
                                                            <ul class="nav navbar-nav mr-3">

                                                                @for($i=0; $i<number_format((int)$value->ratingRestaurant()->sum('rating') /$value->ratingRestaurant()->count(), 0, '.', ''); $i++)
                                                                <li><i class="fa fa-star"></i></li>
                                                                @endfor
                                                            </ul>
                                                            @endif

                                                            <span>({!! $value->ratingRestaurant()->count() > 0 ? $value->ratingRestaurant()->count() :trans('web.No') !!} @lang('web.Ratings') )</span>
                                                        </div>

                                                        <div class="mt-3 mb-3">
                                                            <strong>@lang('web.Pay by :') </strong>
                                                            @lang('web.Cash / Debit / Credit Card / Wallet')
                                                        </div>

                                                        <ul class="post-meta">
                                                            <li><i class="flaticon-transport"></i> @lang('web.Delivery') : @lang('web.20min')</li>
                                                            <li><i class="flaticon-transport"></i> @lang('web.AVG :') 30min</li>
                                                        </ul>
                                                    </div>
                                                    <div class="view-menu-liks">
                                                        <a class="brd-rd3" href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}" title=""
                                                           itemprop="url">@lang('web.View Details')</a>
                                                    </div>
                                                </div>

                                               @endforeach
                                                    @else
                                                    <div class="featured-restaurant-box style3 brd-rd5">

                                                        <div class="featured-restaurant-thumb"><span class="text-center">@lang('web.Not found data')</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-lg-3">
                                        <div class="order-wrapper right fixed_after_scrolling"
                                             data-wow-delay="0.2s">
                                            <div class="order-inner gradient-brd">
                                                  {!! Form::open(['url'=>route('web.restaurants.search'),'method'=>'post']) !!}
                                                    <div class="form-group">
                                                        <input type="search" value="{!! request('search') !!}" name="search" id="search" class="form-control"
                                                               placeholder="@lang('web.Write Your Search')">
                                                        <button type="submit" class="btn_search_filter"><i class="fa fa-search"></i>
                                                        </button>
                                                    </div>
                                              {!! Form::close() !!}
                                                <div class="order-list-wrapper">
                                                    <ul class="order-list-inner">
                                                        <li>
                                                            <div class="dish-name">
                                                                <i class="fa fa-filter"></i>
                                                                <h6 itemprop="headline">@lang('web.Cuisines') </h6>
                                                            </div>
                                                            <div class="dish-ingredients">
                                                              @foreach($typeRestaurant as $key =>$value)
                                                                <span class="radio-box cash-popup-btn">
                                                                    <input type="radio" data-id="{!! $value->id !!}" name="method" data-name="typeData" class="filterTypeRestaurants" id="pay{!! $key !!}">
                                                                    <label for="pay{!! $key !!}">
                                                                        <span>{!! $value->translate(App::getLocale())->name !!} </span>
                                                                        <i class="">{!! $value->restaurantType()->count() !!}</i>
                                                                    </label>
                                                                </span>
                                                               @endforeach

                                                            </div>
                                                        </li>
                                                    </ul>
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
    </sectoin>
    <!-- //== END Section Search Result -->

    @endsection