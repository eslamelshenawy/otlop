@extends('backend.layouts.master')

@section('title',trans('admin.Menu Details'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Menu Details')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('vendor.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(auth()->guard('admin')->user()->hasPermission('read_menu'))
                <li ><a href="{{route('vendor.menu.index')}}"><i class="fa fa-users"></i> @lang('admin.View Menu Details')</a></li>
            @else
                <li ><a><i class="fa fa-meetup disabled"></i> @lang('admin.View Menu Details')</a></li>
            @endif
            <li class="active">@lang('admin.Create Menu Details')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    @push('js')

        <script>
            var room = 1;

            function addMenuDetails() {

                room++;
                var objTo = document.getElementById('addMenuDetails');
                var divtest = document.createElement("div");
                divtest.setAttribute("class", "form-group removeclass" + room);
                var rdiv = 'removeclass' + room;
                divtest.innerHTML =
                    '<div class="row"> <div class="col-md-6">'@foreach(config('translatable.locales') as $locale)+'<div class="form-group"><label>@lang('admin.'.$locale.'.mealName')</label> <input type="text" class="form-control" value="{!! old($locale.'.name') !!}" name="{{$locale}}[name][]" placeholder="@lang('admin.'.$locale.'.mealName')"> </div>'  @endforeach+'<div class="form-group"><label>@lang('admin.Price')</label><input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\'); this.value = this.value.replace(/(\\..*)\\./g, \'$1\');"   class="form-control" value="{{old('price')}}" name="price[]" min=0 step=0.1 placeholder="@lang('admin.Price')">  </div></div> <div class="col-md-6"> @foreach(config('translatable.locales') as $locale)<div class="form-group"><label >@lang('admin.'.$locale.'.mealDescription')</label><textarea  class="form-control"  name="{{$locale}}[description][]" placeholder="@lang('admin.'.$locale.'.mealDescription')">{{old($locale.'.description')}}</textarea>  </div>@endforeach</div>  </div>'
                objTo.appendChild(divtest)
            }

            function remove_education_fields(rid) {
                $('.removeclass' + rid).remove();
            }
            $(document).on('click','.addItems',function () {
               $('.')
            });
        </script>
        @endpush

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data Menu')</h3>
                </div>
                {!! Form::open(['url'=>route('vendor.menu-details.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Menu name')</label>
                                <select class="form-control select2" style="width: 100%;" name="menu_id">
                                    <option>@lang('admin.Select menu name')</option>
                                    @foreach($menu as $key => $value)
                                        @if (old('menu_id') == $value->id)
                                            <option  value="{{$value->id}}" selected> {{$value->translate(App::getLocale())->name}}</option>

                                        @else
                                            <option  value="{{$value->id}}"> {{$value->translate(App::getLocale())->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',['1'=>trans('admin.Active'),'2'=>trans('admin.In-Active')],
                                old('status'),['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <img src="{{asset('upload/images/default.png')}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Avatar')</label>
                                <input type="file" class="form-control image" name="image" >
                            </div>

                        </div>

                    </div>
                    <div class="form-group">
                        <button class="btn btn-success addItems" type="button" onclick="addMenuDetails();"><i class="fa fa-plus"></i></button>
                    </div>
                    <div id="addMenuDetails"></div>

                </div>


                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> @lang('admin.Save')</button>
                </div>

                {!! Form::close() !!}
            </div>

        </div>

    </section>

@endsection


