<td>
    @php
    $percent_number =(deliveryFees()['percentageOrder']*100 )+100;
    $amount = number_format($amount);
    $amount_net = (number_format($amount) *100)/number_format($percent_number);

@endphp
    {!!   ($amount_delivery - deliveryFees()['amountMan'])+ deliveryFees()['percentageOrder']*$amount_net !!}  S.R
</td>
