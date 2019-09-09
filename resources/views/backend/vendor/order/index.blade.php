@extends('backend.layouts.master')

@section('title',trans('admin.Orders'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Orders')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('vendor.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View Orders')</li>
        </ol>
    </section>
@endsection()

@section('main-content')


    <section class="content">

        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! Form::open(['id'=>'form_data','url'=>route('vendor.order.destroy.all'),'method'=>'delete']) !!}

                        {!! $dataTable->table(['class'=>'dataTable table table-bordered table-hover'],true) !!}

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
        @if(auth()->guard('admin')->user()->hasPermission('delete_menu'))
        <script>
            delete_all();
        </script>
        @endif
        {!! $dataTable->scripts() !!}

    @endpush

@endsection


