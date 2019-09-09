@if(empty($order_id) )
{!! trans('admin.Paid in Restaurant') !!}
@else
  {!! trans('admin.OrderID#').$order_id !!}
@endif







