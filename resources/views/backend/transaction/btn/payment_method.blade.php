@if($payment_method == 'card')
    {!! trans('admin.Visa') !!}
@elseif($payment_method == 'cash')
  {!! trans('admin.Cash') !!}
@elseif($payment_method == 'wallet')
  {!! trans('admin.Wallet') !!}
@endif