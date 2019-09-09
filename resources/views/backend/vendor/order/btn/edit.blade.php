<td><a href="" data-toggle="modal" data-target="#detailsUsers{!! $id !!}" class="btn btn-info">
     {{--   {!! \App\User::find($user_id)->firstName.' '.\App\User::find($user_id)->lastName !!} --}}<i class="fa fa-eye"></i>
    </a>

</td>




<div id="detailsUsers{!! $id !!}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        @if(empty(\App\User::find($user_id)->image))
                            <img class="profile-user-img img-responsive img-circle" src="{{asset('upload/images/default.png')}}"  alt="User profile picture">

                        @else
                            <img class="profile-user-img img-responsive img-circle" src="{{\App\User::find($user_id)->imagePath}}" alt="User profile picture">

                        @endif

                        <h3 class="profile-username text-center">{!! \App\User::find($user_id)->firstName.' '.\App\User::find($user_id)->lastName !!}</h3>

                        <p class="text-muted text-center">@lang('admin.Member since') {{date('M-Y',strtotime( \App\User::find($user_id)->created_at))}}</p>


                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">@lang('admin.Order details')</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table class="table table-striped">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>@lang('admin.Meal name')</th>
                                            <th>@lang('admin.Price')</th>
                                            <th>@lang('admin.Quantity')</th>
                                            <th>@lang('admin.Total price')</th>
                                        </tr>
                                        @foreach(\App\OrderDetails::where('order_id',$id)->get() as $key => $value)
                                        <tr>
                                            <td>{!! $key +1!!}.</td>
                                            <td>{!! \App\MenuDetails::find($value->menu_details_id)->translate(App::getLocale())->name !!}</td>
                                            <td>{!! $value->price !!} S.R </td>
                                            <td>{!! $value->qty !!} </td>
                                            <td>{!! $value->qty * $value->price!!} </td>
                                        </tr>
                                         @endforeach

                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
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
