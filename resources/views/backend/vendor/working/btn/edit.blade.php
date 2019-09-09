@if(Auth::guard('admin')->user()->hasRole('vendor') &&Auth::guard('admin')->user()->userType == 'vendor' )

<a href="{!! route('vendor.working.edit',$id) !!}" class="btn btn-info"> <i class="fa fa-edit"></i></a>

    @else
    <button type="button" class="btn btn-info disabled"><i class="fa fa-edit"></i></button>

@endif