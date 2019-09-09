@if($rating == 1)
    <td><span class="label label-danger">@lang('admin.Not at all Satisfied')</span></td>
@elseif($rating == 2)
    <td><span class="label label-warning">@lang('admin.Slightly Satisfied')</span></td>
@elseif($rating == 3)
    <td><span class="label label-primary">@lang('admin.Moderately Satisfied')</span></td>
@elseif($rating == 4)
    <td><span class="label label-info">@lang('admin.Quite Satisfied')</span></td>
@elseif($rating == 5)
    <td><span class="label label-success">@lang('admin.Extremely Satisfied')</span></td>
@endif