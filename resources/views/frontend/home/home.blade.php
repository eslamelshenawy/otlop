@extends('frontend.layouts.master')

@section('title',trans('web.Home'))

@section('content')

    @push('js')

        <script type="text/javascript">
        $(document).ready(function () {
           // var city_id= $( "#city_id option:selected" ).val("");
           //  alert(city_id);
            @if(old('city_id'))
            $.ajax({
                url:'{!! route('web.home') !!}',
                type:'get',
                dataType:'html',
                data:{city_id:'{!! old('city_id') !!}',select:'{!! old('state_id') !!}'},
                success: function (data) {
                    $('.showData').removeClass('hidden');
                    $('.state').html(data);
                    $('.DataState').addClass('hidden');
                    $('#select_state').empty();
                    $("#select_state").trigger("chosen:updated");

                }
            });
            @endif
            $(document).on('change','.city_id',function () {
                var city = $('.city_id option:selected').val();
                $('#select_state').empty();
                $("#select_state").trigger("chosen:updated");

                if (city > 0){
                    $.ajax({
                        url:'{{url("stat")}}'+'/'+city,
                        type:'get',
                        success: function (data) {
                            $('#select_state').append( '<option value="">@lang('web.Select State')</option>' );

                            console.log(data);
                            console.log(data.length);
                            var i;
                            for (i = 0; i < data.length; ++i) {
                                console.log(data[i]);
                                $(data[i]).each(function(index, element){
                                    console.log("Iteration: " + element.name)
                                    $('#select_state').append( '<option value="'+element.id+'">'+element.name+'</option>' );
                                    $("#select_state").trigger("chosen:updated");


                                });
                            }



                        }
                    });
                }
                else{
                    $('.DataState').removeClass('hidden');
                    $('.showData').addClass('hidden');

                }
            })
        })
    </script>
    @endpush

    <!-- START Section Slider With Swaerch -->
    <section class="bg_overlay slider_home">
        <div class="block">
            <div style="background-image: url({!! url('frontend/assets/img/slider/slide-1.png') !!});" class="fixed-bg"></div>
            <div class="restaurant-searching text-center">
                <div class="restaurant-searching-inner">
                    <h2 itemprop="headline"><span> {!! setting()->translate(App::getLocale())->name !!} </span> <br> {!! $page->translate(App::getLocale())->title !!}</h2>
                    {!! Form::open(['url'=>route('web.restaurant.search'),'method'=>'post','class'=>'restaurant-search-form brd-rd2']) !!}
                        <h4>@lang('web.Choose your area to view nearby restaurants around you')</h4>
                        <div class="row mrg10">

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="select-wrp">
                                    <select class="select city_id"  name="city_id" >
                                        <option value="">@lang('web.Select City')</option>
                                        @foreach($city as $key =>$value)
                                            <option value="{!! $value->id !!}" {!! old('city_id') == $value->id ? 'selected' : '' !!}>{!! $value->translate(App::getLocale())->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                               <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="select-wrp">
                                    <select class="select " id="select_state"   >
                                        <option value="">@lang('web.Select State')</option>
                                    </select>
                                </div>
                            </div>


{{--                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 showData hidden">--}}
{{--                                <div class="select-wrp  ">--}}
{{--                                    <div class="state">--}}

{{--                                    </div>--}}

{{--                                </div>--}}


{{--                            </div>--}}


                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="select-wrp">
                                    <select class="select" name="type_id">
                                        <option value="0">@lang('web.Choose Type')</option>
                                        @foreach($type as $key =>$value)
                                            <option value="{!! $value->id !!}" {!! old('type_id') == $value->id ? 'selected' : '' !!}>{!! $value->translate(App::getLocale())->name !!}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="brd-rd2 red-bg btn_search_home" type="submit">@lang('web.Search')</button>
                            </div>
                        </div>
                   {!! Form::close() !!}

                </div>

            </div><!-- Restaurant Searching -->
        </div>

{{--        @include('frontend.component.message')--}}

    </section>
    <!-- //==END Section Slider With Swaerch -->

    <!-- START Section Services -->
    <section class="sec_services">
        <div class="block blackish low-opacity">
            <div class="container">
                <div class="text-center">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                            <div class="step-box wow fadeIn" data-wow-delay="0.2s">
                                <img src="{!! url('frontend/') !!}/assets/images/resource/setp-img1.png" alt="setp-img1.png" itemprop="image">
                                <div class="setp-box-inner">
                                    <h4 itemprop="headline">@lang('web.Find a restaurant')</h4>
                                </div>
                            </div><!-- Step Box -->
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                            <div class="step-box wow fadeIn" data-wow-delay="0.2s">
                                <img src="{!! url('frontend/') !!}/assets/images/resource/setp-img1.png" alt="setp-img1.png" itemprop="image">
                                <div class="setp-box-inner">
                                    <h4 itemprop="headline">@lang('web.Order your meal')</h4>
                                </div>
                            </div><!-- Step Box -->
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                            <div class="step-box wow fadeIn" data-wow-delay="0.2s">
                                <img src="{!! url('frontend/') !!}/assets/images/resource/setp-img1.png" alt="setp-img1.png" itemprop="image">
                                <div class="setp-box-inner">
                                    <h4 itemprop="headline">@lang('web.Enjoy your food')</h4>
                                </div>
                            </div><!-- Step Box -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //== END Section Services -->


    <!-- START Section About Us -->
    <section class="sec_about">
        <div class="block less-spacing grayish opc98">
            <!--<div class="fixed-bg" style="background-image: url(assets/images/parallax4.jpg);"></div>-->
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <div class="food-featured-post">
                            <div class="food-featured-post-thumb wow fadeIn" data-wow-delay="0.2s"
                                 style="visibility: visible; animation-delay: 0.2s; animation-name: fadeIn;">
                                <a href="#" title="" itemprop="url"><img src="{!! asset($page->imagePath) !!}"
                                                                         alt="featured-post-img.jpg" itemprop="image"></a>
                            </div>
                            <div class="food-featured-post-info wow fadeIn" data-wow-delay="0.2s"
                                 style="visibility: visible; animation-delay: 0.2s; animation-name: fadeIn;">
                                <h3 itemprop="headline"><a href="{{url('about-us')}}" title="" itemprop="url">{!! $page->translate(App::getLocale())->title !!}</a></h3>

                                {!! html_entity_decode($page->translate(App::getLocale())->description) !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //== END Section About Us -->

    <!-- START Section Top Restaurant -->
    <section class="top_restaurant">
        <div class="block">
            <div class="container">
                <div class="title1-wrapper text-center">
                    <div class="title1-inner">
                        <span>@lang('web.Featured Food')</span>
                        <h2 itemprop="headline">@lang('web.CHOOSE') &amp;@lang('web.ENJOY') </h2>
                    </div>
                </div>
                <div class="row remove-ext5">
                    @foreach ($restaurants as  $key =>$value)


                    <div class="col-md-3 col-sm-4">
                        <div class="popular-dish-box wow fadeIn" data-wow-delay="0.2s"
                             style="visibility: visible; animation-delay: 0.2s; animation-name: fadeIn;">
                            <div class="popular-dish-thumb">
                                <a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}" title="" itemprop="url">
                                    <img
                                            src="{!! $value->imagePath !!}" alt=" "  itemprop="image"></a>
                                <span class="post-rate yellow-bg brd-rd2"><i class="fa fa-cutlery  "></i>
                                    {!! \App\Type::find($value->type_id)->translate(App::getLocale())->name!!}</span>
                            </div>
                            <div class="popular-dish-info">
                                <h4 itemprop="headline">
                                    <a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}" title="" itemprop="url">

                                            {!! \App\Type::find($value->type_id)->translate(App::getLocale())->name!!}
                                    </a>
                                </h4>
                                <div class="restaurant-info">
                                    <div class="restaurant-info-inner">
                                        <h6 itemprop="headline"><a href="{!! route('web.details.restaurant',$value->translate(App::getLocale())->name) !!}" title=""
                                                                   itemprop="url">
                                                                   <i class="fa fa-fa-magic  "></i>   {!! $value->translate(App::getLocale())->name !!}
                                                                </a></h6>
                                        <span class="red-clr"><i class="fa fa-map-marker  "></i> {!! $value->translate(App::getLocale())->address !!} </span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Popular Dish Box -->
                    </div>
                    @endforeach


                </div>
            </div>
        </div>
    </section>
    <!-- //== END Section Top Restaurant -->



    <!-- START Section Blog -->
    <section class="sec_blog">
        <div class="block gray-bg">
            <div class="title1-wrapper text-center">
                <div class="title1-inner">
                    <h2 itemprop="headline">@lang('web.Articles')</h2>
                </div>
            </div>
            <div class="container">
                <div class="sec-box">
                    <div class="remove-ext">
                        <div class="row">
                            @foreach($blog as $key => $value)
                            <div class="col-md-4 col-sm-6 col-lg-4">
                                <div class="news-box wow fadeIn" data-wow-delay="0.1s"
                                     style="visibility: visible; animation-delay: 0.1s; animation-name: fadeIn;">
                                    <div class="news-thumb">
                                        <a class="brd-rd2" href="{!! route('web.get.blog.details',$value->translate(App::getLocale())->title) !!}" title=""
                                           itemprop="url"><img src="{!! $value->imagePath !!}"
                                                               alt="news-img1.jpg" itemprop="image"></a>
                                        <div class="news-btns">
                                            <a class="post-date red-bg" href="#" title="" itemprop="url">{!! date('d M',strtotime($value->created_at)) !!}</a>
                                            <a class="read-more" href="{!! route('web.get.blog.details',$value->translate(App::getLocale())->title) !!}"
                                               itemprop="url">@lang('web.READ MORE')</a>
                                        </div>
                                    </div>
                                    <div class="news-info">
                                        <h4 itemprop="headline"><a href="{!! route('web.get.blog.details',$value->translate(App::getLocale())->title) !!}"
                                                                   title="" itemprop="url">{!! $value->translate(App::getLocale())->title !!}</a>
                                        </h4>
                                        <p itemprop="description">
                                            {{ str_limit( preg_replace( "/\r|\n/", "", preg_replace("/&nbsp;/",'',strip_tags(htmlspecialchars_decode($value->translate(App::getLocale())->description))) ) ,70)  }}
                                           </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        <a href="{!! route('web.get.blog') !!}" class="btn btn-primary btn_more">@lang('web.More Blog')</a>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- //== END START Section Blog -->


@endsection