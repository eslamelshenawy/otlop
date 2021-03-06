

@if ( session()->has('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> @lang('admin.Success') !</h4>
        {{session('success')}}
    </div>
@endif


@if ( session()->has('error'))

    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> @lang('admin.Errors') !</h4>
        {{session('error')}}
    </div>
@endif

@if ( session()->has('warning'))

    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-warning"></i> @lang('admin.Warning') !</h4>
        {{session('warning')}}
    </div>
@endif

@if ( session()->has('info'))
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info"></i> @lang('admin.Information') !</h4>
        {{session('info')}}
    </div>
@endif