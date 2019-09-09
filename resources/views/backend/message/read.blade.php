

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
                <a href="{{route('admin.message.compose')}}" class="btn btn-primary btn-block margin-bottom">@lang('admin.Compose')</a>

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
                        <h3 class="box-title">@lang('admin.Read Mail')</h3>

                        <div class="box-tools pull-right">
                            <a href="{{route('admin.message.open',$message->id-1)}}" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                            <a href="{{route('admin.message.open',$message->id+1)}}" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h3>{{$message->subject}}</h3>
                            <br/>
                            <h3>@lang('admin.User type') :
                                @if($message->type == 'vendor')
                                   <span class="label label-primary">@lang('web.Vendor')</span>
                                @elseif($message->type == 'customer')
                                   <span class="label label-success">@lang('web.User')</span>
                                @elseif($message->type == 'others')
                                   <span class="label label-info">@lang('web.Others')</span>
                                @elseif($message->type =='delivery')
                                   <span class="label label-warning">@lang('web.Delivery')</span>
                                @endif
                            </h3>
                            <br/>
                            <h5>@lang('admin.From'): {{$message->emailSend}}
                                <span class="mailbox-read-time pull-right">{{date('d-M-Y',strtotime($message->emailSend))}}  {{date('h:m:A',strtotime($message->emailSend))}}</span></h5>
                        </div>
                        <!-- /.mailbox-read-info -->

                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            {{preg_replace( "/\r|\n/", "", preg_replace("/&nbsp;/",'',strip_tags(htmlspecialchars_decode($message->message))) )}}

                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                    <!-- /.box-footer -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <a href="{{route('admin.message.delete',$message->id)}}" class="btn btn-default"><i class="fa fa-trash-o"></i> @lang('admin.Delete')</a>
                        </div>
                        <a href="{{route('admin.message.reply',$message->id)}}" class="btn btn-default"><i class="fa fa-reply"></i> @lang('admin.Reply')</a>

                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>



@endsection




