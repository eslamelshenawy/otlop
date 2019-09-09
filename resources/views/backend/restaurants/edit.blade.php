@extends('backend.layouts.master')

@section('title',trans('admin.Restaurants'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Restaurants')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('admin')->user()->hasPermission('read_restaurants'))
                <li ><a href="{{route('admin.restaurants.index')}}"><i class="fa fa-users"></i> @lang('admin.View Restaurants')</a></li>
            @else
                <li ><a><i class="fa fa-users disabled"></i> @lang('admin.View Restaurants')</a></li>
            @endif
            <li class="active">@lang('admin.Edit Restaurant')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    @push('js')
        <script type="text/javascript">
            $(document).ready(function () {
                @if($restaurant->city_id)
                $.ajax({
                    url:'{!! route('admin.state.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{city_id:'{!! $restaurant->city_id !!}',select:'{!! $restaurant->state_id !!}'},
                    success: function (data) {
                        $('.state').html(data)
                    }
                });
                @endif
                $(document).on('change','.city_id',function () {
                    var city = $('.city_id option:selected').val();
                    if (city > 0){
                        $.ajax({
                            url:'{!! route('admin.state.create') !!}',
                            type:'get',
                            dataType:'html',
                            data:{city_id:city,select:''},
                            success: function (data) {
                                $('.state').html(data)
                            }
                        });
                    }
                    else{
                        $('.state').html('')
                    }
                })
            })
        </script>

    @endpush

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data restaurant')</h3>
                </div>
                {!! Form::open(['url'=>route('admin.restaurants.update',$restaurant->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'form_data']) !!}
                <div class="box-body">
                    <div class="row">
                        <input type="hidden" value="{!! $restaurant->id !!}" name="id">
                        <div class="col-md-6">

                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.resName')</label>
                                    <input type="text" class="form-control" value="{{$restaurant->translate($locale)->name}}" name="{{$locale}}[name]"
                                           id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.resName')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Name of restaurant owner')</label>
                                    <select class="form-control select2" style="width: 100%;" name="admin_id" disabled>
                                        @foreach($vendor as $key => $value)
                                                <option  value="{{$value->id}}"  {!! $value->id == $restaurant->admin_id ? 'selected' : '' !!}> {{$value->firstName. ' '.$value->lastName}}</option>
                                        @endforeach
                                    </select>
                                </div>




                                <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        @if($restaurant->image)
                                            <img src="{!! $restaurant->imagePath !!}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                                        @else
                                            <img src="{{asset('upload/images/default.png')}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">@lang('admin.Image')</label>
                                        <input type="file" class="form-control image" name="image" >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        @if($restaurant->logo)
                                        <img src="{!! $restaurant->logoPath !!}" style="width: 100px;" class="img-thumbnail logo-preview" alt="">
                                            @else
                                            <img src="{{asset('upload/images/default.png')}}" style="width: 100px;" class="img-thumbnail logo-preview" alt="">
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">@lang('admin.Logo')</label>
                                        <input type="file" class="form-control logo" name="logo" >
                                    </div>

                                </div>
                            </div>

                        </div>


                        <div class="col-md-6">

                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.resNameAddress')</label>
                                    <input type="text" class="form-control" value="{{$restaurant->translate($locale)->address}}" name="{{$locale}}[address]"
                                           id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.resNameAddress')">
                                </div>

                            @endforeach


                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.City name')</label>
                                <select class="form-control select2 city_id" style="width: 100%;" name="city_id">
                                    <option>@lang('admin.Select city name')</option>
                                    @foreach($city as $key => $value)
                                                <option  value="{{$value->id}}" {!! $value->id == $restaurant->city_id ? 'selected':'' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                                <div class="form-group">
                                    <label >@lang('admin.State Name')</label>
                                    <span class="state"></span>
                                </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Name of restaurant type')</label>
                                <select class="form-control select2" style="width: 100%;" name="type_id">
                                    <option>@lang('admin.Select name of restaurant type')</option>
                                    @foreach($type as $key => $value)
                                            <option  value="{{$value->id}}" {!! $value->id == $restaurant->type_id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                $restaurant->status,['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                            </div>


                            @if (auth()->user()->hasRole('super_admin'))
                            <div class="form-group">
                                    <label for="">هل هذا مطعم مميز ؟</label>

                                <select class="form-control" name="features_type" id="">

                                    <option value="0"  {{ $restaurant->features_type==0  ? 'selected' :'' }} > لا </option>
                                    <option value="1" {{ $restaurant->features_type==1  ? 'selected' :'' }}>نعم</option>

                                </select>
                            </div>

                        @endif


                        </div>

                    </div>


                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-edit"></i> @lang('admin.Edit')</button>
                </div>

                {!! Form::close() !!}
            </div>
            <!-- /.box -->



        </div>

    </section>

@endsection


