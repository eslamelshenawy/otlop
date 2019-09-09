@extends('frontend.layouts.master')

@section('title',trans('web.Restaurant Details'))

@section('content')

  @push('js')

    <script>
        $(document).ready(function () {
            var _token = $('input[name="_token"]').val();
            post_data('',_token);
            function post_data(id = "" , _token) {
                $.ajax({
                    url:"{!! route('web.load.data2') !!}",
                    method:"POST",
                    data:{id:id,_token:_token,resId:'{!! $restaurants->id !!}'},
                    success: function (data) {
                        $('#load_more_button').remove();
                        $('#post_data').append(data);
                    }
                });
            }
            $(document).on('click' ,'#load_more_button',function () {
                var id = $(this).data('id');
                $('#load_more_button').html('<b> @lang('web.Loading...') </b>');
                post_data(id,_token);
            })
        })
    </script>
    <script type="text/javascript">
        $(document).ready(function() {


            $('#submit').click(function(e){
                e.preventDefault();
                var _token = $('input[name="_token"]').val();
                var comment = $("#content").val();
                if( $('#star1').prop('checked'))
                {
                    var star =1;
                }
                if( $('#star2').prop('checked'))
                {
                    var star =2;
                }
                if( $('#star3').prop('checked'))
                {
                    var star =3;
                }
                if( $('#star4').prop('checked'))
                {
                    var star =4;
                }
                if( $('#star5').prop('checked'))
                {
                    var star =5;
                }
                var restaurant_id = $("#restaurant_id").val();
                $.ajax({
                    type: "POST",
                    url: "{!! route('web.review.restaurants') !!}",
                    dataType: "json",
                    data: {_token:_token,comment:comment, star:star,restaurant_id:restaurant_id},
                    success : function(data){
                        if (data.code === 200){
                            alert('{!! trans('web.Success') !!}' +data.msg);
                            window.location.href = "";
                        } else {
                            $(".display-error").html("<ul>"+data.msg+"</ul>");
                            $(".display-error").css("display","block");
                        }
                    }
                });


            });
        });
    </script>
    @endpush
  <div class="bread-crumbs-wrapper">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{!! route('web.home') !!}" title="" itemprop="url">@lang('web.Home')</a></li>
        <li class="breadcrumb-item active">@lang('web.Restaurant Details')</li>
      </ol>
    </div>
  </div>



  <!-- START Restaurant -->
  <section class="page_restau_details">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
          <div class="img bg_restau_detail">
            <div class="info_restaurant">
              <img src="{!! $restaurants->logoPath !!}" class="img-responsive" alt="">
              <h1>{!! $restaurants->translate(App::getLocale())->name !!}</h1>
              <p>{!! \App\Type::find($restaurants->type_id)->translate(App::getLocale())->name !!}</p>
             @if($rating > 0)
              <div class="rating mt-3">
                @for($i=0; $i<$rating; $i++)
                <i class="fa fa-star"></i>
                @endfor
                <span> &nbsp; @lang('web.Rating') ( {!! $rating !!} )</span>
              </div>
              @else
                <div class="rating mt-3">
                  <span> &nbsp; @lang('web.No Rating') </span>
                </div>
               @endif
              <div class="socail_restaurant">
                <a href=""><i class="fa fa-facebook"></i></a>
                <a href=""><i class="fa fa-twitter"></i></a>
                <a href=""><i class="fa fa-instagram"></i></a>
                <a href=""><i class="fa fa-youtube"></i></a>
                <a href=""><i class="fa fa-pinterest"></i></a>
                <a href=""><i class="fa fa-linkedin"></i></a>
                <a href=""><i class="fa fa-whatsapp"></i></a>
                <a href=""><i class="fa fa-skype"></i></a>
              </div>

              <a href="#card_comments" class="btn_comments"><i class="fa fa-comment"></i> 50 Reviews</a>

            </div>
          </div>
        </div>
      </div>

      <div class="details_restaurant mt-5">
        <div class="card">
          <div class="card_body">
            <p>
              Their best selling dishes are Economy Meal, Mutton Shank on Bedouin Style, Plain Pasta and
              Bedouin Soup, although they have a variety of dishes and meals to choose from, like Meals,
              Bedouin Food, Bedouin Food and Side Items.
              They have been reviewed 109 times by Talabat users, with a rating of 2.5.
            </p>
          </div>
        </div>

        <div id="card_comments" class="card">
          <div class="card_head">
            <h5 class="mt-3">Legleisah - Masaken Sheraton Reviews ({!! count($restaurants->ratingRestaurant()->get()) !!})</h5>
            @if(count($restaurants->ratingRestaurant()->get())>0)
            <div class="row view_rating_reviews">
              @foreach($restaurants->ratingRestaurant()->get() as $key =>$value)
              <div class="col-md-3">
                <div class="rating">
                  <strong>@lang('web.Rating') ({!! $value->rating !!})</strong>

                  @for($i=0; $i<$value->rating; $i++)
                    <i class="fa fa-star"></i>
                  @endfor

                  <p>{!! \App\User::find($value->user_id)->firstName.' '.\App\User::find($value->user_id)->lastName !!}</p>
                </div>
              </div>
             @endforeach
            </div>
              @else
              <div class="row view_rating_reviews">
              </div>
            @endif
          </div>
            {!! csrf_field() !!}
          <div  id="post_data" class="card_body">

          </div>
        </div>

        <div class="card card_add_comment">
          <div class="card_head">
            <h3 class="mt-3">@lang('web.Feedback')</h3>
          </div>
          <div class="card_body">
            <form role="form" id="contactForm">
              <div class="alert alert-danger display-error" style="display: none">
              </div>
              <div class="form-group">
                <h4 for="">@lang('web.How would you rate RAALEAT ?')</h4>
                <div class="starrating risingstar d-flex flex-row-reverse">
                  <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="5 star"></label>
                  <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 star"></label>
                  <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 star"></label>
                  <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 star"></label>
                  <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"></label>
                </div>
              </div>
              <div class="form-group">
                <label for="">@lang('web.Please provide any other feedback on your experience')</label>
                <textarea id="content" class=form-control placeholder="@lang('web.Write Your Reviews')"></textarea>
                <input id="restaurant_id" hidden value="{!! $restaurants->id !!}">
              </div>
              @if(Auth::guard('web')->user())
              <div class="form-group">
                <button type="submit" id="submit" class="btn btn-primary" > @lang('web.Review')</button>
              </div>
                @else
                <div class="form-group">
                  <a href="{!! route('web.get.login') !!}"  class="btn btn-primary" >@lang('web.Review')</a>
                </div>
              @endif
            </form>
          </div>
        </div>

      </div>

    </div>
  </section>
  <!-- //== END Restaurant -->
@endsection