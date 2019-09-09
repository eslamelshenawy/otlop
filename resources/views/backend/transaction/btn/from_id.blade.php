@if($from_user_type == 'user')
  {!! \App\User::find($from_id)->firstName.' '.\App\User::find($from_id)->lastName !!}
@elseif($from_user_type == 'delivery')
  @if(empty($from_id))
    {!! trans('admin.Not assign to delivery')!!}
  @else
    {!! \App\Admin::find($from_id)->firstName.' '.\App\Admin::find($from_id)->lastName !!}

  @endif
@elseif($from_user_type == 'vendor')
  {!! \App\Admin::find(\App\Restaurant::find($from_id)->admin_id)->firstName.' '.\App\Admin::find(\App\Restaurant::find($from_id)->admin_id)->lastName !!}

@elseif($from_user_type == 'admin')
  {!! \App\Admin::find($from_id)->firstName.' '.\App\Admin::find($from_id)->lastName !!}
@endif







