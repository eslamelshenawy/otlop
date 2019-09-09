@extends('frontend.layouts.master')

@section('title',trans('web.Blog'))

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">@lang('web.Maintenance')</li>
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
                                        <img src="{!! asset('upload/images/maintenance.png') !!}" alt="" itemprop="image">
                                    </div>
                                    <h1 itemprop="headline">{!! setting()->messageMaintenance !!}</h1>

                                </div>

                            </div>
                        </div><!-- Section Box -->
                    </div>
                </div>
            </div>

        </div>
    </section>



@endsection