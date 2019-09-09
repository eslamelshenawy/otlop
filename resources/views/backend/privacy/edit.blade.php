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
            @if(Auth::guard('admin')->user()->hasPermission('read_privacy'))
                <li ><a href="{{routeAdmin('privacy.index')}}"><i class="fa fa-list"></i> @lang('admin.View Privacy')</a></li>
            @else
                <li ><a><i class="fa fa-list"></i> @lang('admin.View Privacy')</a></li>
            @endif
            <li class="active">@lang('admin.Edit privacy')</li>
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
                    <h3 class="box-title">@lang('admin.Edit data privacy')</h3>
                </div>
                {!! Form::open(['url'=>route('admin.privacy.update',$privacy->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <input type="hidden" name="id" value="{!! $privacy->id !!}">
                    @foreach(config('translatable.locales') as $locale)
                        <div class="form-group">
                            <label for="exampleInputEmail1">@lang('admin.'.$locale.'.privacyTitle')</label>
                            <input type="text" class="form-control" value="{{$privacy->translate($locale)->title}}" name="{{$locale}}[title]"
                                   id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.privacyTitle')">
                        </div>

                    @endforeach

                    @foreach(config('translatable.locales') as $key => $locale)
                        <div class="form-group">
                            <label for="inputSkills">@lang('admin.'.$locale.'.privacyDescription')</label>
                            <textarea id="editor{{$key+1}}" name="{{$locale}}[description]" placeholder="@lang('admin.'.$locale.'.privacyDescription')"
                                      rows="10" cols="80">{{$privacy->translate($locale)->description}}</textarea>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label for="exampleInputPassword1">@lang('admin.Status')</label>
                        {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                        $privacy->status,['class'=>'form-control select2','style'=>'width: 100%;']) !!}

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


