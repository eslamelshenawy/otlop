@if(auth()->guard('admin')->user()->hasRole('super_admin'))
    <button type="button" class="btn btn-info  btn btn-info" data-toggle="modal"
            data-target="#eyeOpinion{!! $id !!}"><i class="fa fa-eye"></i></button>

    <!-- Modal -->
    <div id="eyeOpinion{!! $id !!}" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('admin.Message')</h4>
                </div>
                <div class="modal-body">
                    <h4 style="word-wrap: break-spaces">

                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" >@lang('admin.Yes')</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                </div>
            </div>

        </div>
    </div>

@else
    <button type="button" class="btn btn-info btn btn-danger disabled" ><i class="fa fa-trash"></i></button>


@endif