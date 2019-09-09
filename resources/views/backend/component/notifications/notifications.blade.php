
@if(Auth::guard('admin')->user()->userType == 'super_admin' || Auth::guard('admin')->user()->userType == 'admin')

    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            @if(Auth::guard('admin')->user()->unreadNotifications->count())
                <span class="label label-warning"> {{Auth::guard('admin')->user()->unreadNotifications->count()}} </span>

            @endif
        </a>
        <ul class="dropdown-menu">
            <li class="header">@lang('admin.You have') {{Auth::guard('admin')->user()->unreadNotifications->count()}} @lang('admin.notifications')</li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    @foreach(Auth::guard('admin')->user()->unreadNotifications as $value)
                        <li>
                            <a href="">
                                <i class="fa fa-users text-aqua"></i> {{$value->data['title']}}
                                <br>
                                <i class="fa fa-clock-o text-aqua"></i> {{date('d-m-Y',strtotime($value->data['date']['date']))}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="footer"><a href="{{route('admin.mark.all')}}">@lang('admin.Mark all a read')</a></li>
        </ul>
    </li>

 @endif