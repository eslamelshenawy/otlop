
@if(Auth::guard('admin')->user()->hasPermission('update_restaurants'))
<a href="{!! route('admin.types.edit',$id) !!}" class="btn btn-info"> <i class="fa fa-edit"></i></a>

    @else
    <button type="button" class="btn btn-info disabled"><i class="fa fa-edit"></i></button>
    @endif