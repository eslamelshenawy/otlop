@if(Auth::guard('admin')->user()->hasRole('vendor') &&Auth::guard('admin')->user()->userType == 'vendor' )
<button type="button" class="btn btn-info  btn btn-danger" data-toggle="modal"
        data-target="#deleteMenu{!! $id !!}"><i class="fa fa-trash"></i></button>

<!-- Modal -->
<div id="deleteMenu{!! $id !!}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('admin.Delete')</h4>
            </div>
            {!! Form::open(['route'=>['vendor.working.destroy',$id],'method'=>'delete']) !!}
            <div class="modal-body">
                <h4>@lang('admin.Are your sure delete this') {!! \App\Day::find($day_id)->translate(App::getLocale())->name !!}</h4>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" >@lang('admin.Yes')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
            </div>
            {!! Form::close() !!}
        </div>

    </div>
</div>

    @else
    <button type="button" class="btn btn-info btn btn-danger disabled" ><i class="fa fa-trash"></i></button>


@endif