@if(Auth::guard('admin')->user()->hasPermission('delete_location'))

<a href="{!! route('vendor.location.edit',$id) !!}" class="btn btn-info"> <i class="fa fa-edit"></i></a>

    @else
    <button type="button" class="btn btn-info disabled"><i class="fa fa-edit"></i></button>

@endif