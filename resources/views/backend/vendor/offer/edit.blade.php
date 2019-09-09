@extends('backend.layouts.master')

@section('title',trans('admin.Offer'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Offer')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('vendor.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('admin')->user()->hasPermission('read_offer'))
                <li ><a href="{{route('vendor.offer.index')}}"><i class="fa fa-list"></i> @lang('admin.View Offer')</a></li>
            @else
                <li ><a><i class="fa fa-list"></i> @lang('admin.View Offer')</a></li>
            @endif
            <li class="active">@lang('admin.Edit Offer')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    @push('js')
        <script type="text/javascript">
            $(document).ready(function () {
                @if($offer->menu_details_id)
                $.ajax({
                    url:'{!! route('vendor.offer.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{menu_details_id:'{!! $offer->menu_details_id !!}',select:'{!! $offer->price !!}'},
                    success: function (data) {
                        $('.oldPrice').addClass('hidden');
                        $('.state').html(data);
                    }
                });
                @endif
                $(document).on('change','.meal',function () {
                    var meal = $('.meal option:selected').val();
                    if (meal > 0){
                        $.ajax({
                            url:'{!! route('vendor.offer.create') !!}',
                            type:'get',
                            dataType:'html',
                            data:{menu_details_id:meal,select:''},
                            success: function (data) {
                                $('.state').html(data);
                                $('.oldPrice').addClass('hidden')
                            }
                        });
                    }
                    else{
                        $('.oldPrice').removeClass('hidden');
                        $('.state').html('');
                    }
                })
            })
        </script>
    @endpush
    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data offer')</h3>
                </div>
                {!! Form::open(['url'=>route('vendor.offer.update',$offer->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Meal name')</label>
                                <select class="form-control select2 meal" style="width: 100%;" name="menu_details_id">
                                    <option>@lang('admin.Select Meal name')</option>
                                    @foreach($menu as $key => $value)
                                        <option  value="{{$value->id}}" {!! $offer->menu_details_id  == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                $offer->status,['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                            </div>

                            <div class="form-group">
                                <label>@lang('admin.Start time')</label>
                                <div class="input-group">
                                    <input type="time" value="{!! $offer->fromTime !!}"
                                           name="fromTime"
                                           class="form-control">

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('admin.Start date')</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="date" class="form-control"
                                           name="fromDate" value="{!! $offer->fromDate !!}">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Original Price')</label>
                                <span class="state"></span>
                                <span class="oldPrice">
                                             <input type="text" readonly class="form-control"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                    id="exampleInputEmail1" placeholder="@lang('admin.Original Price')">
                                        </span>

                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Offer Price')</label>

                                <input type="number"  class="form-control" step="0.1" min="0" value="{!! $offer->price !!}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="price" id="exampleInputEmail1" placeholder="@lang('admin.Offer Price')">
                            </div>

                            <div class="form-group">
                                <label>@lang('admin.End time')</label>
                                <div class="input-group">
                                    <input type="time" value="{!! $offer->toTime !!}"
                                           name="toTime"
                                           class="form-control">

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('admin.End date')</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="date" class="form-control"
                                           name="toDate" value="{!! $offer->toDate !!}">
                                </div>
                                <!-- /.input group -->
                            </div>


                        </div>
                    </div>

                    <input type="hidden" name="id" value="{!! $offer->id !!}">




                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-edit"></i> @lang('admin.Edit')</button>
                </div>

                {!! Form::close() !!}
            </div>

        </div>

    </section>

@endsection


