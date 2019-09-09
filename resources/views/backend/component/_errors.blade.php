{{--@if ($errors->any())
<div class="alert alert-danger">
    <strong>@lang('site.alert') ! </strong>
@foreach ($errors->all() as $index=>$error)
            <p>{{$index+1}}-{{ $error }}</p>
            @endforeach
        </div>
@endif--}}

<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa fa-ban"></i> @lang('admin.Warning') !</h4>
    @foreach($errors->all() as $error)
        <h4>
            <li>
                {!! $error !!}
            </li>
        </h4>
    @endforeach
</div>