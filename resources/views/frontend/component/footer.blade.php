

<footer>
    <div class="block top-padd80 bottom-padd80">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="footer-data">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="widget about_widget wow fadeIn" data-wow-delay="0.1s">
                                    <div class="logo">
                                        <h1 itemprop="headline"><a href="#" title="Home" itemprop="url"><img
                                                        src="{!! setting()->logoPath !!}" alt="logo.png" itemprop="image"></a>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-lg-3">
                                <div class="widget information_links wow fadeIn" data-wow-delay="0.2s">
                                    <h4 class="widget-title" itemprop="headline">@lang('web.Quick Links')</h4>
                                    <ul>
                                        <li><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
                                        <li><a href="{!! route('web.about.us') !!}" title="" itemprop="url">@lang('web.About Us')</a></li>
                                        <li><a href="{!! route('web.contact.us') !!}" title="" itemprop="url">@lang('web.Contact Us')</a></li>
                                        <li><a href="{!! route('web.term') !!}" title="" itemprop="url">@lang('web.Terms')</a></li>
                                        <li><a href="{!! route('web.faq') !!}" title="" itemprop="url">@lang('web.FAQ')</a></li>
                                        <li><a href="{!! route('web.privacy') !!}" title="" itemprop="url">@lang('web.Privacy')</a></li>
                                        <li><a href="#" title="sitemap.html" itemprop="url">@lang('web.SiteMap')</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-lg-6">
                                <div class="widget get_in_touch wow fadeIn" data-wow-delay="0.4s">
                                    <h4 class="widget-title" itemprop="headline">@lang('web.GET IN TOUCH')</h4>
                                    <ul>
                                        <li><i class="fa fa-map-marker"></i> {!! information()->address !!}
                                        </li>
                                        <li><i class="fa fa-phone"></i> {!! information()->support_call !!}</li>
                                        <li><i class="fa fa-envelope"></i> <a href="#" title=""
                                                                              itemprop="url">{!! information()->email !!}</a>
                                        </li>
                                    </ul>

                                    <ul class="social_footer">
                                        @foreach(socialMedia() as $key => $value)
                                        <li><a href="{!! $value->url !!}" target="_blank"><i class="fa fa-{!! $value->value !!}"></i></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><!-- Footer Data -->
                </div>
            </div>
        </div>
    </div>
</footer>


<div class="bottom-bar dark-bg text-center">
    <div class="container">
        <p itemprop="description">{!! setting()->copyright !!} <a class="red-clr" href="" title="" itemprop="url"
                                                 target="_blank">RAALEAT</a>.
            All Rights Reserved</p>
        <p itemprop="description"> Designed &amp; Developed <a class="red-clr" href="" title="" itemprop="url"
                                                               target="_blank">Ebaker</a>.</p>
    </div>
</div><!-- Bottom Bar -->


