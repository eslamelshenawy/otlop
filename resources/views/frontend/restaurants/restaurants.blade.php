@extends('frontend.layouts.master')

@section('title',trans('web.Restaurants'))

@section('content')
    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Restaurant')</li>
            </ol>
        </div>
    </div>

    <!-- START Restaurant -->
    <section class="page_restaurant">
        <div class="container">
            <div class="row">
                @foreach($restaurants as $key =>$value)
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                    <div class="item text-center">
                        <a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}">
                            <img src="{!! $value->logoPath !!}" class="img-responsive" alt="">
                        </a>

                        <div class="info">
                            <a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}" class="">{!! $value->translate(App::getLocale())->name !!}</a>
                            <a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}">
                            {!! \App\Type::find($value->type_id)->translate(App::getLocale())->name!!}
                                {{--@foreach($value->menuRestaurant()->limit(3)->get() as $res)
                                    {!! $res->translate(App::getLocale())->name !!}
                                    @endforeach--}}
                            </a>
                        </div>
                    </div>
                </div>
                    @endforeach


            </div>
        </div>
    </section>
    <!-- //== END Restaurant -->

@endsection