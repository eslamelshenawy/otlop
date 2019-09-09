@extends('backend.layouts.master')

@section('title',trans('admin.Question'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Question')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('admin')->user()->hasPermission('read_question'))
                <li ><a href="{{routeAdmin('question.index')}}"><i class="fa fa-list"></i> @lang('admin.View Question')</a></li>
            @else
                <li ><a><i class="fa fa-list"></i> @lang('admin.View Question')</a></li>
            @endif
            <li class="active">@lang('admin.Edit question')</li>
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
                    <h3 class="box-title">@lang('admin.Edit data question')</h3>
                </div>
                {!! Form::open(['url'=>route('admin.question.update',$question->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <input type="hidden" name="id" value="{!! $question->id !!}">

                    <div class="form-group">
                        <label for="exampleInputEmail1">@lang('admin.Category name')</label>
                        <select class="form-control select2" style="width: 100%;" name="category_id">
                            <option>@lang('admin.Select category name')</option>
                            @foreach($category as $key => $value)
                                <option  value="{{$value->id}}" {!! $question->category_id == $value->id ? 'selected': '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @foreach(config('translatable.locales') as $locale)
                        <div class="form-group">
                            <label for="exampleInputEmail1">@lang('admin.'.$locale.'.questionTitle')</label>
                            <input type="text" class="form-control" value="{{$question->translate($locale)->title}}" name="{{$locale}}[title]"
                                   id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.questionTitle')">
                        </div>

                    @endforeach

                    @foreach(config('translatable.locales') as $key => $locale)
                        <div class="form-group">
                            <label for="inputSkills">@lang('admin.'.$locale.'.questionDescription')</label>
                            <textarea id="editor{{$key+1}}" name="{{$locale}}[description]" placeholder="@lang('admin.'.$locale.'.questionDescription')"
                                      rows="10" cols="80">{{$question->translate($locale)->description}}</textarea>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label for="exampleInputPassword1">@lang('admin.Status')</label>
                        {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                        $question->status,['class'=>'form-control select2','style'=>'width: 100%;']) !!}

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


