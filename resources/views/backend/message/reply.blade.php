

@extends('backend.layouts.master')

@section('title',trans('admin.Mailbox'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Mailbox')
            <small>{{DB::table('messages')->where('receive',1)->count('receive')}} @lang('admin.new messages')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.Mailbox')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <a href="{{route('admin.message.inbox')}}" class="btn btn-primary btn-block margin-bottom">@lang('admin.Back to Inbox')</a>

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('admin.Folders')</h3>

                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="{{route('admin.message.inbox')}}"><i class="fa fa-inbox"></i> @lang('admin.Inbox')
                                    <span class="label label-primary pull-right">{{count(DB::table('messages')->get())}}</span></a></li>
                            <li><a href="{{route('admin.message.compose')}}"><i class="fa fa-envelope-o"></i> @lang('admin.Sent')
                                    <span class="label label-success pull-right">{{count(DB::table('messages')->where('send',1)->get())}}</span></a></li>
                            <li><a href="{{route('admin.message.inbox')}}"><i class="fa fa-file-text-o"></i> @lang('admin.Receive')
                                    <span class="label label-warning pull-right">{{count(DB::table('messages')->where('receive',1)->get())}}</span></a></li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('admin.Compose New Message')</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! Form::open(['url'=>url('admin/send-message'),'method'=>'POST','files'=>true]) !!}
                    <div class="box-body">
                        <div class="form-group">
                            <input class="form-control" placeholder="@lang('admin.To:')"  name="emailTo" value="{{$message->emailTo}}">
                        </div>
                        <div class="form-group">
                            {!! Form::select('type',['vendor'=>trans('web.Vendor')
                              ,'customer'=>trans('web.User'),'delivery'=>trans('web.Delivery')
                              ,'others'=>trans('web.Others')],$message->type,['class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('web.Select user type')]) !!}
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="@lang('admin.Subject:')" name="subject" value="{{$message->subject}}">
                        </div>
                        <div class="form-group">
                    <textarea id="compose-textarea" name="message" class="form-control" placeholder="@lang('admin.Messages')" style="height: 300px"></textarea>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> @lang('admin.Send')</button>
                        </div>
                        <a href="{{route('admin.message.inbox')}}" class="btn btn-default"><i class="fa fa-times"></i> @lang('admin.Cancel')</a>
                    </div>
                    <!-- /.box-footer -->
                    {!! Form::close() !!}
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>



@endsection




