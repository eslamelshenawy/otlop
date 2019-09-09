@extends('backend.layouts.master')

@section('title',trans('admin.Cities'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Cities')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('admin')->user()->hasPermission('read_cities'))
                <li ><a href="{{routeAdmin('city.index')}}"><i class="fa fa-list"></i> @lang('admin.View Cities')</a></li>
            @else
                <li ><a><i class="fa fa-list"></i> @lang('admin.View Cities')</a></li>
            @endif
            <li class="active">@lang('admin.Edit City')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data city')</h3>
                </div>
                {!! Form::open(['url'=>route('admin.city.update',$city->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <input type="hidden" name="id" value="{!! $city->id !!}">
                    @foreach(config('translatable.locales') as $locale)
                        <div class="form-group">
                            <label for="exampleInputEmail1">@lang('admin.'.$locale.'.cityName')</label>
                            <input type="text" class="form-control" value="{{$city->translate($locale)->name}}" name="{{$locale}}[name]"
                                   id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.cityName')">
                        </div>

                    @endforeach

                    <div class="form-group">
                        <label for="exampleInputPassword1">@lang('admin.Status')</label>
                        {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                        $city->status,['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                    </div>



                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-edit"></i> @lang('admin.Edit')</button>
                </div>

                {!! Form::close() !!}
            </div>
            <!-- /.box -->



        </div>

    </section>

@endsection


