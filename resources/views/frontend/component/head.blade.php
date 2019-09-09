

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="{!! seo()->translate(App::getLocale())->description !!}" />
<meta name="keywords" content="{!! seo()->translate(App::getLocale())->keyword !!}" />
<title>{!! setting()->translate(App::getLocale())->name !!} | @yield('title')</title>
<link rel="shortcut icon" href="{!!  setting()->iconPath !!}" type="image/x-icon">
<meta name="csrf-token" content="{{ csrf_token() }}">


<link rel="stylesheet" href="{!! asset('frontend/assets/css/icons.min.css') !!}">
<link rel="stylesheet" href="{!! asset('frontend/assets/css/bootstrap.min.css') !!}">
<link rel="stylesheet" href="{!! asset('frontend/assets/css/bootstrap-4-spaces.css') !!}">
<link rel="stylesheet" href="{!! asset('frontend/assets/css/animate.min.css') !!}">
<link rel="stylesheet" href="{!! asset('frontend/assets/css/main.css') !!}">
@if(App::getLocale()=="ar")
    <link rel="stylesheet" href="{!! asset('frontend/assets/css/ar.css') !!}">

@else

@endif

<link rel="stylesheet" href="{!! asset('frontend/assets/css/red-color.css') !!}">
<link rel="stylesheet" href="{!! asset('frontend/assets/css/yellow-color.css') !!}">
<link rel="stylesheet" href="{!! asset('frontend/assets/css/main-style.css') !!}">
<link rel="stylesheet" href="{!! asset('frontend/assets/css/responsive.css') !!}">

<link rel="stylesheet" href="{!! asset('frontend/assets/css/media-query.css') !!}">

@stack('css')

