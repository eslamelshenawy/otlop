@extends('backend.layouts.master')

@section('title',trans('admin.Admins'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Admins')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View Admin')</li>
        </ol>
    </section>
@endsection()

@section('main-content')


    <section class="content">

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('admin.View Admin')</h3>
                <div class="box-tools pull-right">
                    <div class="form-group">
                        @if(auth()->user()->hasPermission('create_admins'))
                            <a href="{{routeAdmin('admin.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Add Admin')</a>
                        @else
                            <a  class="btn btn-primary disabled"><i class="fa fa-plus-circle"> </i> @lang('admin.Add Admin')</a>
                        @endif
                    </div>

                </div>
            </div>

            <br>

        </div>

        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">@lang('admin.Data users')</h3>

                        {{--<div class="box-tools pull-right">
                            <div class="input-group input-group-sm" style="width: 150px;">

                                <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>--}}

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! Form::open(['id'=>'form_data','url'=>route('admin.admin.destroy.all'),'method'=>'delete']) !!}

                        @if(Auth::guard('admin')->user()->hasPermission('read_admins'))
                        {!! $dataTable->table(['class'=>'dataTable table table-bordered table-hover'],true) !!}
                        @endif
                        {!! Form::close() !!}

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div class="modal" id="multipleDelete">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('admin.Delete')</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="alert alert-danger">

                            <div class="empty_record hidden">
                                <h3>@lang('admin.Please select some records to delete')  </h3>
                            </div>


                            <div class="not_empty_record hidden">
                                <h3>@lang('admin.Are you sure delete all')  <span class="record_count"></span> ? </h3>
                            </div>


                        </div>

                    </div>


                     





                    <!-- Modal footer -->
                    <div class="empty_record hidden">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        </div>
                    </div>

                    <div class="not_empty_record hidden">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.No')</button>
                            <input type="submit" name="del_all" value="@lang('admin.Yes')" class="btn btn-danger del_all">
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>

    @push('js')
        @if(Auth::guard('admin')->user()->hasPermission('delete_admins'))
        <script>
            delete_all();
        </script>
        @endif
        @if(Auth::guard('admin')->user()->hasPermission('read_admins'))
        {!! $dataTable->scripts() !!}
        @endif

    @endpush

@endsection


