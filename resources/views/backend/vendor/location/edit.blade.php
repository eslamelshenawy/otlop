@extends('backend.layouts.master')

@section('title',trans('admin.Location'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Location')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('vendor.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('admin')->user()->hasPermission('read_location'))
                <li ><a href="{{route('vendor.location.index')}}"><i class="fa fa-list"></i> @lang('admin.View Location')</a></li>
            @else
                <li ><a><i class="fa fa-list"></i> @lang('admin.View Location')</a></li>
            @endif
            <li class="active">@lang('admin.Edit Location')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    @push('js')
        <script type="text/javascript"    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAl4ojvZ2izKtlCvqX14FQCWAzCBmq4Wgk&callback=initMap&libraries=places"></script>
        <script type="text/javascript" src='{!! asset('backend/dist/js/locationpicker.jquery.js') !!}'></script>
        <?php
        $lat = !empty($location->lat) ? $location->lat : 29.339867091112144;
        $lng = !empty($location->lng) ? $location->lng : 30.974337577819824;

        ?>
        <script>
            /*       $('#us1').locationpicker({
                        location: {
                            latitude: 29.339867091112144,
                            longitude: 30.974337577819824
                        },
                        radius: 300,
                        markerIcon: 'http://www.iconsdb.com/icons/preview/tropical-blue/map-marker-2-xl.png',
                        inputBinding: {
                            latitudeInput: $('#lat'),
                            longitudeInput: $('#lng'),
                          //  radiusInput: $('#us2-radius'),
                            locationNameInput: $('#address')
                        }

                    });*/
            $('#us1').locationpicker({
                location: {
                    latitude: {!! $location->lat !!},
                    longitude: {!! $location->lng !!}
                },
                radius: 300,
                inputBinding: {
                    latitudeInput: $('#lat'),
                    longitudeInput: $('#lng'),
                    //radiusInput: $('#us3-radius'),
                    locationNameInput: $('#address')
                },
                // question when user change location on map
                /*enableAutocomplete: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                }*/
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                @if($location->city_id)
                $.ajax({
                    url:'{!! route('vendor.location.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{city_id:'{!! $location->city_id !!}',select:'{!! $location->state_id !!}'},
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

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data location')</h3>
                </div>
                {!! Form::open(['url'=>route('vendor.location.update',$location->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.City name')</label>
                                <select class="form-control select2 city_id" style="width: 100%;" name="city_id">
                                    <option>@lang('admin.Select city name')</option>
                                    @foreach($city as $key => $value)
                                        @if($value->id == $location->city_id)
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
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                $location->status,['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                            </div>

                            <div class="form-group">
                                <label >@lang('admin.Address')</label>
                                <input type="text" class="form-control" name="address" value="{!! $location->address !!}"
                                       id="address" placeholder="@lang('admin.Address')">
                            </div>


                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label >@lang('admin.Search Location')</label>
                                <input type="text" class="form-control" name="search" value="{!! old('search') !!}"
                                       id="search" placeholder="@lang('admin.Search Location')">
                            </div>



                            <div class="form-group">
                                <div id="us1" style="width: 100%; height: 400px; !important;"></div>
                            </div>



                            <input type="hidden"  class="form-control"  name="lat" value="{!! $lat !!}"
                                   id="lat" placeholder="@lang('admin.Lat')">

                            <input type="hidden" class="form-control" name="lng" value="{!! $lng !!}"
                                   id="lng" placeholder="@lang('admin.Lng')">

                            <input type="hidden"  name="locationId" value="{!! $location->id !!}">





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


