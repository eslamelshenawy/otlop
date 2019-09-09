@extends('backend.layouts.master')

@section('title',trans('admin.Privacy'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Privacy')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(auth()->guard('admin')->user()->hasPermission('read_privacy'))
                <li ><a href="{{route('admin.privacy.index')}}"><i class="fa fa-users"></i> @lang('admin.View Privacy')</a></li>
            @else
                <li ><a><i class="fa fa-users disabled"></i> @lang('admin.View Privacy')</a></li>
            @endif
            <li class="active">@lang('admin.Create privacy')</li>
        </ol>
    </section>
@endsection()

@section('main-content')
    @push('js')
        <script src="{!! asset('backend/bower_components/ckeditor/ckeditor.js') !!}"></script>
        <script type="text/javascript">
            $(function () {
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace('editor1');
                CKEDITOR.replace('editor2');
                $('.textarea').wysihtml5()
            })
        </script>


    @endpush

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data privacy')</h3>
                </div>
                {!! Form::open(['url'=>routeAdmin('privacy.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                        @foreach(config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.'.$locale.'.privacyTitle')</label>
                                <input type="text" class="form-control" value="{{old($locale.'.title')}}" name="{{$locale}}[title]"
                                       id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.privacyTitle')">
                            </div>

                        @endforeach

                            @foreach(config('translatable.locales') as $key => $locale)
                                <div class="form-group">
                                    <label for="inputSkills">@lang('admin.'.$locale.'.privacyDescription')</label>
                                    <textarea id="editor{{$key+1}}" name="{{$locale}}[description]" placeholder="@lang('admin.'.$locale.'.privacyDescription')"
                                              rows="10" cols="80">{{old($locale.'.description')}}</textarea>
                                </div>
                            @endforeach

                        <div class="form-group">
                            <label for="exampleInputPassword1">@lang('admin.Status')</label>
                            {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                            old('status'),['class'=>'form-control select2','style'=>'width: 100%;']) !!}

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


