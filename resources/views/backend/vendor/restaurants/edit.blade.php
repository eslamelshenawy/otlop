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
                    url:'{!! route('vendor.location.create') !!}',
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
                            url:'{!! route('vendor.location.create') !!}',
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

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">@lang('admin.Update Restaurant')</a></li>
                <li><a href="#timeline" data-toggle="tab">@lang('admin.Working Hours')</a></li>
                {{-- <li><a href="#settings" data-toggle="tab">Settings</a></li>--}}
            </ul>
            <div class="tab-content">

                <div class="{!! route('vendor.restaurants.update',$restaurant->id) ? 'active' : ''!!} tab-pane" id="{!! route('vendor.restaurants.update',$restaurant->id) ? 'activity' : '' !!}">
                    {!! Form::open(['url'=>route('vendor.restaurants.update',$restaurant->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'form_data']) !!}
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
                                            @if($value->id == $restaurant->city_id)
                                                <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>
                                                @foreach(array_except($city , $key)  as $key => $value)
                                                    @if (old('guide_id') == $value->id)
                                                        <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>

                                                    @else
                                                        <option  value="{{$value->id}}"> {{$value->translate(App::getLocale())->name}}</option>
                                                    @endif
                                                @endforeach

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
                                            @if($value->id == $restaurant->type_id)
                                                <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>
                                                @foreach(array_except($type , $key)  as $key => $value)
                                                    @if (old('guide_id') == $value->id)
                                                        <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>

                                                    @else
                                                        <option  value="{{$value->id}}"> {{$value->translate(App::getLocale())->name}}</option>
                                                    @endif
                                                @endforeach

                                            @endif
                                        @endforeach
                                    </select>
                                </div>


                            </div>

                        </div>


                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-edit"></i> @lang('admin.Edit')</button>
                    </div>

                    {!! Form::close() !!}
                </div>

               {{-- <div class="tab-pane" id="timeline">
                    {!! Form::open(['url'=>route('vendor.working.hours'),'method'=>'post','files'=>true,'role'=>'form','id'=>'form_data']) !!}
                   <div class="box-body">
                       <div class="row">
                           <div class="col-md-6">

                               <input type="hidden" value="{!! $restaurant->id !!}" name="id">
                               <div class="form-group">
                                   <label for="exampleInputEmail1">@lang('admin.Menu name')</label>
                                   <select class="form-control select2" style="width: 100%;" name="day_id" >
                                       <option>@lang('admin.Select day work')</option>
                                       @foreach($day as $key => $value)
                                               <option  value="{{$value->id}}" {!! old('day_id') == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>

                                       @endforeach
                                   </select>
                               </div>

                               <div class="form-group">
                                   <label>@lang('admin.Start time')</label>
                                   <div class="input-group">
                                       <input type="time" value="{!! old('from') !!}"
                                              name="from"
                                              class="form-control">

                                       <div class="input-group-addon">
                                           <i class="fa fa-clock-o"></i>
                                       </div>
                                   </div>
                               </div>

                           </div>

                           <div class="col-md-6">
                               <div class="form-group">
                                   <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                   {!! Form::select('status',[1=>trans('admin.On'),0=>trans('admin.Off')],
                                   old('status'),['class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('admin.Select status')]) !!}

                               </div>

                               <div class="form-group">
                                   <label>@lang('admin.End time')</label>
                                   <div class="input-group">
                                       <input type="time" value="{!! old('to') !!}"
                                              name="to"
                                              class="form-control">

                                       <div class="input-group-addon">
                                           <i class="fa fa-clock-o"></i>
                                       </div>
                                   </div>
                               </div>

                           </div>
                       </div>
                   </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> @lang('admin.Save')</button>
                    </div>
                    {!! Form::close() !!}

                </div>--}}
            </div>
        </div>

    </section>

@endsection


