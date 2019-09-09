@extends('backend.layouts.master')

@section('title',trans('admin.Menu'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Menu')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('vendor.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('admin')->user()->hasPermission('read_menu'))
                <li ><a href="{{route('vendor.menu-details.index')}}"><i class="fa fa-list"></i> @lang('admin.View Menu')</a></li>
            @else
                <li ><a><i class="fa fa-list"></i> @lang('admin.View Menu')</a></li>
            @endif
            <li class="active">@lang('admin.Edit City')</li>
        </ol>
    </section>
@endsection()

@section('main-content')
    @push('js')
        <script>
            var room = {!! count($otherData) !!};

            var maxInput = 9;

            function addMenuDetails() {

                var objTo = document.getElementById('addMenuDetails');
                var divtest = document.createElement("div");
                divtest.setAttribute("class", "form-group removeclass" + room);
                var rdiv = 'removeclass' + room;
                if (room < maxInput)
                {
                    divtest.innerHTML =
                        '<div class="row"> <div class="col-md-6">'@foreach(config('translatable.locales') as $locale)+'<div class="form-group"><label>@lang('admin.'.$locale.'.mealName')</label> <input type="text" class="form-control" value="{!! old($locale.'.title') !!}" name="{{$locale}}[title][]" placeholder="@lang('admin.'.$locale.'.mealName')"> </div>'  @endforeach+'</div> <div class="col-md-6"> <div class="form-group"><label>@lang('admin.Price')</label><input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\'); this.value = this.value.replace(/(\\..*)\\./g, \'$1\');"   class="form-control" value="{{old('other_data')}}" name="other_data[]" min=0 step=0.1 placeholder="@lang('admin.Price')">  </div></div> <div class="col-sm-1"><br/> <div class="form-group"> <button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');"> <i class="fa fa-minus"></i> </button> </div></div>  </div>'
                    objTo.appendChild(divtest);

                    room++;
                }
            }

            function remove_education_fields(rid) {
                $('.removeclass' + rid).remove();
                room--;
            }

            $(document).on('click', '.deleteOtherData', function(){
                var id = $(this).attr('id');
                if(confirm("{!! trans('admin.Are you sure you want to Delete this Item... ?') !!}"))
                {
                    $.ajax({
                        url:"{{route('vendor.otherData.delete.record')}}",
                        method:"get",
                        data:{id:id},
                        success:function(data)
                        {
                            alert(data);
                            $('#test-'+id).empty();
                            window.location.href = "";

                        }
                    })
                }
                else
                {
                    return false;
                }
            });



        </script>
        @endpush
    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data menu')</h3>
                </div>
                {!! Form::open(['url'=>route('vendor.menu-details.update',$menuDetails->id),'method'=>'put','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <input type="hidden" name="id" value="{!! $menuDetails->id !!}">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Menu name')</label>
                                <select class="form-control select2" style="width: 100%;" name="menu_id">
                                    <option>@lang('admin.Select menu name')</option>
                                    @foreach($menu as $key => $value)
                                            <option  value="{{$value->id}}" {!! $menuDetails->menu_id == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.mealName')</label>
                                    <input type="text" class="form-control" value="{{$menuDetails->translate($locale)->name}}" name="{{$locale}}[name]"
                                           id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.mealName')">
                                </div>

                            @endforeach

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Price')</label>
                                <input type="number" min="0" step="0.1" class="form-control" value="{{$menuDetails->price}}" name="price"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                       this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       id="exampleInputEmail1" placeholder="@lang('admin.Price')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                $menuDetails->status,['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                            </div>

                        </div>

                        <div class="col-md-6">

                            @foreach(config('translatable.locales') as $key => $locale)
                                <div class="form-group">
                                    <label >@lang('admin.'.$locale.'.mealDescription')</label>
                                    <textarea style="margin: 0px;width: 1324px; height: 90px;" class="form-control" name="{{$locale}}[description]"
                                              placeholder="@lang('admin.'.$locale.'.mealDescription')">{{$menuDetails->translate($locale)->description}}</textarea>
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Preparation time')</label>
                                    <input type="number" min="0" step="1" class="form-control" value="{{$menuDetails->period}}" name="period"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                       this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           id="exampleInputEmail1" placeholder="@lang('admin.Preparation time')">
                                </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">@lang('admin.Image')</label>
                                        <input type="file" class="form-control image" name="image" >
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <img src="{{$menuDetails->imagePath}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('admin.Other data')</h3>
                        </div>
                        @foreach($otherData as $others => $other)

                        <div class="row">

                            <div class="col-md-6">
                                @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label>@lang('admin.'.$locale.'.mealName')</label>
                                    <input type="text" class="form-control" value="{!! $other->translate($locale)->title !!}"
                                           name="{{$locale}}[title][]" placeholder="@lang('admin.'.$locale.'.mealName')">
                                </div>
                                @endforeach

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('admin.Price')</label>
                                    <input type="number"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           class="form-control" value="{{$other->price}}" name="other_data[]" min=0 step=0.1 placeholder="@lang('admin.Price')">
                                </div>
                            </div>

                            <div class="col-sm-1"><br/> <div class="form-group"> <a id="{{$other->id}}" class="btn btn-danger deleteOtherData" > <i class="fa fa-minus"></i> </a> </div></div>
                        </div>
                        @endforeach

                        <div id="addMenuDetails"></div>
                        <div class="form-group">
                            <button class="btn btn-success addItems" type="button" onclick="addMenuDetails();"><i class="fa fa-plus"></i></button>
                        </div>



                    </div>

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


