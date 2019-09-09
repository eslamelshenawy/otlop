@if(auth()->guard('admin')->user()->hasPermission('delete_menu'))
<button type="button" class="btn btn-info  btn btn-danger" data-toggle="modal"
        data-target="#deleteOffer{!! $id !!}"><i class="fa fa-trash"></i></button>

<!-- Modal -->
<div id="deleteOffer{!! $id !!}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('admin.Delete')</h4>
            </div>
            {!! Form::open(['route'=>['vendor.offer.destroy',$id],'method'=>'delete']) !!}
            <div class="modal-body">
                <h4>@lang('admin.Are your sure delete this') {!! \App\MenuDetails::find($menu_details_id)->translate(App::getLocale())->name !!}</h4>
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