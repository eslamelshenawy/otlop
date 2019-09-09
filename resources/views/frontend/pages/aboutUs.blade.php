
@extends('frontend.layouts.master')

@section('title',$about->translate(App::getLocale())->name)

@section('content')

<div class="bread-crumbs-wrapper">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
            <li class="breadcrumb-item active">{!! $about->translate(App::getLocale())->name !!}</li>
        </ol>
    </div>
</div>




<section>
    <div class="block less-spacing gray-bg top-padd30">

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sec-box">
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="blog-detail-wrapper">
                                <div class="blog-detail-thumb wow fadeIn" data-wow-delay="0.2s">
                                    <img src="{!! $about->imagePath !!}" alt="{!! $about->image !!}" itemprop="image">
                                </div>
                                <div class="blog-detail-info">
                                    <span class="post-detail-date red-clr"><i class="fa fa-clock-o"></i> {!! date('M d, Y',strtotime($about->created_at)) !!}</span>
                                </div>
                                <h1 itemprop="headline">{!! $about->translate(App::getLocale())->title !!}</h1>
                                <p>{!! html_entity_decode($about->translate(App::getLocale())->description)  !!}</p>

                            </div>

                        </div>
                    </div><!-- Section Box -->
                </div>
            </div>
        </div>

    </div>
</section>
    @endsection