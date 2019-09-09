@if($status == 1)
    <td><span class="label label-success">@lang('admin.Active')</span></td>
@else
    <td><span class="label label-warning">@lang('admin.In-Active')</span></td>
@endif