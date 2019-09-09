@extends('frontend.layouts.master')

@section('title',trans('web.Blog'))

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Blog Detail')</li>
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
                                        <img src="{!! $blog->imagePath !!}" alt="{!! $blog->image !!}" itemprop="image">
                                    </div>
                                    <div class="blog-detail-info">
                                        <span class="post-detail-date red-clr"><i class="fa fa-clock-o"></i> {!! date('M d, Y',strtotime($blog->created_at)) !!}</span>
                                    </div>
                                    <h1 itemprop="headline">{!! $blog->translate(App::getLocale())->title !!}</h1>
                                    <p>{!!html_entity_decode($blog->translate(App::getLocale())->description)!!}</p>

                                </div>

                            </div>
                        </div><!-- Section Box -->
                    </div>
                </div>
            </div>

        </div>
    </section>



@endsection