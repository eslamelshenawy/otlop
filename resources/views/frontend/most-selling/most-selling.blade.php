@extends('frontend.layouts.master')

@section('title',trans('web.Most Seller'))

@section('content')

<div class="bread-crumbs-wrapper">
  <div class="container">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
      <li class="breadcrumb-item active">@lang('web.Most Seller')</li>
    </ol>
  </div>
</div>



<!-- START Restaurant -->
<section class="page_restau_details pt-0">
  <div class="container">

    <div class="details_restaurant mt-2">
      <div class="card">
        <div class="card_head">
          <h3>@lang('web.Most Seller')</h3>
        </div>
        <div class="card_body pt-0">
          <section class="page_restaurant">
            <div class="row">
              @foreach($restaurant as $key =>$value)
              <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="item text-center">
                  <a href="{!! route('web.view.restaurant',$value->translate(App::getLocale())->name) !!}">
                    <img src="{!! $value->logoPath !!}" class="img-responsive"
                         alt="">
                  </a>
                  <div class="info">
                    <a href="{!! route('web.view.restaurant',$value->translate(App::getLocale())->name) !!}" class="">{!! $value->translate(App::getLocale())->name !!}</a>
                    <a href="{!! route('web.view.restaurant',$value->translate(App::getLocale())->name) !!}">{!! \App\Type::find($value->type_id)->translate(App::getLocale())->name !!}</a>
                  </div>
                </div>
              </div>
                @endforeach

            </div>
          </section>
        </div>
      </div>
    </div>

  </div>
</section>
<!-- //== END Restaurant -->
@endsection