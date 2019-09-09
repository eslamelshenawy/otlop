@if($image)
<img src="{!! asset('public/upload/meal/'.$image) !!}" width="60" class="img-circle" alt="User Image">

    @else
    <img src="{!! asset('upload/images/default.png') !!}" class="user-image" alt="User Image">

@endif