@extends('backend.layouts.master')

@section('title',trans('admin.Working Hours'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Working Hours')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('vendor.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
                <li ><a href="{{route('vendor.working.index')}}"><i class="fa fa-list"></i> @lang('admin.View Working Hours')</a></li>

            <li class="active">@lang('admin.Edit Working Hours')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data working hours')</h3>
                </div>
                {!! Form::open(['url'=>route('vendor.working.update',$working->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <input type="hidden" name="id" value="{!! $working->id !!}">

                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Day')</label>
                                <select class="form-control select2" style="width: 100%;" name="day_id" >
                                    <option>@lang('admin.Select day work')</option>
                                    @foreach($day as $key => $value)
                                        <option  value="{{$value->id}}" {!! $working->day_id == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>

                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>@lang('admin.Start time')</label>
                                <div class="input-group">
                                    <input type="time" value="{!! $working->from !!}"
                                           name="from"
                                           class="form-control">

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',[1=>trans('admin.On'),0=>trans('admin.Off')],
                                $working->status,['class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('admin.Select status')]) !!}

                            </div>

                            <div class="form-group">
                                <label>@lang('admin.End time')</label>
                                <div class="input-group">
                                    <input type="time" value="{!! $working->to !!}"
                                           name="to"
                                           class="form-control">

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-edit"></i> @lang('admin.Edit')</button>
                </div>

                {!! Form::close() !!}
            </div>

        </div>

    </section>

@endsection


