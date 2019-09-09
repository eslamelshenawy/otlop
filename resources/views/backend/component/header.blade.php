<header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>{!! setting()->translate(App::getLocale())->name !!}</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>{!! setting()->translate(App::getLocale())->name !!}</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->

                @if(Auth::guard('admin')->user()->hasRole('super_admin'))
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            @if(DB::table('messages')->where('receive',1)->count('receive'))
                                <span class="label label-success">{{DB::table('messages')->where('receive',1)->count('receive')}}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">@lang('admin.You have') {{DB::table('messages')->where('receive',1)->count('receive')}} @lang('admin.messages')</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    @foreach(DB::table('messages')->where('receive',1)->get() as $key=>$value)
                                        <li><!-- start message -->
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="{{Request::root().'/upload/images/default.png'}}" class="img-circle" alt="User Image">
                                                </div>
                                                <h4>
                                                    {{$value->name}}
                                                    <small><i class="fa fa-clock-o"></i> {{date('d-M-Y',strtotime($value->created_at))}}</small>
                                                </h4>
                                                <p> {{$value->subject}}</p>
                                            </a>
                                        </li>
                                @endforeach
                                <!-- end message -->

                                </ul>
                            </li>
                            <li class="footer"><a href="{{route('admin.message.inbox')}}">@lang('admin.See All Messages')</a></li>
                        </ul>

                    </li>
                @endif
              {{--  <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell-o"></i>
                    <span class="label label-warning">10</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="header">You have 10 notifications</li>
                    <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">
                            <li>
                                <a href="#">
                                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                                    page and may cause design problems
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-users text-red"></i> 5 new members joined
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-user text-red"></i> You changed your username
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="footer"><a href="#">View all</a></li>
                </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">9</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 9 tasks</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Create a nice theme
                                            <small class="pull-right">40%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">40% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Some task I need to do
                                            <small class="pull-right">60%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Make beautiful transitions
                                            <small class="pull-right">80%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">80% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>--}}

              @include('backend.component.notifications.notifications')

                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>

                            <ul class="menu">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <li>
                                        <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                            {{ $properties['native'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @if(auth()->guard('admin')->user()->image)
                            <img src="{!! auth()->guard('admin')->user()->imagePath !!}" class="user-image" alt="User Image">
                        @else
                            <img src="{!! asset('upload/images/default.png') !!}" class="user-image" alt="User Image">
                        @endif
                        <span class="hidden-xs">  {!! auth()->guard('admin')->user()->firstName.' '.auth()->guard('admin')->user()->lastName  !!}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            @if(auth()->guard('admin')->user()->image)
                            <img src="{!! auth()->guard('admin')->user()->imagePath !!}" class="img-circle" alt="User Image">
                            @else
                                <img src="{!! asset('upload/images/default.png') !!}" class="img-circle" alt="User Image">
                            @endif

                            <p>
                                {!! auth()->guard('admin')->user()->firstName.' '.auth()->guard('admin')->user()->lastName  !!}
                                <small>@lang('admin.Member since') {!! date('M . Y',strtotime(auth()->guard('admin')->user()->created_at)) !!}</small>
                            </p>
                        </li>

                        <li class="user-footer">
                            @if(Auth::guard('admin')->user()->userType == 'super_admin' || Auth::guard('admin')->user()->userType == 'admin' )
                            <div class="pull-left">
                                <a href="{!! route('admin.profile') !!}" class="btn btn-default btn-flat">@lang('admin.Profile')</a>
                            </div>
                                @elseif(Auth::guard('admin')->user()->userType == 'vendor' || Auth::guard('admin')->user()->userType == 'user_vendor' )
                                    <div class="pull-left">
                                        <a href="{!! route('vendor.profile') !!}" class="btn btn-default btn-flat">@lang('admin.Profile')</a>
                                    </div>
                                @endif
                            <div class="pull-right">
                                <a href="{!! route('logout') !!}" class="btn btn-default btn-flat">@lang('admin.Sign out')</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>