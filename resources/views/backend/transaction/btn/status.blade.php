@if($status == 'due')
    <td><span class="label label-success">@lang('admin.Due')</span></td>
@else
    <td><span class="label label-warning">@lang('admin.Paid')</span></td>
@endif