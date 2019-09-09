<?php
Route::post('admin/permission_exchange','permissionController@permission_exchange')->name('permission_exchange');
Route::get('test/ad',function(){

//   $te= \App\Restaurant::where('id',6)->first();
//    dd( $te->translate(App::getLocale())->name);

});

Route::get('/clear', function() {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function() {


    Route::group(['namespace'=>'Admin','prefix'=>'admin'],function (){
        Config::set('auth.defines','admin');
        Route::get('/login','AuthController@formLogin')->name('get.login');
        Route::get('/login','AuthController@formLogin')->name('login');
        Route::post('/login','AuthController@login')->name('post.login');
        Route::get('rest/password','AuthController@resetPassword')->name('get.reset.password');
        Route::post('rest/password','AuthController@postResetPassword')->name('post.reset.password');
        Route::get('rest/password/{token}','AuthController@reset')->name('get.reset');
        Route::post('rest/password/{token}','AuthController@postReset')->name('post.reset');


    });


    Route::group(['namespace'=>'Admin','prefix'=>'admin'],function (){
        Route::get('/logout','AuthController@logout')->name('logout');

        #profile
        Route::get('/profile','ProfileController@profile')->name('admin.profile');
        Route::post('/profile','ProfileController@updateProfile')->name('admin.profile.update');
        Route::post('/change-password','ProfileController@changePassword')->name('admin.change.password');

    });

    Route::namespace('Admin')->name('admin.')->prefix('admin')
        ->middleware(['admin'])->group(function (){

            Route::get('/', 'DashboardController@dashboard')->name('home');


            #users
            Route::get('/users', 'UserController@index')->name('users');
            Route::get('/wallet/users', 'UserController@walletUsers')->name('wallet.users');
            Route::get('/wallet/delivery', 'UserController@walletDelivery')->name('wallet.delivery');
            Route::get('/transaction', 'UserController@transaction')->name('transaction');
            Route::get('organization/accounting','UserController@organization_account')->name('organization.accounting');
            Route::get('balance/organization','UserController@balance')->name('organization.balance');

            #setting
            Route::get('/setting','SettingController@getSetting')->name('setting');
            Route::post('/setting','SettingController@postSetting')->name('post.setting');
            Route::post('/seo','SettingController@seo')->name('post.seo');
            Route::post('/contact-information','SettingController@contactInformation')->name('post.contact_info');
            Route::post('/social-media','SettingController@socialMedia')->name('post.social_media');

            #pages
            Route::resource('page', 'PageController');

            #package
            Route::resource('package', 'PackageController');
            Route::delete('package/destroy/all','PackageController@multiDelete')->name('package.destroy.all');


            #opinion
            Route::resource('opinion', 'OpinionController');
            Route::delete('opinion/destroy/all','OpinionController@multiDelete')->name('opinion.destroy.all');

            #question
            Route::resource('question', 'QuestionController');
            Route::delete('question/destroy/all','QuestionController@multiDelete')->name('question.destroy.all');

            #privacy
            Route::resource('privacy', 'PrivacyController');
            Route::delete('privacy/destroy/all','PrivacyController@multiDelete')->name('privacy.destroy.all');

            #manage shift
            Route::get('manage-shift','ManageShiftController@create')->name('manage.shift');
            Route::post('manage-shift','ManageShiftController@store')->name('post.manage.shift');

            #message
            Route::get('inbox','MessageController@viewMessage')->name('message.inbox');
            Route::get('compose','MessageController@composeMessage')->name('message.compose');
            Route::get('read','MessageController@readMessage')->name('message.read');
            Route::get('delete-message/{id}','MessageController@deleteMessageMessage')->name('message.delete');
            Route::get('message-open/{id}','MessageController@readMessage')->name('message.open');
            Route::get('message-reply/{id}','MessageController@getReplyMessage')->name('message.reply');

            Route::post('send-message','MessageController@sendMailMessage')->name('send.messages.mail');
            //Route::post('send-message','MessageController@sendMailMessageDashboard')->name('send.mail.dashboard');

            #admin
            Route::resource('admin', 'AdminController');
            Route::delete('admin/destroy/all','AdminController@multiDelete')->name('admin.destroy.all');

            #city
            Route::resource('city', 'CityController');
            Route::delete('city/destroy/all','CityController@multiDelete')->name('city.destroy.all');

            #state
            Route::resource('state', 'StateController');
            Route::delete('state/destroy/all','StateController@multiDelete')->name('state.destroy.all');

            #Type of restaurants
            Route::resource('types', 'TypeController');
            Route::delete('types/destroy/all','TypeController@multiDelete')->name('types.destroy.all');

            #category
            Route::resource('category', 'CategoryController');
            Route::delete('category/destroy/all','CategoryController@multiDelete')->name('category.destroy.all');


            #blog
            Route::resource('blog', 'BlogController');
            Route::delete('blog/destroy/all','BlogController@multiDelete')->name('blog.destroy.all');


            #restaurants
            Route::resource('restaurants', 'RestaurantController');
            Route::delete('restaurants/destroy/all','RestaurantController@multiDelete')->name('restaurants.destroy.all');

            #order
            Route::resource('order','OrderController');

            #request working
            Route::resource('request-working','RequestWorkingController');
            Route::delete('request-working/destroy/all','BlogController@multiDelete')->name('request-working.destroy.all');


            #notifications
            Route::get('/notifications/mark/all',function (){
                Auth::guard('admin')->user()->unreadNotifications->markAsRead();
                return redirect()->back();
            })->name('mark.all');

        });


    Route::namespace('Vendor')->name('vendor.')->prefix('vendor')
        ->middleware(['admin'])->group(function (){

            Route::get('/', 'DashboardController@dashboard')->name('home');

            #profile
            Route::get('/profile','ProfileController@profile')->name('profile');
            Route::post('/profile','ProfileController@updateProfile')->name('profile.update');
            Route::post('/change-password','ProfileController@changePassword')->name('change.password');

            #restaurants
            Route::resource('restaurants', 'RestaurantController');
            Route::resource('working', 'WorkingController');
            Route::delete('working/destroy/all','WorkingController@multiDelete')->name('working.destroy.all');

            #user vendor
            Route::resource('userVendor','UserVendorController');
            Route::delete('userVendor/destroy/all','UserVendorController@multiDelete')->name('userVendor.destroy.all');


            #location
            Route::resource('location','LocationController');
            Route::delete('location/destroy/all','LocationController@multiDelete')->name('location.destroy.all');

            #menu
            Route::resource('menu','MenuController');
            Route::delete('menu/destroy/all','MenuController@multiDelete')->name('menu.destroy.all');

            #menu
            Route::resource('menu-details','MenuDetailsController');
            Route::delete('menu-details/destroy/all','MenuDetailsController@multiDelete')->name('menu-details.destroy.all');

            Route::get('ajax/deleteOtherData', 'MenuDetailsController@deleteOtherData')->name('otherData.delete.record');

            #offer
            Route::resource('offer','OfferController');
            Route::delete('offer/destroy/all','OfferController@multiDelete')->name('offer.destroy.all');

            #order
            Route::resource('order','OrderController');
            Route::delete('order/destroy/all','OfferController@multiDelete')->name('order.destroy.all');




        });





    Route::namespace('Frontend')->name('web.')
        ->middleware('auth:web')->middleware('Maintenance')
        ->group(function (){
            Route::get('/','HomeController@home')->name('home');
            Route::get('/restaurant','HomeController@getRestaurants')->name('get.restaurants');
            Route::post('/restaurants','HomeController@restaurantsSearch')->name('restaurant.search');

            Route::get('restaurant/{restaurant}','HomeController@getDetailsRestaurant')->name('details.restaurant');


            Route::get('/home', 'HomeController@home');
            Route::get('/send/email', 'HomeController@mail');
            Route::get('/profile', 'ProfileController@index')->name('users.profile');
            Route::post('/profile', 'ProfileController@updateProfile')->name('post.users.profile');

            Route::get('/users/logout','AuthController@logoutUsers')->name('logout.users');
            Route::get('/checkout','CheckoutController@getCheckout')->name('get.checkout');
            Route::post('/store/order','CheckoutController@storeOrder')->name('store.order');
            Route::post('/review/restaurants','reviewRestaurants@reviewRes')->name('review.restaurants');
        });


//*Auth::routes();

Route::namespace('Frontend')->name('web.')
    ->middleware('Maintenance')
    ->group(function (){

        Route::get('/','HomeController@home')->name('home');
        Route::get('/restaurant','HomeController@getRestaurants')->name('get.restaurants');
        Route::post('/restaurants','HomeController@restaurantsSearch')->name('restaurant.search');

        Route::get('restaurant/{restaurant}','HomeController@getDetailsRestaurant')->name('details.restaurant');
        Route::get('sort/asc/restaurants','HomeController@sortRestaurantASC')->name('asc.restaurants');
        Route::get('sort/desc/restaurants','HomeController@sortRestaurantDESC')->name('desc.restaurants');
        Route::get('sort/rating/restaurants','HomeController@sortRestaurantRating')->name('sort.rating.restaurants');

        #add cart in use session
        Route::get('/add-cart/{id}','HomeController@addCart')->name('add.cart');

        #auth social link
        Route::get('auth/{provider}', 'AuthController@redirectToProvider');
        Route::get('auth/{provider}/callback', 'AuthController@handleProviderCallback');

        #auth
        Route::get('/login','AuthController@showFormLogin')->name('get.login');
        Route::post('/users/login','AuthController@loginUsers')->name('login.users');
        Route::get('/register','AuthController@showFormRegister')->name('get.register');
        Route::post('/users/register','AuthController@registerUser')->name('register.users');

        Route::get('rest/password','AuthController@resetPassword')->name('get.reset.password');
        Route::post('rest/password','AuthController@postResetPassword')->name('post.reset.password');
        Route::get('rest/password/{token}','AuthController@reset')->name('get.reset');
        Route::post('rest/password/{token}','AuthController@postReset')->name('post.reset');

        Route::get('/delivery/register','AuthController@showFormDelivery')->name('get.delivery');
        Route::post('/delivery/register','AuthController@registerDelivery')->name('register.delivery');
        Route::get('/restaurant/register','AuthController@showFormRestaurant')->name('get.restaurant');
        Route::post('/restaurant/register','AuthController@registerRestaurant')->name('register.restaurant');


        #about us
        Route::get('/about-us','HomeController@aboutUs')->name('about.us');
        #terms us
        Route::get('/term','HomeController@term')->name('term');

        #fqa
        Route::get('/fqa','HomeController@FQA')->name('faq');
        #fqa
        Route::get('/privacy','HomeController@privacy')->name('privacy');

        #contact us
        Route::get('/contact-us','HomeController@contactUs')->name('contact.us');
        Route::post('/contact-us','HomeController@postContact')->name('post.contact.us');

        #blog
        Route::get('/blog','HomeController@blog')->name('get.blog');
        Route::get('/blog/{title}','HomeController@blogDetails')->name('get.blog.details');

        #offer
        Route::get('/offer','HomeController@offers')->name('offer');

        #ajax
        Route::post('fetch/stats','AjaxController@fetchStats')->name('fetch.stats');
        Route::post('data/data','AjaxController@fetchStats')->name('data');
        Route::post('data/load/more','AjaxController@loadData')->name('load.data');
        Route::post('data/load/review','AjaxController@loadData2')->name('load.data2');
        Route::post('filter/type/restaurant','HomeController@filterTypeRestaurant')->name('filter.type.restaurant');
        Route::get('filter/search/restaurant','HomeController@searchRestaurant')->name('search.ajax');
        Route::get('autocomplete', 'AjaxController@search')->name('autocomplete');
        Route::post('dataPlusMinus', 'AjaxController@dataPlusMinus')->name('dataPlusMinus');
        Route::post('remove/cart','AjaxController@removeCart')->name('remove.cart');
        Route::post('qty/cart','AjaxController@removeCart')->name('qty.cart');

        #Coupon
        Route::get('/coupon','HomeController@coupon')->name('get.coupon');

        #request-worker
        Route::post('/request-worker','HomeController@requestWorker')->name('request.working');

        #search name Restaurant
        Route::post('/restaurants/search','HomeController@restaurantSearch')->name('restaurants.search');

        #most selling
        Route::get('most-selling','HomeController@mostSelling')->name('most.selling');
        Route::get('restaurants/{restaurant}','HomeController@getViewRestaurant')->name('view.restaurant');



        Route::get('session',function (){
         return  Session::all();
        });



    });

    Route::get('maintenance',function (){
        return view('frontend.maintenance.maintenance');
    });
//Route::get('/', 'Frontend\HomeController@home')->name('web.home');

});
Route::get('stat/{id}', 'Frontend\HomeController@stat');



Route::get('/payment/paytabs/request', 'Frontend\PaytabsController@paymentRequest')->name('payment.paytabs.request');

// Update order done
Route::post('/payment/paytabs/response', 'Frontend\PaytabsController@paymentResponse')->name('payment.paytabs.response');



Route::get('success', 'Frontend\PaytabsController@success');

Route::get('facebook', function () {
    return view('facebook');
});
Route::get('auth/facebook', 'Auth\FacebookController@redirectToFacebook');
Route::get('auth/facebook/callback', 'Auth\FacebookController@handleFacebookCallback');

Route::get('/test','FirebaseController@index');
