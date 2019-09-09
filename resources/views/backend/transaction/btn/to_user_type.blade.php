@if($to_user_type == 'user')
    {!! trans('admin.Customer') !!}
@elseif($to_user_type == 'delivery')
    @if(empty($from_id))
        {!! trans('admin.Not assign to delivery')!!}
    @else
        {!! trans('admin.Delivery') !!}

    @endif
@elseif($to_user_type == 'vendor')
    {!! trans('admin.Vendor') !!}

@elseif($to_user_type == 'admin')
    {!! trans('admin.Super admin') !!}
@endif