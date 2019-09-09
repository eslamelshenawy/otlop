@extends('backend.layouts.master')

@section('title',trans('admin.Account'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Account delivery')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.Create account delivery')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data account delivery')</h3>
                </div>
                {!! Form::open(['url'=>route('admin.post.manage.shift'),'method'=>'post','files'=>true,'role'=>'form','id'=>'form_data']) !!}
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Total price morning')</label>
                                <input type="text" class="form-control" value="{{$data->total_price_morning}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                   this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="total_price_morning" id="exampleInputEmail1" placeholder="@lang('admin.Total price morning')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Total price night')</label>
                                <input type="text" class="form-control" value="{{$data->total_price_night}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                   this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="total_price_night" id="exampleInputEmail1" placeholder="@lang('admin.Total price night')">
                            </div>

                            <div class="form-group">
                                <label>@lang('admin.Start time')</label>
                                {{-- <input type="text" name="start_time" value="{{old('start_time')}}" class="form-control"
                                        data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>--}}
                                <div class="input-group">
                                    <input type="time" value="{!! $data->fromTime !!}"
                                           name="fromTime"
                                           class="form-control">

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Delivery visa morning')</label>
                                <input type="text" class="form-control" value="{{$data->delivery_visa_morning}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                   this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="delivery_visa_morning" id="exampleInputEmail1" placeholder="@lang('admin.Delivery visa morning')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Delivery visa night')</label>
                                <input type="text" class="form-control" value="{{$data->delivery_visa_night}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                   this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="delivery_visa_night" id="exampleInputEmail1" placeholder="@lang('admin.Delivery visa night')">
                            </div>

                            <div class="form-group">
                                <label>@lang('admin.End time')</label>
                                <div class="input-group">
                                    <input type="time" value="{!! $data->toTime !!}"
                                           name="toTime"
                                           class="form-control">

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Organization visa morning')</label>
                                <input type="text" class="form-control" autofocus value="{{$data->organization_visa_morning}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                   this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="organization_visa_morning" id="exampleInputEmail1" placeholder="@lang('admin.Organization visa morning')">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Organization visa night')</label>
                                <input type="text" class="form-control" value="{{$data->organization_visa_night}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                   this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="organization_visa_night" id="exampleInputEmail1" placeholder="@lang('admin.Organization visa night')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Percentage order')</label>
                                <input type="number"  class="form-control" value="{!! $data->percentageOrder !!}"
                                       name="percentageOrder" id="exampleInputEmail1" placeholder="@lang('admin.Percentage order')">
                            </div>

                        </div>


                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> @lang('admin.Save')</button>
                </div>

                {!! Form::close() !!}
            </div>
            <!-- /.box -->



        </div>

    </section>

@endsection


