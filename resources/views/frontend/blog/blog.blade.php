@extends('frontend.layouts.master')

@section('title',trans('web.Blog'))

@section('content')

    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                <li class="breadcrumb-item active">@lang('web.Blog')</li>
            </ol>
        </div>
    </div>




    <!-- START Section Blog -->
    <section class="sec_blog">
        <div class="block gray-bg">
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
                                                                   alt="{!! $value->image !!}" itemprop="image"></a>
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
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- //== END START Section Blog -->


@endsection