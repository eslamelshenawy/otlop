@extends('backend.layouts.master')

@section('title',trans('admin.Dashboard'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Control panel')
            <small>@lang('Dashboard')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{!! route('vendor.home') !!}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active"> @lang('admin.Dashboard')</li>
        </ol>
    </section>
@endsection

@section('main-content')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Dashboard Vendors</h3>
                </div>
            </div>


        </div>

    </div>

@endsection