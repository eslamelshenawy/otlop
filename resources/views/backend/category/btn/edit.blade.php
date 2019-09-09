@if(Auth::guard('admin')->user()->hasPermission('delete_category'))

<a href="{!! route('admin.category.edit',$id) !!}" class="btn btn-info"> <i class="fa fa-edit"></i></a>

    @else
    <button type="button" class="btn btn-info disabled"><i class="fa fa-edit"></i></button>

@endif