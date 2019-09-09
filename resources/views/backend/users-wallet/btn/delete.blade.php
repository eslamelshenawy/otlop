<button type="button" class="btn btn-primary  btn btn-primary" data-toggle="modal"
        data-target="#deleteUsers{!! $id !!}"><i class="fa fa-money"></i></button>

<!-- Modal -->
<div id="deleteUsers{!! $id !!}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('admin.Wallet User') {!! $firstName .' '.$lastName !!}</h4>
            </div>
            <div class="modal-body">

                <div class="box box-primary">
                    <div class="box-body box-profile">
                        @if(empty($image))
                            <img class="profile-user-img img-responsive img-circle" src="{{asset('upload/images/default.png')}}"  alt="User profile picture">

                        @else
                            <img class="profile-user-img img-responsive img-circle" src="{{asset('upload/users/'.$image)}}" alt="User profile picture">

                        @endif

                        <h3 class="profile-username text-center">{{$firstName.' '.$lastName}}</h3>

                        <p class="text-muted text-center">@lang('admin.Member since') {{date('M-Y',strtotime($created_at))}}</p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><i class="fa fa-user margin-r-5" aria-hidden="true"></i>@lang('admin.Full name')</b> <a class="pull-right">{{ $firstName.' '.$lastName  }}</a>
                            </li>

                            <li class="list-group-item">
                                <b><i class="fa fa-money margin-r-5"></i>@lang('admin.Amount')</b> <a class="pull-right">{{\App\Wallet::where('user_id',$id)->value('account')}}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fa fa-life-saver margin-r-5"></i>@lang('admin.Status')</b> <a class="pull-right"><span class="label label-{!! \App\Wallet::where('user_id',$id)->value('status') == 1 ? 'success' : 'warning' !!}">
                                        {!! \App\Wallet::where('user_id',$id)->value('status') == 1 ? trans('admin.Active') : trans('admin.In-Active') !!}
                                        </span></a>
                            </li>
                        </ul>
                    </div>


                    <!-- /.box-body -->
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
            </div>
        </div>

    </div>
</div>

