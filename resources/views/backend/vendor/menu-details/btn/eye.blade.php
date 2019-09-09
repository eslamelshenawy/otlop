@if(auth()->guard('admin')->user()->hasPermission('read_menu'))
<button type="button" class="btn btn-primary  btn btn-primary" data-toggle="modal"
        data-target="#eyeMenuDetails{!! $id !!}"><i class="fa fa-eye"></i></button>

<!-- Modal -->
<div id="eyeMenuDetails{!! $id !!}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">@lang('admin.Info Meal Details') </h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-primary">
                            <div class="box-body box-profile">

                                <ul class="list-group list-group-unbordered">
                                    @foreach(\App\OtherDataMenu::where('menu_details_id',$id)->get() as $key => $value)
                                    <li class="list-group-item">
                                        <b><i class="fa fa-list margin-r-5" aria-hidden="true"></i>@lang('admin.Item name')</b> <a class="pull-right">{!! $value->translate(App::getLocale())->title !!}</a>
                                    </li>

                                        <li class="list-group-item">
                                            <b><i class="fa fa-money margin-r-5" aria-hidden="true"></i>@lang('admin.Price')</b> <a class="pull-right">{!! $value->price !!}</a>
                                        </li>
                                        @endforeach



                                </ul>
                            </div>


                            <!-- /.box-body -->
                        </div>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
            </div>
        </div>

    </div>
</div>

    @else
    <button type="button" class="btn btn-info btn btn-danger disabled" ><i class="fa fa-trash"></i></button>


@endif