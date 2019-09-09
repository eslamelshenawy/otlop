@if($from_user_type == 'user')
    {!! trans('admin.Customer') !!}
@elseif($from_user_type == 'delivery')
    @if(empty($from_id))
        {!! trans('admin.Not assign to delivery')!!}
    @else
        {!! trans('admin.Delivery') !!}

    @endif
@elseif($from_user_type == 'vendor')
    {!! trans('admin.Vendor') !!}

@elseif($from_user_type == 'admin')
    {!! trans('admin.Super admin') !!}
@endif