
@extends('frontend.layouts.master')

@section('title',trans('web.Privacy'))

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Privacy')</li>
            </ol>
        </div>
    </div>



    <section class="page_terms page_faq">
        <div class="container">
            <div class="terms_content">
                <div class="items">
                    <h2>@lang('web.Privacy')</h2>

                    @foreach($privacy as $key => $value)
                    <div class="item">
                        <h3 class="btn_show_acc">{!! $value->translate(App::getLocale())->title !!}<i class="fa fa-plus"></i></h3>
                        <div class="desc">
                            {!! html_entity_decode($value->translate(App::getLocale())->description)  !!}
                         </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>




@endsection