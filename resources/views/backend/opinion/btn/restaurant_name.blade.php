@php

    $te= \App\Restaurant::where('id',$restaurant_id)->first();
    $name= $te->translate(App::getLocale())->name;


@endphp

{{$name}}
