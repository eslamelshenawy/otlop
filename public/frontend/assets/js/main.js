/*global $, alert, console*/

$(document).ready(function () {
  'use strict';

  //===== Profile Image Upload =====*/
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#profile-display').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#profile-upload").on("change", function () {
    readURL(this);
  });

  //===== Dropdown Anmiation =====// 
  var drop = $('.gallery-info-btns > a');
  $('.gallery-info-btns').each(function () {
    var delay = 0;
    $(this).find(drop).each(function () {
      $(this).css({
        transitionDelay: delay + 'ms'
      });
      delay += 100;
    });
  });

  new WOW().init();

  $('.rating-wrapper > a').on('click', function () {
    $(this).next('.rate-share').toggleClass('active');
    return false;
  });

  //===== Login Popup Script =====//
  $('.log-popup-btn').on('click', function () {
    $('html').addClass('log-popup-active');
    return false;
  });

  $('.log-close-btn').on('click', function () {
    $('html').removeClass('log-popup-active');
    return false;
  });

  //===== Sign Up Popup Script =====//
  $('.sign-popup-btn').on('click', function () {
    $('html').addClass('sign-popup-active');
    return false;
  });

  $('.sign-close-btn').on('click', function () {
    $('html').removeClass('sign-popup-active');
    return false;
  });

  //===== Newsletter Popup Script =====//
  $('a.close-btn').on('click', function () {
    $('.newsletter-popup-wrapper').fadeOut('slow');
    return false;
  });

  $('a.remove-noti').on('click', function () {
    $(this).parent().slideUp('slow');
    return false;
  });

  $('a.track-close').on('click', function () {
    $('.track-order-popup').fadeOut('slow');
    return false;
  });

  //===== Order Popup Script =====//
  $('.order-popup-btn').on('click', function () {
    $('html').addClass('order-popup-active');
    return false;
  });

  $('a.close-buyer').on('click', function () {
    $('html').removeClass('order-popup-active');
    return false;
  });

  //===== Cash Method Popup Script =====//
  /*   $('.cash-popup-btn').on('click', function () {
      $('html').addClass('cash-method-popup-active');
    });

    $('.cash-method a.payment-close-btn').on('click', function () {
      $('html').removeClass('cash-method-popup-active');
      return false;
    }); */

  //===== Card Method Popup Script =====//
  /* $('.card-popup-btn').on('click', function () {
    $('html').addClass('card-method-popup-active');
  });

  $('.card-method a.payment-close-btn').on('click', function () {
    $('html').removeClass('card-method-popup-active');
    return false;
  }); */

  //===== Thanks Message Popup Script =====//
  $('.confrm-order-btn > a').on('click', function () {
    $('html').addClass('thanks-message-popup-active');
    return false;
  });

  $('a.thanks-close').on('click', function () {
    $('html').removeClass('thanks-message-popup-active');
    return false;
  });

  //===== Counter Up =====//
  if ($.isFunction($.fn.counterUp)) {
    $('.counter').counterUp({
      delay: 10,
      time: 2000
    });
  }

  //===== Accordion =====//
  $('.toggle .content').hide();
  $('.toggle h4:first').addClass('active').next().slideDown(500).parent().addClass("activate");
  $('.toggle h4').on("click", function () {
    if ($(this).next().is(':hidden')) {
      $('.toggle h4').removeClass('active').next().slideUp(500).parent().removeClass("activate");
      $(this).toggleClass('active').next().slideDown(500).parent().toggleClass("activate");
    }
  });

  //===== Sticky Header =====//
  var menu_height = $('header').innerHeight();
  $(window).on("scroll", function () {
    var scroll = $(window).scrollTop();
    if (scroll >= menu_height) {
      $('.stick').addClass('sticky');
    } else {
      $('.stick').removeClass('sticky');
    }
  });
  if ($('header').hasClass('stick')) {
    $('main').css({
      'padding-top': menu_height
    });
  }

  //===== Responsive Header =====//
  $('.menu-btn').on('click', function () {
    $('.responsive-menu').addClass('slidein');
    return false;
  });
  $('.menu-close').on('click', function () {
    $('.responsive-menu').removeClass('slidein');
    return false;
  });
  $('.responsive-menu li.menu-item-has-children > a').on('click', function () {
    $(this).parent().siblings().children('ul').slideUp();
    $(this).parent().siblings().removeClass('active');
    $(this).parent().children('ul').slideToggle();
    $(this).parent().toggleClass('active');
    return false;
  });

  //===== Scroll Up Bar =====//
  if ($.isFunction($.fn.scrollupbar)) {
    $('header').scrollupbar({});
  }

  //===== Scroll Animation =====//
  $(window).on("scroll", function () {
    var scroll2 = $(window).scrollTop();
    if (scroll2 >= 300) {
      $('.left-scooty-mockup').addClass('easein');
    }
  });

  //===== LightBox =====//
  if ($.isFunction($.fn.fancybox)) {
    $('[data-fancybox],[data-fancybox="gallery"]').fancybox({});
  }

  //===== Chosen =====//
  if ($.isFunction($.fn.chosen)) {
    $('.select').chosen({});
  }

  //===== Custom Scrollbar =====//
  if ($.isFunction($.fn.niceScroll)) {
    $('.menu-lst > ul').niceScroll();
  }

  //===== Datepicker =====//
  if ($.isFunction($.fn.datepicker)) {
    $('.datepicker').datepicker({
      autoHide: true,
    });
  }

  //===== Timepicker =====//
  if ($.isFunction($.fn.timepicker)) {
    $('.timepicker').timepicker({
      autoHide: true,
    });
  }

  //===== Count Down =====//
  if ($.isFunction($.fn.downCount)) {
    $('.countdown').downCount({
      date: '12/12/2018 12:00:00',
      offset: +5
    });
  }

  //===== Touch Spin =====//
  if ($.isFunction($.fn.TouchSpin)) {
    $('input.qty').TouchSpin({});
  }

  //===== Owl Carousel =====//
  if ($.isFunction($.fn.owlCarousel)) {
    //=== Top Restaurants Carousel ===//
    $('.top-restaurants-carousel').owlCarousel({
      autoplay: false,
      smartSpeed: 600,
      loop: true,
      items: 1,
      dots: true,
      slideSpeed: 2000,
      nav: false,
      margin: 0,
      animateOut: 'slideOutDown',
      animateIn: 'slideInDown'
    });

    //=== Featured Restaurants Carousel ===//
    $('.featured-restaurant-carousel').owlCarousel({
      autoplay: true,
      smartSpeed: 600,
      loop: true,
      items: 1,
      dots: false,
      slideSpeed: 2000,
      nav: true,
      margin: 0,
      animateOut: 'fadeOut',
      animateIn: 'fadeIn',
      navText: [
        "<i class='fa fa-angle-left'></i>",
        "<i class='fa fa-angle-right'></i>"
      ]
    });

    //=== Blog Detail Gallery Carousel ===//
    $('.blog-detail-gallery-carousel').owlCarousel({
      autoplay: true,
      smartSpeed: 600,
      loop: true,
      items: 1,
      dots: false,
      slideSpeed: 2000,
      nav: true,
      margin: 0,
      animateOut: 'fadeOut',
      animateIn: 'fadeIn',
      navText: [
        "<i class='fa fa-angle-left'></i>",
        "<i class='fa fa-angle-right'></i>"
      ]
    });

    //=== Top Restaurants Carousel 2 ===//
    $('.top-restaurant-carousel2').owlCarousel({
      autoplay: true,
      smartSpeed: 600,
      loop: true,
      items: 6,
      dots: false,
      slideSpeed: 2000,
      nav: false,
      margin: 0,
      navText: [
        "<i class='fa fa-caret-left'></i>",
        "<i class='fa fa-caret-right'></i>"
      ],
      responsive: {
        0: {
          items: 1,
          nav: false
        },
        480: {
          items: 3,
          nav: false
        },
        768: {
          items: 6,
          nav: false
        },
        1200: {
          items: 6
        }
      }
    });

    //=== Dish carousel home1 ===//
    $('.dishes-caro').owlCarousel({
      autoplay: false,
      smartSpeed: 600,
      loop: true,
      items: 1,
      dots: true,
      slideSpeed: 2000,
      nav: false,
      margin: 30,
      navText: [
        "<i class='fa fa-caret-left'></i>",
        "<i class='fa fa-caret-right'></i>"
      ],
      responsive: {
        0: {
          items: 1,
          nav: false,
          dots: false
        },
        480: {
          items: 1,
          nav: false,
          dots: false
        },
        768: {
          items: 1,
          nav: false,
          dots: false
        },
        1200: {
          items: 1
        }
      }
    });

  }

  //===== Slick Carousel =====//
  if ($.isFunction($.fn.slick)) {
    //=== Restaurant Detail Carousel ===//
    $('.restaurant-detail-img-carousel').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: false,
      slide: 'li',
      fade: false,
      asNavFor: '.restaurant-detail-thumb-carousel'
    });

    $('.restaurant-detail-thumb-carousel').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      asNavFor: '.restaurant-detail-img-carousel',
      dots: false,
      arrows: false,
      slide: 'li',
      centerPadding: '0',
      focusOnSelect: true,
      responsive: [{
          breakpoint: 768,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: true,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            centerMode: true,
          }
        }
      ]
    });

    //=== Featured Restaurant Carousel ===//
    $('.featured-restaurant-food-img-carousel').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: false,
      slide: 'li',
      fade: false,
      asNavFor: '.featured-restaurant-food-thumb-carousel'
    });

    $('.featured-restaurant-food-thumb-carousel').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      asNavFor: '.featured-restaurant-food-img-carousel',
      dots: false,
      arrows: false,
      slide: 'li',
      centerPadding: '0',
      focusOnSelect: true,
      responsive: [{
          breakpoint: 980,
          settings: {
            slidesToShow: 5,
            slidesToScroll: 1,
            infinite: true,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: true,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            centerMode: true,
          }
        }
      ]
    });
  }

  //===== Ajax Contact Form =====//
  $('#contactform').on('submit', function () {
    var action = $(this).attr('action');

    var msg = $('#message');
    $(msg).hide();
    var data = 'name=' + $('#name').val() + '&email=' + $('#email').val() + '&phone=' + $('#phone').val() + '&comments=' + $('#comments').val() + '&verify=' + $('#verify').val() + '&captcha=' + $(".g-recaptcha-response").val();

    $.ajax({
      type: 'POST',
      url: action,
      data: data,
      beforeSend: function () {
        $('#submit').attr('disabled', true);
        $('img.loader').fadeIn('slow');
      },
      success: function (data) {
        $('#submit').attr('disabled', false);
        $('img.loader').fadeOut('slow');
        $(msg).empty();
        $(msg).html(data);
        $('#message').slideDown('slow');
        if (data.indexOf('success') > 0) {
          $('#contactform').slideUp('slow');
        }
      }
    });
    return false;
  });


  $("#btn_show_cart").click(function () {
    $(".cart_down").fadeToggle();
  });
  $("#close_cart").click(function () {
    $(".cart_down").fadeToggle();
  });




  $(".desc_cat:first").css("display", "block");
  $("h3.title_cat").click(function () {
    $(this).next().slideToggle(500);
    $(".desc_cat").not($(this).next()).slideUp(500);
  });

  $('a[href*="#"]').on('click', function (e) {
    e.preventDefault()

    $('html, body').animate({
        scrollTop: $($(this).attr('href')).offset().top,
      },
      500,
      'linear'
    )
  });




  $(".item_desc_info:first").css("display", "block");
  $(".item_select_head").click(function () {
    $(this).next().slideToggle(500);
    $(".item_desc_info").not($(this).next()).slideUp(500);
  });




  /*   $(window).scroll(function () {

      var headerH = $('.header').outerHeight(true);
      // console.log(headerH);
      //this will calculate header's full height, with borders, margins, paddings
      var scrollVal = $(this).scrollTop();
      if (scrollVal > headerH + 200) {
        $('.nav_left_accordion').css({
          'position': 'fixed',
          'top': '10px',
          'minWidth': '160px',
          'zIndex': '99',
          'backgroundColor': '#FFF'
        });
      } else {
        $('.nav_left_accordion').css({
          'position': 'static',
          'top': '0px'
        });
      }
    }); */
  /* 
    $(window).scroll(function () {

      var headerH = $('.header').outerHeight(true);
      // console.log(headerH);
      //this will calculate header's full height, with borders, margins, paddings
      var scrollVal = $(this).scrollTop();
      if (scrollVal > headerH + 200) {
        $('.cart_restu').css({
          'position': 'fixed',
          'zIndex': '99',
          'backgroundColor': '#FFF'
        });
      } else {
        $('.cart_restu').css({
          'position': 'static',
          'top': '0px'
        });
      }
    }); */


  /* $(window).scroll(function () {

    var headerH = $('.header').outerHeight(true);
    // console.log(headerH);
    //this will calculate header's full height, with borders, margins, paddings
    var scrollVal = $(this).scrollTop();
    if (scrollVal > headerH + 200) {
      $('.fixed_after_scrolling').css({
        'position': 'fixed',
        'top': '10px',
        'maxWidth': '250px',
        'zIndex': '99',
        'backgroundColor': '#FFF'
      });
    } else {
      $('.fixed_after_scrolling').css({
        'position': 'static',
        'top': '0px'
      });
    }
  });
 */






  $(".btn_add_friend").click(function () {
    $(this).after(
      '<div class="input-group"><input type="text" class="form-control" placeholder="Enter Your Friend Phone"></div>'
    );
  });


  $('.alert_success').hide();
  $('.pay_btn').click(function (e) {
    $('.alert_success').css("top", "-40px").fadeIn('slow', function () {
      $(this).fadeOut().css("top", "-100px");
    });
  });




  $("form").on("change", ".file_input", function () {
    $(this).parent(".file_upload_style").attr("data-text", $(this).val().replace(/.*(\/|\\)/, ''));
  });


  $(".terms_content .desc:first").css("display", "block");

  /*  $(".terms_content .btn_show_acc").click(function () {
     $(this).next().slideToggle(300);
     $(".terms_content .desc").not($(this).next()).slideUp(300);
     $(".btn_show_acc").find('i').removeClass('fa-minus').addClass('fa-plus');
   }); */


  $(".btn_show_acc").on("click", function () {
    if ($(this).hasClass("active")) {

      $(this).removeClass("active");

      $(this).siblings(".terms_content .desc").slideUp(200);

      $(".btn_show_acc > i").removeClass("fa-minus").addClass("fa-plus");

    } else {

      $(".btn_show_acc > i").removeClass("fa-minus").addClass("fa-plus");

      $(this).find("i").removeClass("fa-plus").addClass("fa-minus");

      $(".btn_show_acc").removeClass("active");

      $(this).addClass("active");

      $(".terms_content .desc").slideUp(200);

      $(this).siblings(".terms_content .desc").slideDown(200);

    }
  });




  /*  $(document).ready(function() {
       $('input[type="radio"]').click(function() {
           if($(this).attr('class') == 'chk_show') {
               $('.show_div').show();           
           }
           else {
               $('show_div').hide();   
           }
       });
   });
  */



  $(document).ready(function () {
    $(".account").click(function () {
      var X = $(this).attr('id');
      if (X == 1) {
        $(".submenu").hide();
        $(this).attr('id', '0');
      } else {
        $(".submenu").show();
        $(this).attr('id', '1');
      }
    });
    $(".submenu").mouseup(function () {
      return false
    });
    $(".account").mouseup(function () {
      return false
    });
    $(document).mouseup(function () {
      $(".submenu").hide();
      $(".account").attr('id', '');
    });
  });



  /*   $(function () {
      'use strict';
      $('i.show_pass').click(function () {
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
          $(this).removeClass('fa-eye').addClass('fa-eye-slash').prop('type', 'text');
        } else {
          $(this).removeClass('fa-eye-slash').addClass('fa-eye').prop('type', 'password');
        }
      });
    }); */


  $('.show_pass').click(function () {
    if ('password' == $('.pass_in').attr('type')) {
      $('.pass_in').prop('type', 'text');
      $('.show_pass').removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
      $('.pass_in').prop('type', 'password');
      $('.show_pass').removeClass('fa-eye-slash').addClass('fa-eye');
    }
  });






 /* $(document).ready(function () {
    $('.minus').click(function () {
      var $input = $(this).parent().find('input');
      var count = parseInt($input.val()) - 1;
      var minus = $(this).data('id');
      var name = $(this).data('name');

      count = count < 1 ? 1 : count;
      $input.val(count);
      $input.change();

      $.ajax({
        url: "{!! route('web.data') !!}",
        type: 'post',
        data: {minus: minus , name: name},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
          $('.cart_restu').html(data.cart);
          $('.myCart ').html(data.myCart)
        }
      });
      return false;
    });
    $('.plus').click(function () {
      var $input = $(this).parent().find('input');
      $input.val(parseInt($input.val()) + 1);
      $input.change();
      return false;
    });
  });*/




}); //===== Document Ready Ends =====//

/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/
/***********************************************************************************************************/



$(function () {

  window.verifyRecaptchaCallback = function (response) {
    $('input[data-recaptcha]').val(response).trigger('change');
  }

  window.expiredRecaptchaCallback = function () {
    $('input[data-recaptcha]').val("").trigger('change');
  }

  $('#contact-form').validator();

  $('#contact-form').on('submit', function (e) {
    if (!e.isDefaultPrevented()) {
      var url = "contact.php";

      $.ajax({
        type: "POST",
        url: url,
        data: $(this).serialize(),
        success: function (data) {
          var messageAlert = 'alert-' + data.type;
          var messageText = data.message;

          var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
          if (messageAlert && messageText) {
            $('#contact-form').find('.messages').html(alertBox);
            $('#contact-form')[0].reset();
            grecaptcha.reset();
          }
        }
      });
      return false;
    }
  })
});


jQuery(window).on('load', function ($) {
  'use strict';
  //===== Page Loader =====//
  jQuery('.preloader').fadeOut('slow');

  //===== Isotope =====//
  if (jQuery('.filter-item').length > 0) {
    if (jQuery().isotope) {
      var jQuerycontainer = jQuery('.masonry'); // cache container
      jQuerycontainer.isotope({
        itemSelector: '.filter-item',
        columnWidth: .5,
      });
      jQuery('.filter-buttons a').on('click', function () {
        var selector = jQuery(this).attr('data-filter');
        jQuery('.filter-buttons li').removeClass('active');
        jQuery(this).parent().addClass('active');
        jQuerycontainer.isotope({
          filter: selector
        });
        return false;
      });
      jQuerycontainer.isotope('layout'); // layout/layout
    }

    jQuery(window).resize(function () {
      if (jQuery().isotope) {
        jQuery('.masonry').isotope('layout'); // layout/relayout on window resize
      }
    });
  }

});


/* 
jQuery(function(){
    var j = jQuery; //Just a variable for using jQuery without conflicts
    var addInput = '.qty_cart'; //This is the id of the input you are changing
    var n = 1; //n is equal to 1

    //Set default value to n (n = 1)
    j(addInput).val(n);

    //On click add 1 to n
    j('.plus').on('click', function(){
        j(addInput).val(++n);
    })

    j('.min').on('click', function(){
        //If n is bigger or equal to 1 subtract 1 from n
        if (n >= 1) {
            j(addInput).val(--n);
        } else {
            //Otherwise do nothing
        }
    });
}); */



// $(".nav_left_accordion, .cart_restu, .fixed_after_scrolling").stick_in_parent({
//     offset_top: 60,
//     parent: '.page_details'
// });


(function () {

  'use strict';

  function activeStickyKit() {
    $('.nav_left_accordion, .cart_restu').stick_in_parent({
      offset_top: 60,
      parent: '.page_details'
    });

    // bootstrap col position
    $('.nav_left_accordion, .cart_restu')
      .on('sticky_kit:bottom', function (e) {
        $(this).parent().css('position', 'static');
      })
      .on('sticky_kit:unbottom', function (e) {
        $(this).parent().css('position', 'relative');
      });
  };
  activeStickyKit();

  function detachStickyKit() {
    $('.nav_left_accordion, .cart_restu').trigger("sticky_kit:detach");
  };

  //  stop sticky kit
  //  based on your window width

  var screen = 768;

  var windowHeight, windowWidth;
  windowWidth = $(window).width();
  if ((windowWidth < screen)) {
    detachStickyKit();
  } else {
    activeStickyKit();
  }

  // windowSize
  // window resize
  function windowSize() {
    windowHeight = window.innerHeight ? window.innerHeight : $(window).height();
    windowWidth = window.innerWidth ? window.innerWidth : $(window).width();

  }
  windowSize();

  // Returns a function, that, as long as it continues to be invoked, will not
  // be triggered. The function will be called after it stops being called for
  // N milliseconds. If `immediate` is passed, trigger the function on the
  // leading edge, instead of the trailing.
  function debounce(func, wait, immediate) {
    var timeout;
    return function () {
      var context = this,
        args = arguments;
      var later = function () {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };

  $(window).resize(debounce(function () {
    windowSize();
    $(document.body).trigger("sticky_kit:recalc");
    if (windowWidth < screen) {
      detachStickyKit();
    } else {
      activeStickyKit();
    }
  }, 250));

})(window.jQuery);