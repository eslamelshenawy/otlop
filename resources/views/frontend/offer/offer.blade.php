@extends('frontend.layouts.master')

@section('title',trans('web.Offer'))

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Offer')</li>
            </ol>
        </div>
    </div>

    <!-- START Restaurant -->
    <section class="page_restau_details pt-0 page_offers">
        <div class="container">

            <div class="details_restaurant mt-2">
                <div class="card">
                    <div class="card_head">
                        <h3>@lang('web.Offers')</h3>
                    </div>
                    <div class="card_body pt-0">
                        <section class="page_restaurant">
                            <div class="row">
                                @foreach($restaurants as $key  => $value)
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="item text-center">
                                        <a type="button" class="" data-toggle="modal" data-target=".offers_modal_{!! $value->id !!}">
                                            <img src="{!! $value->logoPath !!}" class="img-responsive"
                                                 alt="">
                                        </a>
                                        <div class="info">
                                            <a type="button" class="" data-toggle="modal"
                                               data-target=".offers_modal_{!! $value->id !!}">{!! $value->translate(App::getLocale())->name !!}</a>
                                        </div>
                                    </div>

                                    <!-- Modal Popup Offers -->
                                    <div class="modal fade offers_modal_{!! $value->id !!}" tabindex="-1" role="dialog"
                                         aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">@lang('web.List offers')</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="restaurants-list">

                                                        @foreach($value->offers()->
                                                        whereDate('fromDate', '<=', date("Y-m-d",strtotime(\Carbon\Carbon::now())))
                    ->whereDate('toDate', '>=', date("Y-m-d",strtotime(\Carbon\Carbon::now())))
                                                        ->get() as $key  => $item)

                                                        <div class="featured-restaurant-box style3 brd-rd5">
                                                            <div class="featured-restaurant-thumb">
                                                                <a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name ) !!}" title="" itemprop="url">
                                                                    <img src="{!!  $value->logoPath !!}" alt="restaurant-logo1-1.png" itemprop="image">
                                                                </a>
                                                            </div>
                                                            <div class="featured-restaurant-info">
                                                                <h4 itemprop="headline" class="mb-3"><a href="#" title="" itemprop="url">{!! \App\MenuDetails::find($item->menu_details_id)->translate(App::getLocale())->name !!}</a></h4>

                                                                <div class="mt-3 mb-3">
                                                                    {!! \App\MenuDetails::find($item->menu_details_id)->translate(App::getLocale())->description !!}
                                                                </div>
                                                            </div>
                                                            <div class="view-menu-liks">
                                                                <strong class="text-center mb-2 ml-3 d_inline_b">@lang('web.Price'):
                                                                    {!! $item->price !!} S.R</strong>
                                                                <a class="brd-rd3" {{--href="restaurant-details.html"--}}
                                                                   title="" itemprop="url">@lang('web.View Details')</a>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- //== END Modal Popup Offers -->

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