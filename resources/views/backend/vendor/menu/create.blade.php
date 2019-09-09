@extends('backend.layouts.master')

@section('title',trans('admin.Menu'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Menu')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('vendor.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(auth()->guard('admin')->user()->hasPermission('read_menu'))
                <li ><a href="{{route('vendor.menu.index')}}"><i class="fa fa-users"></i> @lang('admin.View Menu')</a></li>
            @else
                <li ><a><i class="fa fa-meetup disabled"></i> @lang('admin.View Menu')</a></li>
            @endif
            <li class="active">@lang('admin.Create Menu')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data Menu')</h3>
                </div>
                {!! Form::open(['url'=>route('vendor.menu.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                        @foreach(config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.'.$locale.'.menuName')</label>
                                <input type="text" class="form-control" value="{{old($locale.'.name')}}" name="{{$locale}}[name]"
                                       id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.menuName')">
                            </div>

                        @endforeach

                        <div class="form-group">
                            <label for="exampleInputPassword1">@lang('admin.Status')</label>
                            {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                            old('status'),['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                        </div>

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> @lang('admin.Save')</button>
                </div>

                {!! Form::close() !!}
            </div>

        </div>

    </section>

@endsection


