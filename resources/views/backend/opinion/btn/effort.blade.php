@if($evaluation == 1)
    <td><span class="label label-danger">{!! $evaluation !!}</span></td>
@elseif($evaluation == 2)
    <td><span class="label label-warning">{!! $evaluation !!}</span></td>
@elseif($evaluation == 3)
    <td><span class="label label-primary">{!! $evaluation !!}</span></td>
@elseif($evaluation == 4)
    <td><span class="label label-info">{!! $evaluation !!}</span></td>
@elseif($evaluation == 5)
    <td><span class="label label-success">{!! $evaluation !!}</span></td>
@endif