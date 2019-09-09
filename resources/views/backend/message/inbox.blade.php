

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
                        <h3 class="box-title">@lang('admin.Inbox')</h3>


                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <!-- /.btn-group -->
                            {{--<button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                <input type="checkbox"  class="check_all" onclick="check_all()">
                            </button>

                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
--}}
                            <a href="{!! $message->appends(request()->query())->nextPageUrl() !!}" class="btn btn-default btn-sm"><i class="fa fa-home"></i></a>
                            <a href="{!! $message->appends(request()->query()) !!}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>

                            <div class="pull-right">
                                {{$message->count()}} / {{$message->total()}}
                                <div class="btn-group">
                                    <a href="{{$message->previousPageUrl()}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                                    <a href="{{$message->nextPageUrl()}}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>

                                </div>
                                <!-- /.btn-group -->
                            </div>
                            <!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <tbody>
                                @foreach($message as $key=>$value)
                                <tr>
                                   {{-- <td><input type="checkbox" name="item[]" class="item_checkbox" value="{!! $value->id !!}"></td>--}}
                                    @if($value->read == 1)
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                    @else
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-white"></i></a></td>
                                    @endif
                                    <td class="mailbox-name"><a href="{{route('admin.message.open',$value->id)}}">{{$value->name}}</a></td>
                                    <td class="mailbox-subject"><b>{{$value->subject}}</b> -{{$value->subject}}</td>
                                    @if($value->type == 'vendor')
                                        <td class="mailbox-date"><span class="label label-primary">@lang('web.Vendor')</span></td>
                                        @elseif($value->type == 'customer')
                                        <td class="mailbox-date"><span class="label label-success">@lang('web.User')</span></td>
                                        @elseif($value->type == 'others')
                                        <td class="mailbox-date"><span class="label label-info">@lang('web.Others')</span></td>
                                        @elseif($value->type =='delivery')
                                        <td class="mailbox-date"><span class="label label-warning">@lang('web.Delivery')</span></td>
                                        @endif
                                    <td class="mailbox-date">{{date('d-M-Y',strtotime($value->created_at))}}</td>
                                    <td><a href="{{route('admin.message.delete',$value->id)}}" class="btn-danger btn-sm"><i class="fa fa-trash-o"></i></a></td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-padding">
                        <div class="mailbox-controls">
                            <!-- Check all button -->

                            <!-- /.btn-group -->
                            <a href="{!! $message->appends(request()->query())->nextPageUrl() !!}" class="btn btn-default btn-sm"><i class="fa fa-home"></i></a>
                            <a href="{!! $message->appends(request()->query())!!}" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></a>
                            <div class="pull-right">
                                {{$message->count()}}  / {{$message->total()}}
                                <div class="btn-group">
                                    <a href="{!! $message->appends(request()->query())->previousPageUrl() !!}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                                    <a href="{!! $message->appends(request()->query())->nextPageUrl() !!}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                                </div>
                                <!-- /.btn-group -->
                            </div>
                            <!-- /.pull-right -->
                        </div>
                    </div>
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>




@endsection




