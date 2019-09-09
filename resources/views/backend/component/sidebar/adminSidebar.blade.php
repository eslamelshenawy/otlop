


<li class="{!! getUrl('admin.home') !!}">
    <a href="{!! route('admin.home') !!}">
        <i class="fa fa-dashboard"></i> <span>@lang('admin.Dashboard')</span>
    </a>
</li>


<li class="{!! getUrl('admin.users') !!}">
    <a href="{!! route('admin.users') !!}">
        <i class="fa fa-users"></i> <span>@lang('admin.Users')</span>
    </a>
</li>

<li class="treeview {!! getUrl('admin.manage.shift') !!} {!! getUrl('admin.wallet.users') !!} {!! getUrl('admin.wallet.delivery') !!} " >
    <a href="#">
        <i class="fa fa-money"></i> <span>@lang('admin.Account')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_account_admin'))
            <li class="{!! getUrl('admin.manage.shift') !!}"><a href="{{route('admin.manage.shift')}}"><i class="fa fa-money"></i> @lang('admin.Edit manage delivery')</a></li>
            <li class="{!! getUrl('admin.wallet.users') !!}"><a href="{{route('admin.wallet.users')}}"><i class="fa fa-money"></i> @lang('admin.Wallet users')</a></li>
            <li class="{!! getUrl('admin.wallet.delivery') !!}"><a href="{{route('admin.wallet.delivery')}}"><i class="fa fa-money"></i> @lang('admin.Wallet delivery')</a></li>
            <li class="{!! getUrl('admin.transaction') !!}"><a href="{{route('admin.transaction')}}"><i class="fa fa-money"></i> @lang('admin.Transaction')</a></li>
            <li class="{!! getUrl('admin.organization.accounting') !!}"><a href="{{route('admin.organization.accounting')}}"><i class="fa fa-money"></i> @lang('admin.organization_accounting')</a></li>
            <li class="{!! getUrl('admin.balance.organization') !!}"><a href="{{route('admin.organization.balance')}}"><i class="fa fa-money"></i> @lang('admin.balance.organization')</a></li>
{{--            <li class="{!! getUrl('admin.balance.organization') !!}"><a href="{{route('admin.organization.balance')}}"><i class="fa fa-money"></i> @lang('admin.balance.organization')</a></li>--}}

        @endif
       {{-- @if(auth()->guard('admin')->user()->hasPermission('create_admins'))
            <li><a href="{{routeAdmin('admin.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Admin')</a></li>
        @endif--}}
    </ul>


<li class="treeview {!! getUrl('admin.package.index') !!} {!! getUrl('admin.package.create') !!}" >
    <a href="#">
        <i class="fa fa-pagelines"></i> <span>@lang('admin.Package')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_package'))
            <li class="{!! getUrl('admin.package.index') !!}"><a href="{{route('admin.package.index')}}"><i class="fa fa-eye"></i> @lang('admin.View')</a></li>

        @endif
        @if(auth()->guard('admin')->user()->hasPermission('read_package'))
            <li class="{!! getUrl('admin.package.create') !!}"><a href="{{route('admin.package.create')}}"><i class="fa fa-plus"></i> @lang('admin.Add')</a></li>

        @endif
    </ul>
</li>


<li class="treeview {!! getUrl('admin.admin.index') !!} {!! getUrl('admin.admin.create') !!} " >
    <a href="#">
        <i class="fa fa-user-o"></i> <span>@lang('admin.Admins')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_admins'))
            <li class="{!! getUrl('admin.admin.index') !!}"><a href="{{routeAdmin('admin.index')}}"><i class="fa fa-users"></i> @lang('admin.View Admin')</a></li>
            <li ><a href="{!! routeAdmin('admin.index') !!}?userType=admin"><i class="fa fa-user"></i> @lang('admin.Admin')</a></li>
            <li ><a href="{!! routeAdmin('admin.index') !!}?userType=delivery"><i class="fa fa-user"></i> @lang('admin.Delivery')</a></li>
            <li ><a href="{!! routeAdmin('admin.index') !!}?userType=vendor"><i class="fa fa-user"></i> @lang('admin.Vendor')</a></li>
            <li ><a href="{!! routeAdmin('admin.index') !!}?userType=user_vendor"><i class="fa fa-user"></i> @lang('admin.Employee Vendor')</a></li>

        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_admins'))
            <li class="{!! getUrl('admin.admin.create') !!}"><a href="{{routeAdmin('admin.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Admin')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('admin.city.index') !!} {!! getUrl('admin.city.create') !!}" >
    <a href="#">
        <i class="fa fa-code"></i> <span>@lang('admin.Cities')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_cities'))
            <li class="{!! getUrl('admin.city.index') !!}"><a href="{{routeAdmin('city.index')}}"><i class="fa fa-list"></i> @lang('admin.View Cities')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_cities'))
            <li class="{!! getUrl('admin.city.create') !!}"><a href="{{routeAdmin('city.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create City')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('admin.state.index') !!} {!! getUrl('admin.state.create') !!}" >
    <a href="#">
        <i class="fa fa-code"></i> <span>@lang('admin.State')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_cities'))
            <li class="{!! getUrl('admin.state.index') !!}"><a href="{{routeAdmin('state.index')}}"><i class="fa fa-list"></i> @lang('admin.View State')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_cities'))
            <li class="{!! getUrl('admin.state.create') !!}"><a href="{{routeAdmin('state.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create State')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('admin.types.index') !!} {!! getUrl('admin.types.create') !!}" >
    <a href="#">
        <i class="fa fa-calculator"></i> <span>@lang('admin.Type of restaurants')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_restaurants'))
            <li class="{!! getUrl('admin.types.index') !!}"><a href="{{routeAdmin('types.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Type')</a></li>
        @endif

        @if(auth()->guard('admin')->user()->hasPermission('create_restaurants'))
            <li class="{!! getUrl('admin.types.create') !!}"><a href="{{routeAdmin('types.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Type')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('admin.restaurants.index') !!} {!! getUrl('admin.restaurants.create') !!}" >
    <a href="#">
        <i class="fa fa-list-alt"></i> <span>@lang('admin.Restaurants')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_restaurants'))
            <li class="{!! getUrl('admin.restaurants.index') !!}"><a href="{{routeAdmin('restaurants.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Restaurants')</a></li>
        @endif

        @if(auth()->guard('admin')->user()->hasPermission('create_restaurants'))
            <li class="{!! getUrl('admin.restaurants.create') !!}"><a href="{{routeAdmin('restaurants.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Restaurant')</a></li>
        @endif
    </ul>
</li>
@if(Auth::guard('admin')->user()->hasRole('super_admin'))
<li class="{!! getUrl('admin.setting') !!}">
    <a href="{{route('admin.setting')}}">
        <i class="fa fa-cogs"></i> <span>@lang('admin.Setting & SEO')</span>
    </a>
</li>

<li class="treeview {!! getUrl('admin.page.index') !!}" >
    <a href="#">
        <i class="fa fa-pagelines"></i> <span>@lang('admin.Pages')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
            <li class="{!! getUrl('admin.page.index') !!}"><a href="{{route('admin.page.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Pages')</a></li>
    </ul>
</li>

<li class="{!! getUrl('admin.opinion.index') !!}">
    <a href="{{route('admin.opinion.index')}}">
        <i class="fa fa-cogs"></i> <span>@lang('admin.Opinions')</span>
    </a>
</li>

<li class="treeview {!! getUrl('admin.message.inbox') !!} {!! getUrl('admin.message.compose') !!}">
    <a href="#">
        <i class="fa fa-envelope"></i> <span>@lang('admin.Mailbox')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
            <li class="{!! getUrl('admin.message.inbox') !!} ">
                <a href="{{route('admin.message.inbox')}}">@lang('admin.Inbox')
                    <span class="pull-right-container">
                  <span class="label label-primary pull-right">{{count(DB::table('messages')->get())}}</span>
                    </span>
                </a>
            </li>
            <li class="{!! getUrl('admin.message.compose') !!}"><a href="{{route('admin.message.compose')}}">@lang('admin.Compose')</a></li>
    </ul>
</li>

@endif
<li class="treeview {!! getUrl('admin.category.index') !!} {!! getUrl('admin.category.create') !!}" >
    <a href="#">
        <i class="fa fa-pagelines"></i> <span>@lang('admin.Category')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_category'))
            <li class="{!! getUrl('admin.category.index') !!}"><a href="{{route('admin.category.index')}}"><i class="fa fa-eye"></i> @lang('admin.View')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_category'))
            <li class="{!! getUrl('admin.category.create') !!}"><a href="{{route('admin.category.create')}}"><i class="fa fa-plus"></i> @lang('admin.Add')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('admin.blog.index') !!} {!! getUrl('admin.blog.create') !!}" >
    <a href="#">
        <i class="fa fa-pagelines"></i> <span>@lang('admin.Blog')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_blog'))
            <li class="{!! getUrl('admin.blog.index') !!}"><a href="{{route('admin.blog.index')}}"><i class="fa fa-eye"></i> @lang('admin.View')</a></li>
        @endif
        @if(auth()->guard('admin')->user()->hasPermission('create_blog'))
            <li class="{!! getUrl('admin.blog.create') !!}"><a href="{{route('admin.blog.create')}}"><i class="fa fa-plus"></i> @lang('admin.Add')</a></li>
        @endif
    </ul>
</li>

<li class="treeview {!! getUrl('admin.question.index') !!} {!! getUrl('admin.question.create') !!}" >
    <a href="#">
        <i class="fa fa-pagelines"></i> <span>@lang('admin.Question')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_question'))
            <li class="{!! getUrl('admin.question.index') !!}"><a href="{{route('admin.question.index')}}"><i class="fa fa-eye"></i> @lang('admin.View')</a></li>
        @endif
            @if(auth()->guard('admin')->user()->hasPermission('read_question'))
                <li class="{!! getUrl('admin.question.create') !!}"><a href="{{route('admin.question.create')}}"><i class="fa fa-plus"></i> @lang('admin.Add')</a></li>
            @endif
    </ul>
</li>


<li class="treeview {!! getUrl('admin.privacy.index') !!} {!! getUrl('admin.privacy.create') !!}" >
    <a href="#">
        <i class="fa fa-pagelines"></i> <span>@lang('admin.Privacy')</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu" style="{{--{!! activeMenu('admin')[1] !!}--}}">
        @if(auth()->guard('admin')->user()->hasPermission('read_privacy'))
            <li class="{!! getUrl('admin.privacy.index') !!}"><a href="{{route('admin.privacy.index')}}"><i class="fa fa-eye"></i> @lang('admin.View')</a></li>

        @endif
        @if(auth()->guard('admin')->user()->hasPermission('read_privacy'))
            <li class="{!! getUrl('admin.privacy.create') !!}"><a href="{{route('admin.privacy.create')}}"><i class="fa fa-plus"></i> @lang('admin.Add')</a></li>

        @endif
    </ul>
</li>

<li class="{!! getUrl('admin.request-working.index') !!}">
    <a href="{!! route('admin.request-working.index') !!}">
        <i class="fa fa-first-order"></i> <span>@lang('admin.Request')</span>
    </a>
</li>

<li class="{!! getUrl('admin.order') !!}">
    <a href="{!! route('admin.order.index') !!}">
        <i class="fa fa-first-order"></i> <span>@lang('admin.Orders')</span>
    </a>
</li>






