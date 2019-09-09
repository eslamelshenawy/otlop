@if($userType == 'admin')
    <td><span class="label label-success">@lang('admin.Admin')</span></td>
@elseif($userType == 'vendor')
    <td><span class="label label-info">@lang('admin.Vendor')</span></td>
@elseif($userType == 'user_vendor')
    <td><span class="label label-warning">@lang('admin.Employee Vendor')</span></td>
@elseif($userType == 'delivery')
    <td><span class="label label-danger">@lang('admin.Delivery')</span></td>
@endif