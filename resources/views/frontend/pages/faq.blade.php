
@extends('frontend.layouts.master')

@section('title',trans('web.FAQ'))

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Frequently Asked Questions (FAQ)')</li>
            </ol>
        </div>
    </div>



    <section class="page_terms page_faq">
        <div class="container">
            <div class="terms_content">
                <div class="row">

                    <div class="col-md-3 col-sm-12 col-lg-3">
                        <ul class="nav pt-0 m-0 nav_left_accordion">
                            <li><strong>@lang('web.Categories')</strong></li>
                            @foreach($faq as $key =>$value)
                            <li>
                                <a href="#faq{!! $key !!}"><i class="fa fa-chevron-right"></i>
                                    {!! $value->translate(App::getLocale())->name !!}</a>
                            </li>
                           @endforeach

                        </ul>
                    </div>

                    <div class="col-md-9">
                        @foreach($faq as $key =>$value)
                        <div id="faq{!! $key !!}" class="items">

                            <h2>{!! $value->translate(App::getLocale())->name !!}</h2>
                           @foreach($value->FQACategory()->get() as $key => $item)
                            <div class="item">
                                <h3 class="btn_show_acc">{!! $item->translate(App::getLocale())->title !!} <i class="fa fa-plus"></i></h3>
                                <div class="desc">
                                   {!! html_entity_decode($item->translate(App::getLocale())->description) !!}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>




@endsection