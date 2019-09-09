@extends('backend.layouts.master')

@section('title',trans('admin.Admins'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Admins')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(auth()->user()->hasPermission('read_admins'))
                <li ><a href="{{route('admin.admin.index')}}"><i class="fa fa-users"></i> @lang('admin.View Admin')</a></li>
            @else
                <li ><a><i class="fa fa-users disabled"></i> @lang('admin.View Admin')</a></li>
            @endif
            <li class="active">@lang('admin.Create Admin')</li>
        </ol>
    </section>
@endsection()

@section('main-content')

    @push('js')
        <script type="text/javascript">
            $('#selectUserType').change(function (e) {
                //console.log(e.target.value);
                var selectOption = e.target.value;
                if (selectOption === 'admin')
                {
                    $('.divPermission').removeClass('hidden');
                    $('.divPermission2').addClass('hidden')
                }
                else
                {
                    $('.divPermission').addClass('hidden');
                    $('.divPermission2').addClass('hidden')
                }

            })
        </script>

    @endpush

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data admin')</h3>
                </div>
                {!! Form::open(['url'=>routeAdmin('admin.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'form_data']) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.First name')</label>
                                <input type="text" class="form-control" autofocus value="{{old('firstName')}}"
                                       name="firstName" id="exampleInputEmail1" placeholder="@lang('admin.First name')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.E-mail')</label>
                                <input type="email" class="form-control" value="{{old('email')}}" name="email" id="exampleInputEmail1" placeholder="@lang('admin.E-mail')">
                            </div>

                            <div class="form-group">
                                <label for="password-field">@lang('admin.Password')  <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span></label>
                                <input type="password" name="password" class="form-control"
                                       id="password-field" placeholder="@lang('admin.Password')">
                            </div>

                            <div class="form-group">
                                <img src="{{asset('upload/images/default.png')}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Avatar')</label>
                                <input type="file" class="form-control image" name="image" >
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Address')</label>
                                <input type="text" class="form-control"  value="{{old('address')}}" name="address" id="exampleInputEmail1" placeholder="@lang('admin.Address')">
                            </div>


                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Last name')</label>
                                <input type="text" class="form-control"  value="{{old('lastName')}}" name="lastName" id="exampleInputEmail1" placeholder="@lang('admin.Last name')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Phone')</label>
                                <input type="text" class="form-control"  value="{{old('phone')}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="phone" id="exampleInputEmail1" placeholder="@lang('admin.Phone')">
                            </div>


                            <div class="form-group">
                                <label>@lang('admin.User type')</label>
                                {!! Form::select('userType',[
                                'admin'=>trans('admin.admin'),
                                'vendor'=>trans('admin.vendor'),
                                'delivery'=>trans('admin.delivery')
                                ],
                                old('userType'),
                                ['id'=>'selectUserType','class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('admin.Select user type')]) !!}

                            </div>


                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                old('status'),['class'=>'form-control select2','style'=>'width: 100%;']) !!}

                            </div>



                            <div  class="form-group divPermission hidden">
                                <label>@lang('admin.Permission')</label>
                                <div class="nav-tabs-custom">
                                    @php
                                        $models = ['admins','cities','vendors','restaurants','account_admin','question','privacy'
                                        ,'blog','category'];
                                        $maps = ['create','read','update','delete']
                                    @endphp
                                    <ul class="nav nav-tabs">
                                        @foreach($models as $key =>$model)
                                            <li class="{{$key == 0 ? 'active' : ''}}"><a href="#{{$model}}" data-toggle="tab">{{$model}}</a></li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">
                                        @foreach($models as $key =>$model)
                                            <div class="tab-pane {{$key == 0 ? 'active' : ''}}" id="{{$model}}">
                                                @foreach($maps as $index => $map)
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="permission[]" value="{{ $map.'_'.$model  }}"> {{$map}}
                                                        </label>
                                                    </div>
                                                @endforeach

                                            </div>

                                        @endforeach
                                    </div>
                                    <!-- /.tab-content -->
                                </div>

                            </div>


                              {{--  @if(old('userType') == 'admin')
                                <div  class="form-group divPermission2">
                                    <label>@lang('admin.Permission')</label>
                                    <div class="nav-tabs-custom">
                                        @php
                                            $models = ['admins','vendors'];
                                            $maps = ['create','read','update','delete']
                                        @endphp
                                        <ul class="nav nav-tabs">
                                            @foreach($models as $key =>$model)
                                                <li class="{{$key == 0 ? 'active' : ''}}"><a href="#{{$model}}" data-toggle="tab">{{$model}}</a></li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            @foreach($models as $key =>$model)
                                                <div class="tab-pane {{$key == 0 ? 'active' : ''}}" id="{{$model}}">
                                                    @foreach($maps as $index => $map)
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="permission[]" value="{{ $map.'_'.$model  }}"> {{$map}}
                                                            </label>
                                                        </div>
                                                    @endforeach

                                                </div>

                                            @endforeach
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>

                                </div>
                                @endif--}}



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


