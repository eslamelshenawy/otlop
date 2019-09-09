
@extends('frontend.layouts.master')

@section('title',$term->translate(App::getLocale())->name)

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">{!! $term->translate(App::getLocale())->name !!}</li>
            </ol>
        </div>
    </div>


    <section class="page_about">
        <div class="container">
            <div class="about_content">
                <h1>{!! $term->translate(App::getLocale())->title !!}</h1>
                {!! html_entity_decode($term->translate(App::getLocale())->description)  !!}
            </div>
        </div>
    </section>

@endsection