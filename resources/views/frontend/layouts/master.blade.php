<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.component.head')
</head>

<body itemscope>


<!-- header -->
@include('frontend.component.header')


@yield('content')

@if(Request::route()->getName() == route('web.home'))
<!-- section app -->
@include('frontend.component.section-app')

@endif

<!-- footer and  bottom-bar -->
@include('frontend.component.footer')


<!-- js -->
@include('frontend.component.script')
</body>

</html>