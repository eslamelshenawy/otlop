@extends('backend.layouts.master')

@section('title',trans('admin.Dashboard'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Control panel')
            <small>@lang('Dashboard')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{!! routeAdmin('') !!}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active"> @lang('admin.Dashboard')</li>
        </ol>
    </section>
@endsection

@section('main-content')


    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{!! count($orders) !!}</h3>

                        <p>@lang('admin.New Orders')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{--{!! route('admin.order.index') !!}--}}" class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{!! count($vendors) !!}</h3>

                        <p>@lang('admin.Vendors')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-stalker"></i>
                    </div>
                    @if(auth()->guard('admin')->user()->hasPermission('read_admins'))
                        <a href="{!! routeAdmin('admin.index') !!}?userType=vendor" class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></a>
                    @else
                        <button disabled class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></button>

                    @endif

                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{!! count($delivery) !!}<sup style="font-size: 20px"></sup></h3>

                        <p>@lang('admin.Delivery')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    @if(auth()->guard('admin')->user()->hasPermission('read_admins'))
                    <a href="{!! routeAdmin('admin.index') !!}?userType=delivery" class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></a>

                    @else
                        <button disabled class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></button>

                    @endif
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{!! count($users) !!}</h3>

                        <p>@lang('admin.User Registrations')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{!! route('admin.users') !!}" class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->


            <!-- ./col -->
        </div>

        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{!! percentageOrder($orders->sum('amount')) !!} <span> ريال</span></h3>

                        <p>@lang('admin.Total Orders')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-arrow-left-a"></i>
                    </div>
                    <a href="#" class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->




            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3>0.0 <span> ريال</span></h3>

                        <p>@lang('admin.Account Vendor')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-arrow-down-c"></i>
                    </div>
                    <a href="{!! routeAdmin('admin.index') !!}?userType=vendor" class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{!! $sumDelivery !!}<span> ريال</span></h3>

                        <p>@lang('admin.Account Delivery')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-arrow-down-a"></i>
                    </div>
                    @if(auth()->guard('admin')->user()->hasPermission('read_admins'))
                        <a href="{!! routeAdmin('admin.index') !!}?userType=delivery" class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></a>

                    @else
                        <button disabled class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></button>

                    @endif
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>{!! $sumUsers !!} <span> ريال</span> </h3>

                        <p>@lang('admin.Total Account')</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-arrow-left-c"></i>
                    </div>
                        <a href="{!! route('admin.users') !!}" class="small-box-footer">@lang('admin.More info') <i class="fa fa-arrow-circle-right"></i></a>

                </div>
            </div>
            <!-- ./col -->


            <!-- ./col -->
        </div>


    </section>
@endsection