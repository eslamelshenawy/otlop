@if(Auth::guard('admin')->user()->hasPermission('delete_restaurants'))
<button type="button" class="btn btn-info  btn btn-danger" data-toggle="modal"
        data-target="#deleteType{!! $id !!}"><i class="fa fa-trash"></i></button>

<!-- Modal -->
<div id="deleteType{!! $id !!}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">@lang('admin.Delete')</h4>
            </div>
            {!! Form::open(['route'=>['admin.types.destroy',$id],'method'=>'delete','id'=>'formABC']) !!}
            <div class="modal-body">
                <h4>@lang('admin.Are your sure delete this') {!! $name !!}</h4>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSubmit" class="btn btn-danger" >@lang('admin.Yes')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
            </div>
            {!! Form::close() !!}
        </div>

    </div>
</div>
    @else
    <button type="button" class="btn btn-danger disabled"><i class="fa fa-trash"></i></button>

@endif