@extends('backend.layouts.master')

@section('title',trans('admin.Profile'))

@section('content-header')
        <section class="content-header">

            <h1>
                @lang('admin.Profile')
                <small>@lang('admin.Profile')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>

                    <li class="active">@lang('admin.Profile')</li>
            </ol>
        </section>

@endsection()

@section('main-content')
    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                        <div class="box-body box-profile">
                            @if(empty($data->image))
                                <img class="profile-user-img img-responsive img-circle" src="{{Request::root().'/upload/images/default.png'}}"  alt="User profile picture">

                            @else
                                <img class="profile-user-img img-responsive img-circle" src="{{Request::root().'/upload/admin/'.$data->image}}" alt="User profile picture">

                            @endif

                            <h3 class="profile-username text-center">{{$data->name}}</h3>

                            <p class="text-muted text-center">@lang('admin.Member since') {{date('M-Y',strtotime($data->created_at))}}</p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b><i class="fa fa-user margin-r-5" aria-hidden="true"></i>@lang('admin.Full name')</b> <a class="pull-right">{{$data->firstName.' '.$data->lastName}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b><i class="fa fa-envelope margin-r-5" aria-hidden="true"></i>@lang('admin.E-mail')</b> <a class="pull-right">{{$data->email}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b><i class="fa fa-map-marker margin-r-5"></i>@lang('admin.Address')</b> <a class="pull-right">{{$data->address}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b><i class="fa fa-mobile margin-r-5"></i>@lang('admin.Phone')</b> <a class="pull-right">{{$data->phone}}</a>
                                </li>
                            </ul>
                        </div>

                <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <!-- About Me Box -->
            {{--  <div class="box box-primary">
                  <div class="box-header with-border">
                      <h3 class="box-title">@lang('admin.Center Me')</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                      <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

                      <p class="text-muted">
                          B.S. in Computer Science from the University of Tennessee at Knoxville
                      </p>

                      <hr>

                      <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

                      <p class="text-muted">Malibu, California</p>

                      <hr>

                      <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

                      <p>
                          <span class="label label-danger">UI Design</span>
                          <span class="label label-success">Coding</span>
                          <span class="label label-info">Javascript</span>
                          <span class="label label-warning">PHP</span>
                          <span class="label label-primary">Node.js</span>
                      </p>

                      <hr>

                      <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

                      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                  </div>
                  <!-- /.box-body -->
              </div>--}}
            <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab">@lang('admin.Update Profile')</a></li>
                        <li><a href="#timeline" data-toggle="tab">@lang('admin.Change password')</a></li>
                        {{-- <li><a href="#settings" data-toggle="tab">Settings</a></li>--}}
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="{!! route('admin.profile.update') ? 'activity' : '' !!}">
                            {!! Form::open(['url'=>route('admin.profile.update'),'method'=>'POST','class'=>'form-horizontal','files'=>true]) !!}
                            <input hidden name="id" value="{{$data->id}}">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">@lang('admin.First name')</label>

                                <div class="col-sm-10">
                                    <input type="text" name="firstName" class="form-control" id="inputName" value="{{$data->firstName}}" placeholder="@lang('admin.Full Name')">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">@lang('admin.Last name')</label>

                                <div class="col-sm-10">
                                    <input type="text" name="lastName" class="form-control" id="inputName" value="{{$data->lastName}}" placeholder="@lang('admin.Full Name')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">@lang('admin.E-mail')</label>

                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" value="{{$data->email}}" id="inputEmail" placeholder="@lang('admin.E-Mail')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">@lang('admin.Address')</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" value="{{$data->address}}" id="inputName" placeholder="@lang('admin.Address')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputExperience" class="col-sm-2 control-label">@lang('admin.Phone')</label>

                                <div class="col-sm-10">
                                    <input type="text"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           class="form-control" name="phone" value="{{$data->phone}}" id="inputName" placeholder="@lang('admin.Phone')">
                                </div>
                            </div>

                                @if(empty($data->image))
                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-2 control-label">@lang('admin.Avatar')</label>
                                        <div class="col-sm-10">
                                            <img src="{{Request::root().'/upload/images/default.png'}}"
                                                 style="height: 100px; width: 150px;" alt="..." class="img-circle image-preview">

                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-2 control-label">@lang('admin.Avatar')</label>
                                        <div class="col-sm-10">
                                            <img src="{{$data->imagePath}}"
                                                 style="height: 100px; width: 150px;" alt="..." class="img-circle image-preview">

                                        </div>
                                    </div>
                                @endif

                            <div class="form-group">
                                <label for="inputExperience" class="col-sm-2 control-label">@lang('admin.Avatar')</label>

                                <div class="col-sm-10">
                                    <input type="file" class="form-control image" name="image" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">@lang('admin.Update Data')</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="timeline">
                            {!! Form::open(['url'=>route('admin.change.password'),'method'=>'POST','class'=>'form-horizontal','file'=>true]) !!}
                            <input hidden name="id" value="{{$data->id}}">
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">@lang('admin.Old password')</label>

                                <div class="col-sm-10">
                                    <input type="password"  class="form-control" name="old_password" id="inputName" placeholder="@lang('admin.Old password')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">@lang('admin.New password')</label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="new_password" id="inputEmail" placeholder="@lang('admin.New password')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputName" class="col-sm-2 control-label">@lang('admin.Confirm password')</label>

                                <div class="col-sm-10">
                                    <input type="password" name="confirm_password" class="form-control" id="inputName" placeholder="@lang('admin.Retype password')">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">@lang('admin.Update password')</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- /.tab-pane -->

                    {{-- <div class="tab-pane" id="settings">
                         <form class="form-horizontal">
                             <div class="form-group">
                                 <label for="inputName" class="col-sm-2 control-label">Name</label>

                                 <div class="col-sm-10">
                                     <input type="email" class="form-control" id="inputName" placeholder="Name">
                                 </div>
                             </div>
                             <div class="form-group">
                                 <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                 <div class="col-sm-10">
                                     <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                 </div>
                             </div>
                             <div class="form-group">
                                 <label for="inputName" class="col-sm-2 control-label">Name</label>

                                 <div class="col-sm-10">
                                     <input type="text" class="form-control" id="inputName" placeholder="Name">
                                 </div>
                             </div>
                             <div class="form-group">
                                 <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                 <div class="col-sm-10">
                                     <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                 </div>
                             </div>
                             <div class="form-group">
                                 <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                                 <div class="col-sm-10">
                                     <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                 </div>
                             </div>
                             <div class="form-group">
                                 <div class="col-sm-offset-2 col-sm-10">
                                     <div class="checkbox">
                                         <label>
                                             <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                         </label>
                                     </div>
                                 </div>
                             </div>
                             <div class="form-group">
                                 <div class="col-sm-offset-2 col-sm-10">
                                     <button type="submit" class="btn btn-danger">Submit</button>
                                 </div>
                             </div>
                         </form>
                     </div>--}}
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


