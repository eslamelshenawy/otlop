@extends('backend.layouts.master')

@section('title',trans('admin.Users'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Users')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View Users')</li>
        </ol>
    </section>
@endsection()

@section('main-content')


    <section class="content">

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('admin.View Users')</h3>
            </div>

            <br>

        </div>

        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">@lang('admin.Data users')</h3>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {!! $dataTable->table(['class'=>'dataTable table table-bordered table-hover'],true) !!}

                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>


    </section>

    @push('js')
        <script>
            delete_all();
        </script>
        {!! $dataTable->scripts() !!}

    @endpush
    @stack('script')
@endsection


