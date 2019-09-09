@extends('frontend.layouts.master')

@section('title',trans('web.Coupon'))

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Coupon')</li>
            </ol>
        </div>
    </div>

    <!-- START Restaurant -->
    <section class="page_coupon">
        <div class="container">

            <div class="row">

                @foreach($coupon as $key=>$value)
                <div class="col-md-4">
                    <div class="item text-center">
                        <h1> {!! $value->price !!} @lang('web.S.R')</h1>
                        <p>{!! $value->translate(App::getLocale())->name !!}</p>
                        <span>{!! $value->point !!} @lang('web.Riyal')</span>
                        <a href="#">@lang('web.Buy Coupon')</a>
                    </div>
                </div>
                @endforeach

            </div>

        </div>
    </section>
    <!-- //== END Restaurant -->




@endsection