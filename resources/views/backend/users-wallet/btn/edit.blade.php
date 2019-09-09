
<button type="button" class="btn btn-info  btn btn-info" data-toggle="modal"
        data-target="#deleteUsers{!! $id !!}"><i class="fa fa-eye"></i></button>

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
                                <b><i class="fa fa-address-card margin-r-5"></i>@lang('admin.E-mail')</b> <a class="pull-right">{{$email}}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fa fa-phone margin-r-5"></i>@lang('admin.Phone')</b> <a class="pull-right">{{$phone}}</a>
                            </li>

                            <li class="list-group-item">
                                <b><i class="fa fa-address-book-o margin-r-5"></i>@lang('admin.Address')</b> <a class="pull-right">{{$address}}</a>
                            </li>
                            {{--  <li class="list-group-item">
                                  <b><i class="fa fa-address-book margin-r-5"></i>@lang('admin.Address')</b> <a class="pull-right">{{\App\City::find($city_id)->translate(App::getLocale())->name}}</a>
                              </li>--}}
                            <li class="list-group-item">
                                <b><i class="fa fa-times margin-r-5"></i>@lang('admin.Member since')</b> <a class="pull-right">{{date('d-m-Y',strtotime($created_at))}}</a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fa fa-life-saver margin-r-5"></i>@lang('admin.Status')</b> <a class="pull-right"><span class="label label-{!! $status == 1 ? 'success' : 'warning' !!}">
                                        {!! $status == 1 ? trans('admin.Active') : trans('admin.In-Active') !!}
                                        </span></a>
                            </li>
                            {{--<li class="list-group-item">
                                <b><i class="fa fa-life-saver margin-r-5"></i>@lang('admin.Status')</b> <a class="pull-right"><span class="label label-{!! \App\Wallet::where('user_id',$id)->value('status') == 1 ? 'success' : 'warning' !!}">
                                        {!! \App\Wallet::where('user_id',$id)->value('status') == 1 ? trans('admin.Active') : trans('admin.In-Active') !!}
                                        </span></a>
                            </li>--}}
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

