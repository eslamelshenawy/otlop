@extends('backend.layouts.master')

@section('title',trans('admin.Setting & SEO'))

@section('content-header')
    <section class="content-header">
        <h1>
            @lang('admin.Setting & SEO')
            <small>@lang('admin.Setting & SEO')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.Setting & SEO')</li>
        </ol>
    </section>
@endsection()

@section('main-content')
@push('js')
    <script type="text/javascript">
        var x = 1;
        $(document).on('click','.addInput',function () {
            var maxInput = 9;

            if (x < maxInput)
            {
                $('.divInput').append(' <div>' +
                    '            <div class="col-md-6">' +
                    '                <label >@lang('admin.Social name')</label>' +
                    '                <input type="text" class="form-control" name="value[]"' +
                    '                       placeholder="@lang('admin.Social name')">' +
                    '            </div>'+
                    '            <div class="col-md-6">' +
                    '                <label >@lang('admin.Url')</label>' +
                    '                <input type="text" class="form-control" name="url[]"' +
                    '                       placeholder="@lang('admin.Url')">' +
                    '            </div>' +
                    '            <div class="clearfix"></div>' +
                    '            <br>' +
                    '            <a href="#" class="removeInput btn btn-danger"> <i class="fa fa-trash"></i> </a>' +
                    '        </div>');
                x++;

            }

            return false;

        });

        $(document).on('click','.removeInput',function () {
            $(this).parent('div').remove();
            x--;
            return false;
        });

        $(document).on('change','.status',function () {
            var status = $('.status option:selected').val();
            if (status ==='close') {
                $('.reason').removeClass('hidden');
            }else {
                $('.reason').addClass('hidden');
            }
        })

    </script>

    @endpush
    <section class="content">

        <div class="row">
            <!-- /.col -->
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li><a class="active" href="#settings" data-toggle="tab">
                                <i class="fa fa-cog"></i></a><span>@lang('admin.Settings')</span></li>
                        <li><a  href="#seo" data-toggle="tab">
                                <i class="fa fa-search"></i></a><span>@lang('admin.SEO')</span></li>
                        <li><a  href="#contact_info" data-toggle="tab">
                                <i class="fa fa-envelope-o"></i></a><span>@lang('admin.Contact Info')</span></li>
                        <li><a  href="#social_media" data-toggle="tab">
                                <i class="fa fa-share-alt"></i></a><span>@lang('admin.Social Media')</span></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="settings">
                                {!! Form::open(['url'=>route('admin.post.setting'),'method'=>'POST','files'=>true,'class'=>'form-horizontal']) !!}

                            @foreach(config('translatable.locales') as $key => $locale)
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">@lang('admin.'.$locale.'.siteName')</label>

                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"  value="{{$setting->translate($locale)->name}}"
                                               name="{{$locale}}[name]" id="inputName" placeholder="@lang('admin.'.$locale.'.siteName')">
                                    </div>
                                </div>
                            @endforeach



                            <div class="form-group">
                                    <label for="inputEmail" class="col-sm-2 control-label">@lang('admin.Website Logo')</label>

                                    <div class="col-sm-8">
                                        <input type="file"  class="form-control logo" name="logo">
                                    </div>
                                    <div class="col-sm-2">
                                        @if($setting->logo)
                                            <img src="{{$setting->logoPath}}" alt="" class="profile-user-img img-responsive img-circle logo-preview">
                                        @else
                                            <img class="profile-user-img img-responsive img-circle logo-preview" src="{{asset('upload/images/default.png')}}"   alt="User profile picture">
                                        @endif

                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">@lang('admin.Website Favicon')</label>

                                    <div class="col-sm-8">
                                        <input type="file" class="form-control icon" name="icon" placeholder="@lang('admin.Website Favicon')">
                                    </div>
                                    <div class="col-sm-2">
                                        @if($setting->icon)
                                        <img src="{{$setting->iconPath}}" alt="" class="profile-user-img img-responsive img-circle icon-preview">
                                            @else
                                            <img class="profile-user-img img-responsive img-circle icon-preview" src="{{asset('upload/images/default.png')}}"  alt="User profile picture">
                                        @endif
                                    </div>

                                </div>

                            <div class="form-group">
                                <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.Copyright')</label>

                                <div class="col-sm-10">
                                    <input type="text" name="copyright" value="{{$setting->copyright}}"  class="form-control" id="inputSkills"
                                           placeholder="@lang('admin.Copyright')">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.Language Site')</label>

                                <div class="col-sm-10">
                                    {!! Form::select('lang',['en'=>trans('lang.english'),'ar'=>trans('lang.arabic')],
                               $setting->lang,['class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('admin.Select Language Website')]) !!}

                                </div>
                            </div>
                            <div class="form-group">

                                <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.Status')</label>

                                <div class="col-sm-10">
                                    {!! Form::select('status',['open'=>trans('admin.open'),'close'=>trans('admin.close')],
                               $setting->status,['class'=>'form-control select2 status','style'=>'width: 100%;']) !!}

                                </div>
                            </div>

                            <div class="form-group reason hidden">
                                <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.Message Maintenance')</label>

                                <div class="col-sm-10">

                                        <textarea style="margin: 0px;width: 1324px; height: 90px;" name="messageMaintenance" placeholder="@lang('admin.Message Maintenance')"
                                                  >{!! $setting->messageMaintenance  !!}</textarea>
                                </div>
                            </div>


                        @foreach(config('translatable.locales') as $key => $locale)
                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.'.$locale.'.siteDescription')</label>

                                    <div class="col-sm-10">

                                        <textarea style="margin: 0px;width: 1324px; height: 90px;"  name="{{$locale}}[description]" placeholder="@lang('admin.'.$locale.'.siteDescription')"
                                                  >{{$setting->translate($locale)->description}}</textarea>
                                    </div>
                                </div>
                            @endforeach


                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-success">@lang('admin.Submit')</button>
                                        <a href="{{route('admin.home')}}" class="btn btn-danger">@lang('admin.Cancel')</a>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>

                        <div class="tab-pane" id="seo">
                            {!! Form::open(['url'=>route('admin.post.seo'),'method'=>'POST','files'=>true,'class'=>'form-horizontal']) !!}


                            @foreach(config('translatable.locales') as $key => $locale)

                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.'.$locale.'.metaKeywords')</label>

                                    <div class="col-sm-10">

                                        <textarea style="margin: 0px;width: 1324px; height: 90px;" name="{{$locale}}[keyword]"
                                                  placeholder="@lang('admin.'.$locale.'.metaKeywords')"
                                                 >{{$seo->translate($locale)->keyword}}</textarea>
                                    </div>
                                </div>




                            @endforeach

                            @foreach(config('translatable.locales') as $key => $locale)
                            <div class="form-group">
                                <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.'.$locale.'.metaDescription')</label>

                                <div class="col-sm-10">

                                        <textarea style="margin: 0px;width: 1324px; height: 90px;" name="{{$locale}}[description]"
                                                  placeholder="@lang('admin.'.$locale.'.metaDescription')"
                                                 >{{$seo->translate($locale)->description}}</textarea>
                                </div>
                            </div>
                            @endforeach

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-success">@lang('admin.Submit')</button>
                                        <a href="{{route('admin.home')}}" class="btn btn-danger">@lang('admin.Cancel')</a>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>

                        <div class="tab-pane" id="contact_info">
                                {!! Form::open(['url'=>route('admin.post.contact_info'),'method'=>'POST','files'=>true,'class'=>'form-horizontal']) !!}

                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">@lang('admin.E-mail')</label>

                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" name="email" value="{{$contactInformation->email}}"
                                               id="inputName" placeholder="@lang('admin.E-mail')">
                                    </div>
                                </div>

                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">@lang('admin.Address')</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" value="{{$contactInformation->address}}"
                                           id="inputName" placeholder="@lang('admin.Address')">
                                </div>
                            </div>


                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.Phone')</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="phone" value="{{$contactInformation->phone}}"
                                               class="form-control" id="inputSkills" placeholder="@lang('admin.Phone')"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.Mobile')</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="mobile" value="{{$contactInformation->mobile}}"
                                               class="form-control" id="inputSkills" placeholder="@lang('admin.Mobile')"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputSkills" class="col-sm-2 control-label">@lang('admin.Support Call')</label>

                                    <div class="col-sm-10">
                                        <input type="text" name="support_call" value="{{$contactInformation->support_call}}"
                                               class="form-control" id="inputSkills"
                                               placeholder="@lang('admin.Support Call')"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-success">@lang('admin.Submit')</button>
                                        <a href="{{route('admin.home')}}" class="btn btn-danger">@lang('admin.Cancel')</a>
                                    </div>
                                </div>
                           {!! Form::close() !!}
                        </div>

                        <div class="tab-pane" id="social_media">
                                {!! Form::open(['url'=>route('admin.post.social_media'),'method'=>'POST','files'=>true,'class'=>'form-horizontal']) !!}

                            <div class="divInput col-md-12 col-lg-12 col-sm-12">

                                @foreach($socialMedia as $key =>$data)
                                    <div>

                                        <div class="col-md-6">
                                            <label >@lang('admin.Social name')</label>
                                            <input type="text" value="{!! $data->value !!}" class="form-control" name="value[]"
                                                   placeholder="@lang('admin.Social name')">
                                        </div>

                                        <div class="col-md-6">
                                            <label >@lang('admin.Url')</label>
                                            <input type="text" value="{!! $data->url !!}" class="form-control" name="url[]"
                                                   placeholder="@lang('admin.Url')">
                                        </div>
                                        <div class="clearfix"></div>
                                        <br>
                                        <a href="#" class="removeInput btn btn-danger"> <i class="fa fa-trash"></i> </a>


                                    </div>
                                    @endforeach


                            </div>
                            <div class="clearfix"></div>
                            <br>
                            <a href="#" class="addInput btn btn-info"> <i class="fa fa-plus"></i> </a>

                            <div class="clearfix"></div>
                            <br>



                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success">@lang('admin.Submit')</button>
                                        <a href="{{route('admin.home')}}" class="btn btn-danger">@lang('admin.Cancel')</a>
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>

                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>

@endsection


