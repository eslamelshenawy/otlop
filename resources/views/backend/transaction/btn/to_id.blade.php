@if($to_user_type == 'user')
{!! \App\User::find($to_id)->firstName.' '.\App\User::find($to_id)->lastName !!}
@elseif($to_user_type == 'delivery')
  @if($to_id == null)
    {!! trans('admin.Not assign to delivery')!!}

  @else
    {!! \App\Admin::find($to_id)->firstName.' '.\App\Admin::find($to_id)->lastName !!}

  @endif
@elseif($to_user_type == 'vendor')
  {!! \App\Admin::find(\App\Restaurant::find($to_id)->admin_id)->firstName.' '.\App\Admin::find(\App\Restaurant::find($to_id)->admin_id)->lastName !!}
@elseif($to_user_type == 'admin')
  {!! \App\Admin::find($to_id)->firstName.' '.\App\Admin::find($to_id)->lastName !!}
@endif







