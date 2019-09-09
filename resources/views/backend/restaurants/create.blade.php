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
            @if(auth()->user()->hasPermission('read_admins'))
                <li ><a href="{{route('admin.restaurants.index')}}"><i class="fa fa-users"></i> @lang('admin.View Restaurants')</a></li>
            @else
                <li ><a><i class="fa fa-users disabled"></i> @lang('admin.View Restaurants')</a></li>
            @endif
            <li class="active">@lang('admin.Create Restaurant')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    @push('js')
        <script type="text/javascript">
            $(document).ready(function () {
                @if(old('city_id'))
                $.ajax({
                    url:'{!! route('admin.state.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{city_id:'{!! old('city_id') !!}',select:'{!! old('state_id') !!}'},
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
                {!! Form::open(['url'=>routeAdmin('restaurants.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'form_data']) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.resName')</label>
                                    <input type="text" class="form-control" value="{{old($locale.'.name')}}" name="{{$locale}}[name]"
                                           id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.resName')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Name of restaurant owner')</label>
                                    <select class="form-control select2" style="width: 100%;" name="admin_id">
                                        <option>@lang('admin.Select name of restaurant owner')</option>
                                        @foreach($vendor as $key => $value)
                                            @if (old('admin_id') == $value->id)
                                                <option  value="{{$value->id}}" selected> {{$value->firstName. ' '.$value->lastName}}</option>

                                            @else
                                                <option  value="{{$value->id}}"> {{$value->firstName. ' '.$value->lastName}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>



                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <img src="{{asset('upload/images/default.png')}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">@lang('admin.Image')</label>
                                            <input type="file" class="form-control image" name="image" >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <img src="{{asset('upload/images/default.png')}}" style="width: 100px;" class="img-thumbnail logo-preview" alt="">
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
                                    <input type="text" class="form-control" value="{{old($locale.'.address')}}" name="{{$locale}}[address]"
                                           id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.resNameAddress')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.City name')</label>
                                    <select class="form-control select2 city_id" style="width: 100%;" name="city_id">
                                        <option>@lang('admin.Select city name')</option>
                                        @foreach($city as $key => $value)
                                            @if (old('city_id') == $value->id)
                                                <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>

                                            @else
                                                <option  value="{{$value->id}}"> {{$value->translate(App::getLocale())->name}}</option>
                                            @endif
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
                                            @if (old('type_id') == $value->id)
                                                <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>

                                            @else
                                                <option  value="{{$value->id}}"> {{$value->translate(App::getLocale())->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                               

                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                old('status'),['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                            </div>

                            @if (auth()->user()->hasRole('super_admin'))
                            <div class="form-group">
                                    <label for="">هل هذا مطعم مميز ؟</label>

                                <select class="form-control" name="features_type" id="">

                                    <option value="0"> لا </option>
                                    <option value="1">نعم</option>

                                </select>
                            </div>

                        @endif


                        </div>

                    </div>


                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> @lang('admin.Save')</button>
                </div>

                {!! Form::close() !!}
            </div>
            <!-- /.box -->



        </div>

    </section>

@endsection


