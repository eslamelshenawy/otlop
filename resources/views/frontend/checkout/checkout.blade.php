@extends('frontend.layouts.master')

@section('title',trans('web.Checkout'))

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Checkout')</li>
            </ol>
        </div>
    </div>

    @include('frontend.component.message')

    @if(session()->has('warning'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('warning') }}
        </div>
    @endif
    <!-- START Checkout -->
    <section class="page_checkout">
        <div class="container">

            <div class="card">
                <div class="card_head">
                    <h3>@lang('web.Order Summary')</h3>
                </div>
                <div class="card_body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('web.Item(s)') </th>
                                        <th>@lang('web.Quantity')</th>
                                        <th>@lang('web.Price')</th>
                                        <th>@lang('web.Total')</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($cart as $key =>$value)
                                    <tr>
                                        <th scope="row">{!! $key+1 !!}</th>
                                        <td>{!! \App\MenuDetails::find($value->menu_details_id)->translate(App::getLocale())->name !!}</td>
                                        <td>{!! $value->qty !!}  </td>
                                        <td>{!! percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price) !!} @lang('web.S.R')</td>
                                        <td>{!! $value->qty *  percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price)!!} @lang('web.S.R')</td>
                                    </tr>
                                   @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card_head">
                    <h3>@lang('web.Order Summary')</h3>
                </div>
                <div class="card_body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('web.Address')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>{!! Auth::guard('web')->user()->address !!}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card_head">
                    <h3>@lang('web.Delivery Time')</h3>
                </div>
                {!! Form::open(['url'=>route('web.store.order'),'method'=>'post']) !!}
                <div class="card_body">
                    <div class="row">
                        <div class="col-md-8">

                            <p class="">@lang('web.Select your payment method')</p>
                            <div class="mt-2 ml-4 img_debit_cart">
                                <a class="radio-box card-popup-btn">
                                    <input type="radio" value="card" {!! old('card') == 'on' ?'checked' : '' !!} name="payment_type" id="pay1-1">
                                    <label for="pay1-1">
                                        <span><img src="{!! url('frontend/') !!}/assets/img/card.png" alt=""> @lang('web.Debit/Credit Card')</span>

                                    </label>
                                </a>
                                <a class="radio-box card-popup-btn">
                                    <input type="radio" value="cash"  {!! old('cash') == 'on' ?'checked' : '' !!} name="payment_type" id="pay1-2">
                                    <label for="pay1-2">
                                        <span><img src="{!! url('frontend/') !!}/assets/img/cash.png" alt=""> @lang('web.Cash')</span>

                                    </label>
                                </a>
                                <a class="radio-box card-popup-btn">
                                    <input type="radio" value="wallet" {!! old('wallet') == 'on' ?'checked' : '' !!} name="payment_type" id="pay1-3">
                                    <label for="pay1-3">
                                        <span><img src="{!! url('frontend/') !!}/assets/img/wallet.png" alt=""> @lang('web.Wallet')</span>

                                    </label>
                                </a>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table table_card_chk">
                                    <tbody>
                                    <tr>
                                        <td scope="row">@lang('web.Subtotal')</td>
                                        <td>{!! percentageOrder($total) !!} @lang('web.S.R')</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">@lang('Delivery Fees')</td>
                                        <td>{!! deliveryFees()['amountDelivery'] !!} @lang('web.S.R')</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">@lang('web.VAT')</td>
                                        <td>0.00 @lang('web.S.R')</td>
                                    </tr>
                                    <tr class="brd_1">
                                        <th scope="row">@lang('web.Total Amount')</th>
                                        <th>{!! percentageOrder($total) +  deliveryFees()['amountDelivery']!!} @lang('web.S.R')</th>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <th colspan="2" class="text-center">
                                        <button type="submit" class="btn_table_chk">@lang('web.Place Order')</button>
                                    </th>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </section>
    <!-- //==END Checkout -->


@endsection