@extends('backend.layouts.master')

@section('title',trans('admin.Questions'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Questions')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(auth()->guard('admin')->user()->hasPermission('read_question'))
                <li ><a href="{{route('admin.question.index')}}"><i class="fa fa-users"></i> @lang('admin.View Questions')</a></li>
            @else
                <li ><a><i class="fa fa-users disabled"></i> @lang('admin.View Questions')</a></li>
            @endif
            <li class="active">@lang('admin.Create question')</li>
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
                    <h3 class="box-title">@lang('admin.Create data question')</h3>
                </div>
                {!! Form::open(['url'=>routeAdmin('question.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">@lang('admin.Category name')</label>
                        <select class="form-control select2" style="width: 100%;" name="category_id">
                            <option>@lang('admin.Select category name')</option>
                            @foreach($category as $key => $value)
                                    <option  value="{{$value->id}}" {!! old('category_id') == $value->id ? 'selected': '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                            @endforeach
                        </select>
                    </div>
                        @foreach(config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.'.$locale.'.questionTitle')</label>
                                <input type="text" class="form-control" value="{{old($locale.'.title')}}" name="{{$locale}}[title]"
                                       id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.questionTitle')">
                            </div>

                        @endforeach

                            @foreach(config('translatable.locales') as $key => $locale)
                                <div class="form-group">
                                    <label for="inputSkills">@lang('admin.'.$locale.'.questionDescription')</label>
                                    <textarea id="editor{{$key+1}}" name="{{$locale}}[description]" placeholder="@lang('admin.'.$locale.'.questionDescription')"
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


