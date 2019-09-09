<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(auth()->guard('admin')->user()->image)
                    <img src="{!! auth()->guard('admin')->user()->imagePath !!}" class="img-circle" alt="User Image">
                @else
                    <img src="{!! asset('upload/images/default.png') !!}" class="img-circle" alt="User Image">
                @endif
            </div>
            <div class="pull-left info">
                <p>{!! auth()->guard('admin')->user()->firstName.' '.auth()->guard('admin')->user()->lastName !!}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">

            <li class="header">@lang('admin.MAIN NAVIGATION')</li>

            @if(Auth::guard('admin')->user()->userType == 'super_admin' || Auth::guard('admin')->user()->userType == 'admin')


                @include('backend.component.sidebar.adminSidebar')

                @elseif(Auth::guard('admin')->user()->userType == 'vendor' || Auth::guard('admin')->user()->userType == 'user_vendor' )

                @include('backend.component.sidebar.vendorSidebar')

                @endif

            <li>
                <a href="{!!route('logout') !!}">
                    <i class="fa fa-lock"></i> <span>@lang('admin.Logout')</span>
                </a>
            </li>


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
