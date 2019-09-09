@extends('backend.layouts.master')

@section('title',trans('admin.balance'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Account balance')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.Create account balance')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <ul class="nav nav-tabs nav-justified md-tabs indigo" id="myTabJust" role="tablist">
        <li class="nav-item active">
            <a class="nav-link active" id="home-tab-just" data-toggle="tab" href="#order-total" role="tab" aria-controls="home-just"
               aria-selected="true">Total Order</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link active" id="home-tab-just" data-toggle="tab" href="#home-just" role="tab" aria-controls="home-just"
               aria-selected="true">Balance organization</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab-just" data-toggle="tab" href="#profile-just" role="tab" aria-controls="profile-just"
               aria-selected="false">Balance Restaurant</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab-just" data-toggle="tab" href="#contact-just" role="tab" aria-controls="contact-just"
               aria-selected="false">Balance Delivery:</a>
        </li>
    </ul>
    <div class="tab-content card pt-5" id="myTabContentJust">
        <div class="tab-pane fade show active in" id="order-total" role="tabpanel" aria-labelledby="home-tab-just">
            <p>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Balance</th>
                    <th scope="col">Cash</th>
                    <th scope="col">Card</th>
                    <th scope="col">Wallet</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row"> {{$sum_order_total}} S.R</th>
                    <td>{{$sum_order_total_cash}} S.R</td>
                    <td>{{$sum_order_total_card}} S.R</td>
                    <td>{{$sum_order_total_wallet}} S.R</td>
                </tr>
                </tbody>
            </table>
            </p>
        </div>
        <div class="tab-pane fade show active " id="home-just" role="tabpanel" aria-labelledby="home-tab-just">
            <p>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Balance</th>
                    <th scope="col">Cash</th>
                    <th scope="col">Card</th>
                    <th scope="col">Wallet</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row"> {{$sum_organize}} S.R</th>
                    <td>{{$sum_organize_cash}} S.R</td>
                    <td>{{$sum_organize_card}} S.R</td>
                    <td>{{$sum_organize_wallet}} S.R</td>
                </tr>
                </tbody>
            </table>
            </p>
        </div>
        <div class="tab-pane fade" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-just">
            <p>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Balance</th>
                    <th scope="col">Balance Permisson Exchange</th>
                    <th scope="col">Cash</th>
                    <th scope="col">Card</th>
                    <th scope="col">Wallet</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row"> {{$sum_restaurant - $sum_PermissonExchange}} S.R</th>
                    <td>{{$sum_PermissonExchange}} S.R</td>
                    <td>{{$sum_restaurant_cash}} S.R</td>
                    <td>{{$sum_restaurant_card}} S.R</td>
                    <td>{{$sum_restaurant_wallet}} S.R</td>
                </tr>
                </tbody>
            </table>

            </p>


            <p>

                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    Create Permisson
                </button>
            </p>
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                {{--create permission--}}

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create permission</h3>
                        </div>
                        <form action={{url('admin/permission_exchange')}} method="post">

                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Select restaurant</label>
                                    <select class="form-control select2" style="width: 100%;" id="restaurant_id" name="restaurant_id">
                                        <option>Select restaurant </option>
                                        @foreach($restaurant as $key => $value)
{{--                                            @if (old('city_id') == $value->id)--}}
{{--                                                <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>--}}

{{--                                            @else--}}
                                                <option  value="{{$value->id}}"> {{$value->translate(App::getLocale())->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Amount Exchange </label>
                                    <input type="number" min="0" class="form-control" value="" id="amout" name="amout"  placeholder="Amount Exchange">
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary" id="" ><i class="fa fa-save"></i> Save</button>
                            </div>

                        </form>
                    </div>

                {{--                    --}}
                </div>
            </div>

{{--       All  Permissons      --}}
            <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsepermission" aria-expanded="false" aria-controls="collapsepermission">
                   All  Permissons
                </button>
            </p>
            <div class="collapse" id="collapsepermission">
                <div class="card card-body">
                {{--All permission--}}

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">All  permission</h3>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Number</th>
                                <th scope="col">Name</th>
                                <th scope="col">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($PermissonExchange as $key =>  $PermissonExchang)
                                <tr>
                                <th scope="row"> {{$key+1}}</th>
                                <td>{{$PermissonExchang->name_restaurant}}</td>
                                <td>{{$PermissonExchang->amout}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{$PermissonExchange->links()}}

                    </div>

                {{--                    --}}
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="contact-just" role="tabpanel" aria-labelledby="contact-tab-just">
            <p>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Balance</th>
                    <th scope="col">Balance Permisson Exchange</th>
                    <th scope="col">Cash</th>
                    <th scope="col">Card</th>
                    <th scope="col">Wallet</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row"> {{$sum_delivery - $sum_PermissonExchange_deliver}} S.R</th>
                    <td>{{$sum_PermissonExchange_deliver}} S.R</td>
                    <td>{{$sum_delivery_cash}} S.R</td>
                    <td>{{$sum_delivery_card}} S.R</td>
                    <td>{{$sum_delivery_wallet}} S.R</td>
                </tr>
                </tbody>
            </table>

            </p>

            <p>

                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapsecreatepermission" aria-expanded="false" aria-controls="collapsecreatepermission">
                    Create Permisson
                </button>
            </p>
            <div class="collapse" id="collapsecreatepermission">
                <div class="card card-body">
                    {{--create permission--}}

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create permission</h3>
                        </div>
                        <form action={{url('admin/permission_exchange')}} method="post">

                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Select Delivery</label>
                                    <select class="form-control select2" style="width: 100%;" id="delivery_id" name="delivery_id">
                                        <option>Select Delivery </option>
                                        @foreach($delivery as $key => $value)
                                            {{--                                            @if (old('city_id') == $value->id)--}}
                                            {{--                                                <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>--}}

                                            {{--                                            @else--}}
                                            <option  value="{{$value->id}}"> {{$value->firstName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Amount Exchange </label>
                                    <input type="number" min="0" class="form-control" value="" id="amout" name="amout"  placeholder="Amount Exchange">
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary" id="" ><i class="fa fa-save"></i> Save</button>
                            </div>

                        </form>
                    </div>

                    {{--                    --}}
                </div>
            </div>

            {{--       All  Permissons      --}}
            <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapallsepermission" aria-expanded="false" aria-controls="collapallsepermission">
                    All  Permissons
                </button>
            </p>
            <div class="collapse" id="collapallsepermission">
                <div class="card card-body">
                    {{--All permission--}}

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">All  permission</h3>
                        </div>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Number</th>
                                <th scope="col">Name</th>
                                <th scope="col">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($PermissonExchange_deliver as $key =>  $PermissonExchang)
                                <tr>
                                    <th scope="row"> {{$key+1}}</th>
                                    <td>{{$PermissonExchang->firstName}}</td>
                                    <td>{{$PermissonExchang->amout}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{$PermissonExchange_deliver->links()}}

                    </div>

                    {{--                    --}}
                </div>
            </div>
        </div>
    </div>

{{--    <h1>Balances </h1>--}}

{{--    <h2 style="color:blue">Balance organization</h2>--}}

{{--    <div style="font-size: x-large; color: coral;">--}}
{{--    {{$sum_organize}} S.R--}}
{{--    </div >--}}


{{--    <h2 style="color:blue">Balance Restaurant :</h2>--}}
{{--    <div  style="font-size: x-large; color: coral;" >--}}
{{--        {{$sum_restaurant}} S.R--}}
{{--    </div >--}}

{{--    <h2 style="color:blue">Balance Delivery:</h2>--}}
{{--    <div   style="font-size: x-large; color: coral;">--}}
{{--        {{$sum_delivery}} S.R--}}
{{--    </div>--}}

    <script>
        $(document).ready(function() {
            $("#submit_amount").click(function(){
                var  amout =$("#amout").val();
                var  restaurant_id =$("#restaurant_id").val();
                // alert(amout);
                var data ={
                    "_token": "{{ csrf_token() }}",
                    "amout": amout,
                    "restaurant_id": restaurant_id,

                }
                console.log(data);
                $.ajax({
                    type:'POST',
                    url:"{{url('admin/permission_exchange')}}",
                    data:data,
                    success: function( msg ) {
                    }
                });

            });

        });

    </script>
@endsection


