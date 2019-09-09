<?php

namespace App\Http\Controllers\Frontend;

use App\Blog;
use App\Cart;
use App\Category;
use App\City;
use App\Http\Middleware\Admin;
use App\MenuDetails;
use App\Message;
use App\MyCart;
use App\Package;
use App\Page;
use App\Privacy;
use App\Question;
use App\StateTranslation;
use App\RequestWorking;
use App\Restaurant;
use App\State;
use App\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class HomeController extends Controller
{

    public function mail()
    {
        $name = 'test';
        Mail::to('test@gmail.com')->send(new SendMailable($name));

        return 'Email was sent';
    }

    public function addCart(Request $request, $id)
    {
        $menuDetails = MenuDetails::find($id);
        $oldCart = \Session::has('cart') ? \Session::has('cart') : '' ;
        $cart = new MyCart($oldCart);
        $cart->add($menuDetails,$menuDetails->id);
        $request->session()->put('cart',$cart);
        //dd($request->session()->get('cart'));
        return redirect()->back();
    }

    public function home()
    {
        try{
            $type = Type::where('status',1)->orderBy('created_at','DESC')->get();

            $city = City::where('status',1)->orderBy('created_at','DESC')->get();

            $page = Page::find(1);

            $blog = Blog::where('status',1)->orderBy('created_at','DESC')->get();
            $restaurants = Restaurant::with('menuRestaurant')
                -> where('features_type',1)
                ->orderBy('created_at','DESC')->get();


            if (\request()->ajax())
            {
                if (\request()->has('city_id'))
                {
                    $select = \request()->has('select') ? \request('select') : '' ;

                    return \Form::select('state_id',
                        \App\StateTranslation::join('states','state_translations.state_id','=','states.id')->
                        where('states.city_id',\request('city_id'))->
                        where('state_translations.locale',\App::getLocale())
                            ->pluck('state_translations.name','state_translations.state_id')
                        ,
                        $select,[' class' => '  select ' , 'style'=>' height: 47px;color: #fff;  background-color: #dc531f00;', 'placeholder'=>trans('web.Select a state')]) ;

                }
            }

            return view('frontend.home.home',compact('type','restaurants','city','page','blog'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    #all restaurant
    public function getRestaurants()
    {
        try{
            $restaurants = Restaurant::with('menuRestaurant')

                ->orderBy('created_at','DESC')->get();
            return view('frontend.restaurants.restaurants',compact('restaurants'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    #search restaurant
    public function restaurantsSearch(Request $request)
    {
        try{
            $messages = [
//                'city_id.required' => 'We need to know your city name!',
//                'state_id.required' => 'We need to know your state name',
            ];

            $validator = \Validator::make($request->all(), [
//                'city_id' => 'required|exists:cities,id',
//                'state_id' => 'required|exists:states,id',
            ], $messages);

            if ($validator->fails()) {

                return redirect()->back()
                    ->withErrors($validator)->withInput();
            }

            $typeRestaurant = Type::with('restaurantType')
                ->whereHas('restaurantType',function ($q){
                    $q->where('status',1);
                })
                ->where('status','=',1)
                ->get();

//           if ($request->city_id && $request->state_id && $request->type_id == null)
//           {
//               dd("ddd");
//
//               $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
//                   ->whereHas('locationRestaurant',function ($q) use ($request){
//                       $q->where('city_id',$request->city_id);
//                       $q->where('state_id',$request->state_id);
//                   })->get();
//               return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));
//
//           }
//           if ($request->city_id && $request->state_id && $request->type_id)
//           {
//               dd("123");
            if ($request->city_id && $request->state_id && $request->type_id){
//                     $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
//                         ->whereHas('locationRestaurant',function ($q) use ($request){
//                             $q->where('city_id',$request->city_id);
//                             $q ->where('state_id',$request->state_id);
//                         })->where('type_id','=',$request->type_id)
//                         ->get();
                $restaurants = Restaurant::where('city_id',$request->city_id)->where('state_id',$request->state_id)->where('type_id','=',$request->state_id)
                    ->get();
//               dd("123");

                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));

            }elseif ($request->city_id){
                $restaurants = Restaurant::where('city_id',$request->city_id)->get();
//                     dd($request->city_id);
                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));

            }elseif ($request->type_id){
                $restaurants = Restaurant::where('type_id',$request->type_id)->get();
//                                          dd('sa111111111');
                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));

            }
            elseif ($request->city_id && $request->state_id){
//                     $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
//                         ->whereHas('locationRestaurant',function ($q) use ($request){
//                             $q->where('city_id',$request->city_id);
//                             $q ->where('state_id',$request->state_id);
//                         })
//                         ->get();
                $restaurants = Restaurant::where('city_id',$request->city_id)->where('state_id','=',$request->state_id)
                    ->get();
//               dd("12sa3");

                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));

            }else{
//                     $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
//                         ->whereHas('locationRestaurant',function ($q) use ($request){
//                             $q->where('city_id',$request->city_id);
//                         })->where('type_id','=',$request->type_id)
//                         ->get();
                $restaurants = Restaurant::get();
//               dd($restaurants);

                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));

            }

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    #sort asc restaurant
    public function sortRestaurantASC()
    {
        try{
            $typeRestaurant = Type::with('restaurantType')
                ->whereHas('restaurantType',function ($q){
                    $q->where('status',1);
                })
                ->where('status','=',1)
                ->get();

            $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                ->orderBy('created_at','ASC')
                ->get();

            if ($restaurants->isEmpty())
            {
                $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                    ->get();

                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));
            }
            else
            {
                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));

            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
    #sort desc restaurant
    public function sortRestaurantDESC()
    {
        try{
            $typeRestaurant = Type::with('restaurantType')
                ->whereHas('restaurantType',function ($q){
                    $q->where('status',1);
                })
                ->where('status','=',1)
                ->get();

            $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                ->orderBy('created_at','DESC')
                ->get();

            if ($restaurants->isEmpty())
            {
                $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                    ->get();

                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));
            }
            else
            {
                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));

            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
    #sort rating restaurant
    public function sortRestaurantRating()
    {
        try{
            $typeRestaurant = Type::with('restaurantType')
                ->whereHas('restaurantType',function ($q){
                    $q->where('status',1);
                })
                ->where('status','=',1)
                ->get();

            $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                ->whereHas('ratingRestaurant',function ($q){
                    $q->whereIn('rating',[4,5]);
                })
                ->orderBy('created_at','DESC')
                ->get();

            if ($restaurants->isEmpty())
            {
                $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                    ->get();

                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));
            }
            else
            {
                return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));

            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
    #details menu in restaurants
    public function getDetailsRestaurant($restaurant)
    {
        try{
            $restaurants = Restaurant::with('restaurantTranslations','menuRestaurant',
                'menuRestaurant.menuDetailsRestaurant','workingHours')
                ->whereHas('restaurantTranslations',function ($q) use ($restaurant){
                    $q->where('name',$restaurant);
                    $q->where('locale',\App::getLocale());
                })->first();

            $rating = ratingRestaurant($restaurants->id);

            $cart = Cart::where('user_id',\Auth::guard('web')->user() ? \Auth::user()->id :'')->get();

            CurrentUrl();
            return view('frontend.restaurants.details',compact('restaurants','cart','rating'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    #about us
    public function aboutUs()
    {
        $about = Page::where('slug','about-us')->first();

        return view('frontend.pages.aboutUs',compact('about'));
    }

    #term
    public function term()
    {
        $term = Page::where('slug','terms')->first();

        return view('frontend.pages.term',compact('term'));
    }

    #term
    public function FQA()
    {
        $faq = Category::with('FQACategory')
            ->whereHas('FQACategory',function ($q){
                $q->where('status',1);
            })->where('status','=',1)
            ->get();
        return view('frontend.pages.faq',compact('faq'));
    }

    #term
    public function privacy()
    {
        $privacy = Privacy::where('status',1)->get();

        return view('frontend.pages.privacy',compact('privacy'));
    }

    #messages
    public function contactUs()
    {
        $city = City::where('status',1)->orderBy('created_at','DESC')->get();
        $type = Type::where('status',1)->orderBy('created_at','DESC')->get();
        if (\request()->ajax())
        {
            if (\request()->has('city_id'))
            {
                $select = \request()->has('select') ? \request('select') : '' ;
                return \Form::select('state_id',
                    \App\StateTranslation::join('states','state_translations.state_id','=','states.id')->
                    where('states.city_id',\request('city_id'))->
                    where('state_translations.locale',\App::getLocale())
                        ->pluck('state_translations.name','state_translations.state_id')
                    ,
                    $select,[' class' => 'form-control' , 'placeholder'=>trans('web.Select state name')]) ;

            }
        }
        $page = Page::find(3);
        return view('frontend.pages.contactUs',compact('page','city','type'));
    }

    #contact us
    public function postContact(Request $request)
    {
        try{
            $messages = [
                'name.required' => 'We need to know your full name',
                'mobile.required' => 'We need to know your mobile',
                'email.required' => 'We need to know your e-mail address!',
                'subject.required' => 'We need to know your subject',
                'message.required' => 'We need to know your message',
            ];
            $validator = \Validator::make($request->all(), [
                'name'=>'required|min:3',
                'mobile'=>'required|numeric',
                'subject' => 'min:3|required',
                'email'=>'required|email|string',
                'type'=>'required|in:vendor,customer,others,delivery',
                'message' =>'min:3|required',
            ], $messages);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)->withInput();
            }
            $message = new Message();

            $message->name = $request->get('name');
            $message->emailTo = $request->get('email');
            $message->emailSend = \App\Admin::find(1)->email;
            $message->subject = $request->get('subject');
            $message->mobile = $request->get('mobile');
            $message->message = $request->get('message');
            $message->type = $request->get('type');
            $message->read = 0;
            $message->send = 0;
            $message->receive = 1;
            $message->save();

            if ($message->save())
            {
                /* $data =
                     [
                         'email'=>$message->email,
                         'subject'=>$message->subject,
                         'bodyMessage'=>$message->message,

                     ];
                 \Mail::send('frontend.mail.mail',$data,function ($messages) use ($data){
                     $messages->from(Admin::find(1)->email);
                     $messages->to($data['email']);
                     $messages->subject($data['subject']);

                 });*/
                return redirect()->route('web.home')->with('success',trans('web.Done Send Mail Success'));
            }
            else
            {
                return redirect()->route('web.contact.us')->with('warning',trans('web.Please try again'));
            }


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    #offer
    public function offers()
    {
        $restaurants = Restaurant::with('offers')
            ->whereHas('offers',function ($q){
                $q->whereDate('fromDate', '<=', date("Y-m-d",strtotime(Carbon::now())))
                    ->whereDate('toDate', '>=', date("Y-m-d",strtotime(Carbon::now())))
                    ->where('offers.status',1);
            })->get();

        return view('frontend.offer.offer',compact('restaurants'));
    }

    #coupon
    public function coupon()
    {
        $coupon = Package::where('status',1)->get();

        return view('frontend.coupon.coupon',compact('coupon'));
    }

    #blog
    public function blog()
    {
        $blog = Blog::where('status',1)->orderBy('created_at','DESC')->get();

        return view('frontend.blog.blog',compact('blog'));
    }

    #blogDetails
    public function blogDetails($title)
    {

        $blog = Blog::with('blogTranslation')
            ->whereHas('blogTranslation',function ($q) use ($title){
                $q->where('title',$title);
                $q->where('locale',\App::getLocale());
            })
            ->where('status',1)
            ->orderBy('created_at','DESC')->first();
        return view('frontend.blog.blogDetails',compact('blog'));
    }

    #requestWorker
    public function requestWorker(Request $request)
    {
        try{
            if ($request->type =='delivery')
            {
                $messages = [
                    'firstName.required' => 'We need to know your first name',
                    'lastName.required' => 'We need to know your last name',
                    'city_id.required' => 'We need to know your city name!',
                    'state_id.required' => 'We need to know your state name',
                    'address.required' => 'We need to know your address',
                    'phone.required' => 'We need to know your phone',
                    'email.required' => 'We need to know your phone',
                    'type.required' => 'We need to know your phone',
                    'method.required' => 'We need to know your files',
                    // 'upload_file.required' => 'We need to know your files',
                ];
                $validator = \Validator::make($request->all(), [
                    'firstName'=>'required|min:3',
                    'lastName'=>'required|min:3',
                    'city_id' => 'required|exists:cities,id',
                    'state_id' => 'required|exists:states,id',
                    'address'=>'required|string',
                    'email'=>'required|email|string|unique:request_workings,email|unique:users,email|unique:admins,email',
                    'type'=>'required|in:owner,delivery',
                    'method'=>'required|in:fileOwner,fileDelivery',
                    'phone' =>'required',
                    // 'upload_file' =>'required',
                ], $messages);
            }
            elseif ($request->type == 'owner')
            {
                $messages = [
                    'firstName.required' => 'We need to know your first name',
                    'lastName.required' => 'We need to know your last name',
                    'city_id.required' => 'We need to know your city name!',
                    'state_id.required' => 'We need to know your state name',
                    'address.required' => 'We need to know your address',
                    'phone.required' => 'We need to know your phone',
                    'email.required' => 'We need to know your phone',
                    'type.required' => 'We need to know your phone',
                    'method.required' => 'We need to know your files',
                    // 'upload_file.required' => 'We need to know your files',
                    'res_name.required' => 'We need to know your restaurant name',
                    'type_id.required' => 'We need to know your type of restaurant',
                ];
                $validator = \Validator::make($request->all(), [
                    'firstName'=>'required|min:3',
                    'res_name'=>'required|min:3',
                    'lastName'=>'required|min:3',
                    'city_id' => 'required|exists:cities,id',
                    'state_id' => 'required|exists:states,id',
                    'type_id' => 'required|exists:types,id',
                    'address'=>'required|string',
                    'email'=>'required|email|string|unique:request_workings,email|unique:users,email|unique:admins,email',
                    'type'=>'required|in:owner,delivery',
                    'method'=>'required|in:fileOwner,fileDelivery',
                    'phone' =>'required',
                    // 'upload_file' =>'required',
                ], $messages);
            }

            if ($validator->fails()) {
                // dd($validator->errors()->all());

                session()->put('profile','active');
                session()->put('type',$request->type);

                // return redirect()->back()->with('error', $validator->errors()->all());

                return redirect()->back()->withInput()->withErrors($validator);

                return redirect()->back()
                    ->withErrors($validator)->withInput();
            }

            if ($request->file('upload_file'))
            {
                $filename = uploadImages($request->upload_file,'admin/','');
            }
            // if ($request->hasFile('upload_file'))
            // {
            //   $save =  \Storage::putFile('public',$request->file('upload_file'));

            //     //\Storage::delete('public/C5rmhnUqiE9vyiaJQnFEbDJ82ahwTmPvq5Q14qZu.xlsx');
            // }
            // else
            // {
            //     session('warning',trans('web.Please upload data'));
            // }
            $data = new RequestWorking();
            $data->name = $request->firstName.' '.$request->lastName;
            $data->type = $request->type;
            if ($request->type == 'owner')
            {
                $data->res_name = $request->res_name;
                $data->type_id = $request->type_id;
            }

            $data->city_id = $request->city_id;
            $data->state_id = $request->state_id;
            $data->address = $request->address;
            $data->phone = $request->phone;
            $data->email = $request->email;
            // dd($filename);

            // $data->file = $filename;
            // dd($data);
            $data->save();
            if ($data->save())
            {
                return redirect()->back()->with('message',trans('web.Data has been successfully'));

                session('success',trans('web.Data has been successfully'));
                session()->forget('profile');
                session()->forget('type');
                return redirect()->route('web.home');
            }
            else
            {
                session('warning',trans('web.Please try again'));
                session()->put('profile','active');
                session()->put('type',$request->type);
                return redirect()->back()->withInput();
            }

        }catch (\Exception$exception )
        {
            session()->put('profile','active');
            session()->put('type',$request->type);
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    #filterTypeRestaurant
    public function filterTypeRestaurant()
    {
        if (\request()->ajax())
        {
            if (\request('name') == 'all')
            {
                $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                    ->get();

                $head = '<div class="featured-restaurant-box style3 brd-rd5">';
                $content = '';
                $footer = '</div>';
                $rating = '';
                foreach($restaurants as $key =>$value)
                {
                    if ($value->ratingRestaurant()->count() > 0)
                    {
                        for($i=0; $i<number_format((int)$value->ratingRestaurant()->sum('rating') /$value->ratingRestaurant()->count(), 0, '.', ''); $i++)
                        {
                            $rating = $rating.'<li><i class="fa fa-star"></i></li>';
                        }
                    }
                    $content = $content.' <div class="featured-restaurant-thumb"><a href="'. route('web.details.restaurant',$value->translate(\App::getLocale())->name) .'" title="" itemprop="url"><img src="'.$value->logoPath.'" alt="restaurant-logo1-1.png" itemprop="image"></a></div> <div class="featured-restaurant-info"> <h4 itemprop="headline" class="mb-3"><a href="'.route('web.details.restaurant',$value->translate(\App::getLocale())->name).'" title="" itemprop="url">'.$value->translate(\App::getLocale())->name.'</a></h4> <span class="red-clr">'.$value->translate(\App::getLocale())->addess.'</span>  <div class="rating"> <ul class="nav navbar-nav mr-3"> '.$rating.' </ul> <span>('.$value->ratingRestaurant()->count() > 0 ? $value->ratingRestaurant()->count() : trans('web.No').' '.trans('web.Ratings').')</span> </div>  <div class="mt-3 mb-3"> <strong>'.trans('web.Pay by :').' </strong> '.trans('web.Cash / Debit / Credit Card / Wallet').' </div> <ul class="post-meta">  <li><i class="flaticon-transport"></i> '.trans('web.Delivery').' : '.trans('web.20min').'</li> <li><i class="flaticon-transport"></i> '.trans('web.AVG :').' 30min</li> </ul> </div> <div class="view-menu-liks"> <a class="brd-rd3" href="'.route('web.details.restaurant',$value->translate(\App::getLocale())->name).'" title="" itemprop="url">'.trans('web.View Details').'</a> </div>';
                }
                return $head.$content.$footer;
            }

            if (\request('name') == 'typeData')
            {
                $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                    ->where('type_id','=',\request('id'))
                    ->orderBy('created_at','ASC')->get();

                if ($restaurants->isEmpty())
                {

                    $head = '<div class="featured-restaurant-box style3 brd-rd5">';
                    $content = '<div class="featured-restaurant-thumb"><span class="text-center">'.trans('web.Not found data').'</span>';
                    $footer = '</div>';

                    return $head.$content.$footer;
                }
                else
                {
                    $head = '';
                    $content = '';
                    $footer = '';
                    $rating = '';
                    $count = trans('web.No');
                    foreach($restaurants as $key =>$value)
                    {
                        if ($value->ratingRestaurant()->count() > 0)
                        {
                            for($i=0; $i<number_format((int)$value->ratingRestaurant()->sum('rating') /$value->ratingRestaurant()->count(), 0, '.', ''); $i++)
                            {
                                $rating = $rating.'<li><i class="fa fa-star"></i></li>';
                            }
                            if ($value->ratingRestaurant()->count() > 0)
                            {
                                $count = $value->ratingRestaurant()->count();
                            }
                            else
                            {
                                $count = trans('web.No');
                            }

                        }
                        $content = $content.' <div class="featured-restaurant-box style3 brd-rd5"><div class="featured-restaurant-thumb"><a href="'. route('web.details.restaurant',$value->translate(\App::getLocale())->name) .'" title="" itemprop="url"><img src="'.$value->logoPath.'" alt="restaurant-logo1-1.png" itemprop="image"></a></div> <div class="featured-restaurant-info"> <h4 itemprop="headline" class="mb-3"><a href="'.route('web.details.restaurant',$value->translate(\App::getLocale())->name).'" title="" itemprop="url">'.$value->translate(\App::getLocale())->name.'</a></h4> <span class="red-clr">'.$value->translate(\App::getLocale())->addess.'</span>  <div class="rating"> <ul class="nav navbar-nav mr-3"> '.$rating.' </ul> <span>('.$count.' '.trans('web.Ratings').')</span> </div>  <div class="mt-3 mb-3"> <strong>'.trans('web.Pay by :').' </strong> '.trans('web.Cash / Debit / Credit Card / Wallet').' </div> <ul class="post-meta">  <li><i class="flaticon-transport"></i> '.trans('web.Delivery').' : '.trans('web.20min').'</li> <li><i class="flaticon-transport"></i> '.trans('web.AVG :').' 30min</li> </ul> </div> <div class="view-menu-liks"> <a class="brd-rd3" href="'.route('web.details.restaurant',$value->translate(\App::getLocale())->name).'" title="" itemprop="url">'.trans('web.View Details').'</a> </div></div>';
                    }
                    return $head.$content.$footer;

                }
            }
        }

    }

    #searchRestaurant
    public function searchRestaurant(Request $request)
    {
        if ($request->ajax())
        {
            $query = $request->get('query');
            if ($query != '')
            {
                $restaurants = Restaurant::
                with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant','restaurantType','restaurantTranslations')
                    ->whereHas('restaurantTranslations',function ($q) use ($query){
                        $q->where('name','like','%'.$query.'%');
                        ;
                    })->orderBy('created_at','DESC')
                    ->get();
                if ($restaurants->isEmpty())
                {
                    $head = '<div class="featured-restaurant-box style3 brd-rd5">';
                    $content = '<div class="featured-restaurant-box style3 brd-rd5"> <span class="text-center">'.trans('web.Not found data').'</span> </div>';
                    $footer = '</div>';
                }
                else
                {
                    $head = '<div class="featured-restaurant-box style3 brd-rd5">';
                    $content = '';
                    $footer = '</div>';
                    $rating = '';
                    foreach($restaurants as $key =>$value)
                    {
                        if ($value->ratingRestaurant()->count() > 0)
                        {
                            for($i=0; $i<number_format((int)$value->ratingRestaurant()->sum('rating') /$value->ratingRestaurant()->count(), 0, '.', ''); $i++)
                            {
                                $rating = $rating.'<li><i class="fa fa-star"></i></li>';
                            }
                        }
                        else
                        {
                            for($a=0; $a<5; $a++)
                            {
                                $rating = $rating.'<li><i class="fa fa-star"></i></li>';

                            }
                        }
                        $content = $content.' <div class="featured-restaurant-thumb"><a href="'. route('web.details.restaurant',$value->translate(\App::getLocale())->name) .'" title="" itemprop="url"><img src="'.$value->logoPath.'" alt="restaurant-logo1-1.png" itemprop="image"></a></div> <div class="featured-restaurant-info"> <h4 itemprop="headline" class="mb-3"><a href="'.route('web.details.restaurant',$value->translate(\App::getLocale())->name).'" title="" itemprop="url">'.$value->translate(\App::getLocale())->name.'</a></h4> <span class="red-clr">'.$value->translate(\App::getLocale())->addess.'</span>  <div class="rating"> <ul class="nav navbar-nav mr-3"> '.$rating.' </ul> <span>('.$value->ratingRestaurant()->count().' '.trans('web.Ratings').')</span> </div>  <div class="mt-3 mb-3"> <strong>'.trans('web.Pay by :').' </strong> '.trans('web.Cash / Debit / Credit Card / Wallet').' </div> <ul class="post-meta">  <li><i class="flaticon-transport"></i> '.trans('web.Delivery').' : '.trans('web.20min').'</li> <li><i class="flaticon-transport"></i> '.trans('web.AVG :').' 30min</li> </ul> </div> <div class="view-menu-liks"> <a class="brd-rd3" href="'.route('web.details.restaurant',$value->translate(\App::getLocale())->name).'" title="" itemprop="url">'.trans('web.View Details').'</a> </div> ';
                    }
                }
            }
            else
            {
                $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                    ->get();
                $head = '<div class="featured-restaurant-box style3 brd-rd5">';
                $content = '';
                $footer = '</div>';
                $rating = '';
                foreach($restaurants as $key =>$value)
                {
                    if ($value->ratingRestaurant()->count() > 0)
                    {
                        for($i=0; $i<number_format((int)$value->ratingRestaurant()->sum('rating') /$value->ratingRestaurant()->count(), 0, '.', ''); $i++)
                        {
                            $rating = $rating.'<li><i class="fa fa-star"></i></li>';
                        }
                    }
                    else
                    {
                        for($a=0; $a<5; $a++)
                        {
                            $rating = $rating.'<li><i class="fa fa-star"></i></li>';

                        }
                    }
                    $content = $content.' <div class="featured-restaurant-thumb"><a href="'. route('web.details.restaurant',$value->translate(\App::getLocale())->name) .'" title="" itemprop="url"><img src="'.$value->logoPath.'" alt="restaurant-logo1-1.png" itemprop="image"></a></div> <div class="featured-restaurant-info"> <h4 itemprop="headline" class="mb-3"><a href="'.route('web.details.restaurant',$value->translate(\App::getLocale())->name).'" title="" itemprop="url">'.$value->translate(\App::getLocale())->name.'</a></h4> <span class="red-clr">'.$value->translate(\App::getLocale())->addess.'</span>  <div class="rating"> <ul class="nav navbar-nav mr-3"> '.$rating.' </ul> <span>('.$value->ratingRestaurant()->count().' '.trans('web.Ratings').')</span> </div>  <div class="mt-3 mb-3"> <strong>'.trans('web.Pay by :').' </strong> '.trans('web.Cash / Debit / Credit Card / Wallet').' </div> <ul class="post-meta">  <li><i class="flaticon-transport"></i> '.trans('web.Delivery').' : '.trans('web.20min').'</li> <li><i class="flaticon-transport"></i> '.trans('web.AVG :').' 30min</li> </ul> </div> <div class="view-menu-liks"> <a class="brd-rd3" href="'.route('web.details.restaurant',$value->translate(\App::getLocale())->name).'" title="" itemprop="url">'.trans('web.View Details').'</a> </div>';
                }
            }
            $data =
                [
                    'dataResult'=>$head.$content.$footer
                ];
            return $data;
        }
    }

    #restaurantsSearch
    public function restaurantSearch(Request $request)
    {
        try{

            $typeRestaurant = Type::with('restaurantType')
                ->whereHas('restaurantType',function ($q){
                    $q->where('status',1);
                })
                ->where('status','=',1)
                ->get();
            if ($request->search != null)
            {
                $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant',
                    'menuRestaurant.menuDetailsRestaurant','restaurantTranslations')
                    ->whereHas('restaurantTranslations',function ($q) use ($request){
                        $q->where('name','LIKE','%'.$request->search.'%');
                        $q->where('locale',\App::getLocale());
                    })
                    ->get();
            }
            else
            {
                $restaurants = Restaurant::with('locationRestaurant','ratingRestaurant','menuRestaurant.menuDetailsRestaurant')
                    ->orderBy('created_at','DESC')
                    ->get();
            }
            return view('frontend.restaurants.all-restaurants',compact('restaurants','typeRestaurant'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function mostSelling()
    {
        $restaurant = Restaurant::with('orders')->get();

        return view('frontend.most-selling.most-selling',compact('restaurant'));
    }

    public function getViewRestaurant($restaurant)
    {
        try{
            $restaurants = Restaurant::with('restaurantTranslations','menuRestaurant',
                'menuRestaurant.menuDetailsRestaurant','workingHours','ratingRestaurant')
                ->whereHas('restaurantTranslations',function ($q) use ($restaurant){
                    $q->where('name',$restaurant);
                    $q->where('locale',\App::getLocale());
                })->first();

            $rating = ratingRestaurant($restaurants->id);


            CurrentUrl();
            return view('frontend.most-selling.viewRestaurant',compact('restaurants','rating'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public  function stat($id){
        $state=State::where('city_id',$id)->get();
        foreach ($state as $stat){
            $sate_trans=  StateTranslation::where('state_id',$stat->id)->select('name','id')->where('locale',\App::getLocale())->get();
            return $sate_trans;
        }
    }
}
