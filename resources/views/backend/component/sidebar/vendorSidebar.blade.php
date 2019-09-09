

<li class="{!! getUrl('vendor.home') !!}">
    <a href="{!! route('vendor.home') !!}">
        <i class="fa fa-dashboard"></i> <span>@lang('admin.Dashboard')</span>
    </a>
</li>

@if(Auth::guard('admin')->user()->hasRole('vendor') &&Auth::guard('admin')->user()->userType == 'vendor' )


<li class="treeview {!! getUrl('vendor.restaurants.edit') !!} {!! getUrl('vendor.working.index') !!} {!! getUrl('vendor.working.create') !!}" >
    <a href="#">
        <i class="fa fa-code"></i> <span>@lang('admin.Restaurants')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
            <li class="{!! getUrl('vendor.userVendor.index') !!}">
                <a href="{!! route('vendor.restaurants.edit',\DB::table('restaurants')
                ->where('admin_id',\Auth::guard('admin')->user()->id)->value('id')) !!}">
                    <i class="fa fa-list"></i> @lang('admin.Restaurants')</a></li>


        <li class="{!! getUrl('vendor.working.index') !!}"><a href="{{route('vendor.working.index')}}"><i class="fa fa-list-alt"></i> @lang('admin.View Working Hours')</a></li>
            <li class="{!! getUrl('vendor.working.create') !!}"><a href="{{route('vendor.working.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Working Hours')</a></li>

    </ul>
</li>


@endif

<li class="treeview {!! getUrl('vendor.userVendor.index') !!} {!! getUrl('vendor.userVendor.create') !!}" >
    <a href="#">
        <i class="fa fa-code"></i> <span>@lang('admin.Moderators')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_user_vendors'))
            <li class="{!! getUrl('vendor.userVendor.index') !!}"><a href="{{route('vendor.userVendor.index')}}"><i class="fa fa-list"></i> @lang('admin.View Moderators')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_user_vendors'))
            <li class="{!! getUrl('vendor.userVendor.create') !!}"><a href="{{route('vendor.userVendor.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Moderator')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('vendor.location.index') !!} {!! getUrl('vendor.location.create') !!}" >
    <a href="#">
        <i class="fa fa-code"></i> <span>@lang('admin.Location')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_location'))
            <li class="{!! getUrl('vendor.location.index') !!}"><a href="{{route('vendor.location.index')}}"><i class="fa fa-list"></i> @lang('admin.View Location')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_location'))
            <li class="{!! getUrl('vendor.location.create') !!}"><a href="{{route('vendor.location.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Location')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('vendor.menu.index') !!} {!! getUrl('vendor.menu.create') !!}" >
    <a href="#">
        <i class="fa fa-code"></i> <span>@lang('admin.Menu')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_menu'))
            <li class="{!! getUrl('vendor.menu.index') !!}"><a href="{{route('vendor.menu.index')}}"><i class="fa fa-list"></i> @lang('admin.View Menu')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_menu'))
            <li class="{!! getUrl('vendor.menu.create') !!}"><a href="{{route('vendor.menu.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Menu')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('vendor.menu-details.index') !!} {!! getUrl('vendor.menu-details.create') !!}" >
    <a href="#">
        <i class="fa fa-code"></i> <span>@lang('admin.Menu Details')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_menu'))
            <li class="{!! getUrl('vendor.menu-details.index') !!}"><a href="{{route('vendor.menu-details.index')}}"><i class="fa fa-eye"></i> @lang('admin.View')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_menu'))
            <li class="{!! getUrl('vendor.menu-details.create') !!}"><a href="{{route('vendor.menu-details.create')}}"><i class="fa fa-plus"></i> @lang('admin.Add')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('vendor.offer.index') !!} {!! getUrl('vendor.offer.create') !!}" >
    <a href="#">
        <i class="fa fa-code"></i> <span>@lang('admin.Offers')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_menu'))
            <li class="{!! getUrl('vendor.offer.index') !!}"><a href="{{route('vendor.offer.index')}}"><i class="fa fa-eye"></i> @lang('admin.View')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_menu'))
            <li class="{!! getUrl('vendor.offer.create') !!}"><a href="{{route('vendor.offer.create')}}"><i class="fa fa-plus"></i> @lang('admin.Add')</a></li>
        @endif
    </ul>
</li>

<li class="{!! getUrl('vendor.order') !!}">
    <a href="{!! route('vendor.order.index') !!}">
        <i class="fa fa-first-order"></i> <span>@lang('admin.Orders')</span>
    </a>
</li>


