<?php

namespace App\Http\Controllers\Api;

use App\AddressOrder;
use App\Admin;
use App\Cart;
use App\ChargingWallet;
use App\City;
use App\CityTranslation;
use App\Mail\UserResetPassword;
use App\ManageShift;
use App\Menu;
use App\MenuDetails;
use App\MenuDetailsTranslation;
use App\MenuTranslation;
use App\Notifications\RegisterUsers;
use App\Offer;
use App\Order;
use App\OrderDetails;
use App\Package;
use App\PackageTranslation;
use App\PayOfRestaurant;
use App\Privacy;
use App\PrivacyTranslation;
use App\Question;
use App\QuestionTranslation;
use App\Rating;
use App\RatingRestaurant;
use App\Restaurant;
use App\RestaurantTranslation;
use App\SendShare;
use App\State;
use App\StatementTransaction;
use App\StateTranslation;
use App\Type;
use App\TypeTranslation;
use App\User;
use App\UserWalletDetails;
use App\Wallet;
use Carbon\Carbon;
use Edujugon\PushNotification\PushNotification;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
class ApiController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api')->except([
            'getCity',
            'getState',
            'register',
            'login',
            'resetPassword',
            'getType',
            'searchRestaurant',
            'getMenu',
            'getMenuDetails',
            'getPackage',
            'gerFAQ',
            'getPrivacy',
            'getOffersRestaurant',
            'getOffers',
            'allRestaurants',
            'updatePlayerId',
        ]);
    }

    #get city
    public function getCity(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang' => 'required|in:en,ar',
                ],[],
                [
                    'lang'=>trans('admin.Language')
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $city = City::with('cityTranslatable')
                ->where('status',1)
                ->get();
            if ($city->isEmpty())
            {
                return $this->NotFountResponse();
            }
            else
            {
                for ($i=0; $i<count($city); $i++)
                {
                    $details [] =
                        [
                            'id' =>  $city[$i]->id,
                            'name' =>  $city[$i]->cityTranslatable()->where('locale',$request->lang)->value('name'),
                        ];
                }
                return $this->apiResponse($details,'',200);
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #get state
    public function getState(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang' => 'required|in:en,ar',
                    'city_id'=>'required|exists:cities,id',
                ],[],
                [
                    'lang'=>trans('admin.Language'),
                    'city_id'=>trans('admin.city name')
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $state = State::with('stateTranslatable')
                ->where('status',1)
                ->where('city_id',$request->city_id)
                ->get();
            if ($state->isEmpty())
            {
                return $this->NotFountResponse();
            }
            else
            {
                for ($i=0; $i<count($state); $i++)
                {
                    $details [] =
                        [
                            'id' =>  $state[$i]->id,
                            'name' =>  $state[$i]->stateTranslatable()->where('locale',$request->lang)->value('name'),
                        ];
                }
                return $this->apiResponse($details,'',200);
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #get Type
    public function getType(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang' => 'required|in:en,ar',
                ],[],
                [
                    'lang'=>trans('admin.Language'),
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $type = Type::with('typeTranslatable')
                ->where('status',1)
                ->get();
            if ($type->isEmpty())
            {
                return $this->NotFountResponse();
            }
            else
            {
                for ($i=0; $i<count($type); $i++)
                {
                    $details [] =
                        [
                            'id' =>  $type[$i]->id,
                            'name' =>  $type[$i]->typeTranslatable()->where('locale',$request->lang)->value('name'),
                        ];
                }
                return $this->apiResponse($details,'',200);
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #register
    public function register(Request $request)
    {
        try{
            $filename ='';
            $validator = \Validator::make($request->all(),
                [
                    'firstName' => 'required|min:3',
                    'email'=>'required|string|email|unique:admins|unique:users',
                    'password'=>'required|min:1',
                    'image'=>'sometimes|nullable|'.validateImage(),
                    'phone'=>'sometimes|nullable|unique:users',
                    'address'=>'sometimes|nullable|string',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            if($request->file('image'))
            {
                $filename = uploadImages($request->image,'users/','');
            }
            $user = new User();
            $user->firstName = $request->get('firstName');
            $user->lastName = '';
            $user->email = $request->get('email');
            $user->password = \Hash::make($request->get('password'));
            $user->image = $filename;
            $user->phone = $request->get('phone');
            $user->address = $request->get('address');
            $user->user_token = Str::random(60);
            $user->save();

            if ($user->save())
            {
                Wallet::create([
                    'user_id' => $user->id,
                ]);
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $date = new \DateTime();

                $admin = Admin::where('userType','super_admin')->first();
                $admin->notify(new RegisterUsers($user->id,trans('admin.This user register now '.$user->firstName. ' '.$user->lastName),$date));
                /* return $this->apiResponse(json_encode((string) $response->getBody(),true),'',200);*/
                $credentials = request(['email', 'password']);
                if(!\Auth::guard('web')->attempt($credentials))
                {
                    return $this->apiResponse(null,'Unauthorized',401);
                }
                else
                {
                    $user = $request->user();
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    if ($request->remember_me)
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    $token->save();

                    return response()->json([
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                    ]);
                }
                //return $this->apiResponse('successfully registered','',200);
            }
            else
            {
                return $this->apiResponse('Registration failed Please try again','',200);
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #getPackage
    public function getPackage(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang' => 'required|in:en,ar',
                ],[],
                [
                    'lang'=>trans('admin.Language'),
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $package = Package::with('packageTranslatable')
                ->where('status',1)
                ->get();
            if ($package->isEmpty())
            {
                return $this->NotFountResponse();
            }
            else
            {
                for ($i=0; $i<count($package); $i++)
                {
                    $details [] =
                        [
                            'id' =>  $package[$i]->id,
                            'name' =>  $package[$i]->packageTranslatable()->where('locale',$request->lang)->value('name'),
                            'price' =>  $package[$i]->price,
                            'point' =>  $package[$i]->point,

                        ];
                }
                return $this->apiResponse($details,'',200);
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }
    }

    #login
    public function login(Request $request)
    {
        try{

            $validator = \Validator::make($request->all(),
                [
                    'email'=>'required|string|email|exists:users,email',
                    'password'=>'required|min:1',
                    'remember_me'=>'sometimes|nullable|string|boolean',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            else
            {
                $credentials = request(['email', 'password']);
                if(!\Auth::guard('web')->attempt($credentials))
                {
                    return $this->apiResponse(null,'E-mail or Password is not correct',401);
                }
                else
                {
                    $user = $request->user();
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    if ($request->remember_me)
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    $token->save();

                    return response()->json([
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                    ]);
                }
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }


    }

    #updatePlayerId
    public function updatePlayerId(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'email'=>'required|string|email|exists:users,email',
                'player_id'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        else
        {
            $user =  \DB::table('users')->where('email','=',$request->email)
                ->update(['player_id' => $request->player_id]);
            if ($user)
            {
                return $this->apiResponse('done updated player id','',200);
            }
            else
            {
                return $this->apiResponse('','please try again',420);
            }

        }
    }

    #rest password
    public function resetPassword(Request $request)
    {
        try{

            $validator = \Validator::make($request->all(),
                [
                    'email'=>'required|string|email|exists:users,email',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            else
            {
                $user = User::where('email',$request->email)->first();
                if (empty($user))
                {
                    return $this->apiResponse('Please login first','',404);
                }
                else
                {
                    $token = app('auth.password.broker')->createToken($user);
                    $data = \DB::table('password_resets')->insert(
                        [
                            'email'=>$user->email,
                            'token'=>$token,
                            'created_at'=>Carbon::now()
                        ]);
                    \Mail::to($user->email)->send(new UserResetPassword(['data'=>$user,'token'=>$token]));
                    return $this->apiResponse('The URL has been sent to reset the password','',200);
                }
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #search
    public function searchRestaurant(Request $request)
    {
        try{

            $validator = \Validator::make($request->all(),
                [
                    'city_id'=>'required|exists:cities,id',
                    'state_id'=>'required|exists:states,id',
                    'type_id'=>'required|exists:types,id',
                    'lang'=>'required|in:ar,en',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $restaurant = Restaurant::with('locationRestaurant','ratingRestaurant','restaurantTranslations')
                ->whereHas('locationRestaurant',function ($q) use ($request){
                    $q->where('city_id',$request->city_id)
                        ->where('state_id',$request->state_id);
                })->where('type_id','=',$request->type_id)
                ->get();
            if ($restaurant->isEmpty())
            {
                return $this->apiResponse('','not found data',404);
            }
            else
            {
                for ($i=0; $i<count($restaurant); $i++)
                {
                    $rating = RatingRestaurant::where('restaurant_id',$restaurant[$i]->id)->get();
                    if ($rating->isEmpty())
                    {
                        $data = "No Rating" ;
                    }
                    else
                    {
                        $total = $rating->sum('rating') / count($rating);

                        $data = number_format((int)$total, 0, '.', '');
                    }


                    $details [] =
                        [
                            'id' =>  $restaurant[$i]->id,
                            'name' =>  $restaurant[$i]->restaurantTranslations()->where('locale',$request->lang)->value('name'),
                            'phone' =>  $restaurant[$i]->phone == null ? '' :  $restaurant[$i]->phone,
                            'logo' =>  $restaurant[$i]->logoPath,
                            'image' => $restaurant[$i]->imagePath,
                            'status' =>  $restaurant[$i]->status == 1 ? 'Active' :  'In-Active',
                            'rating' => $data,
                            'countRating' => count($rating)
                        ];
                }

                return $this->apiResponse($details,'',200);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #menu
    public function getMenu(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'restaurant_id'=>'required|exists:restaurants,id',
                    'lang'=>'required|in:ar,en',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $data = MenuDetails::where('restaurant_id',$request->restaurant_id)->get();
            $total = $acreage = 0;
            if (count($data) > 0)
            {
                for ($i=0; $i<count($data); $i++)
                {
                    $total += $data[$i]->period;
                }
                $acreage = $total / count($data);
            }
            else
            {
                $acreage = 20;
            }


            $menu = Menu::with('menuTranslatable')
                ->where('restaurant_id','=',$request->restaurant_id)
                ->where('status','=',1)->get();
            if ($menu->isEmpty())
            {
                return $this->apiResponse('','not found data',404);
            }
            else
            {
                for ($i=0; $i<count($menu); $i++)
                {
                    $details [] =
                        [
                            'id' =>  $menu[$i]->id,
                            'name' =>  $menu[$i]->menuTranslatable()->where('locale',$request->lang)->value('name'),
                        ];
                }

                return $this->apiResponse(['data'=>$details,'period'=>$acreage],'',200);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #getMenuDetails
    public function getMenuDetails(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'menu_id'=>'required|exists:menus,id',
                    'lang'=>'required|in:ar,en',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $menuDetails = MenuDetails::with('menuDetailsTranslatable')
                ->where('menu_id',$request->menu_id)
                ->where('status','=',1)
                ->get();


            if ($menuDetails->isEmpty())
            {
                return $this->apiResponse('','not found data',404);
            }
            else
            {
                for ($i=0; $i<count($menuDetails); $i++)
                {
                    $details [] =
                        [
                            'id' =>  $menuDetails[$i]->id,
                            'name' =>  $menuDetails[$i]->menuDetailsTranslatable()->where('locale',$request->lang)->value('name'),
                            'description' => $menuDetails[$i]->description,
                            'price' =>  percentageOrder($menuDetails[$i]->price),
                            'period'=> $menuDetails[$i]->period,
                            'image' => $menuDetails[$i]->imagePath,
                        ];
                }

                return $this->apiResponse($details,'',200);
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #cart
    public function addCart(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'qty' =>'required',
                    'menu_details_id'=>'required|exists:menu_details,id',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            //return $request->user();
            $user = User::find($request->user()->id);


            $restaurant = MenuDetails::find($request->menu_details_id);

            if (empty($user))
            {
                return $this->apiResponse('','We can\'t find a user with that this id',404);
            }
            else
            {
                $checkCart = Cart::where('user_id',$user->id)->first();

                $dataCart = Cart::where('user_id',$user->id)
                    ->where('menu_details_id',$request->menu_details_id)->get();
                if ($dataCart->isEmpty())
                {
                    $cart = new Cart();
                    $cart->restaurant_id = $restaurant->restaurant_id;
                    $cart->user_id = $user->id;
                    $cart->menu_details_id = $request->get('menu_details_id');
                    $cart->qty = $request->qty;
                    $cart->save();
                    if ($cart->save())
                    {
                        return $this->apiResponse(['message'=>'save cart success','cartId'=>$cart->id],'',200);
                    }
                    else
                    {
                        return $this->apiResponse('','please try again',500);
                    }
                }
                else
                {
                    if ($restaurant->restaurant_id == $checkCart->restaurant_id)
                    {
                        $old = $dataCart[0]->qty;
                        $cartID = $dataCart[0]->id;
                        $update = \DB::table('carts')
                            ->where('user_id','=',$user->id)
                            ->where('menu_details_id','=',$request->get('menu_details_id'))
                            ->where('restaurant_id','=',$restaurant->restaurant_id)
                            ->update(['qty'=>$old + $request->qty]);
                        if ($update)
                        {
                            return $this->apiResponse(['message'=>'update cart success','cartId'=>$cartID],'',200);
                        }
                        else
                        {
                            return $this->apiResponse('','please try again',500);
                        }
                    }
                    else
                    {
                        return $this->apiResponse('','Please choose the meal from the same restaurant',400);
                    }

                }
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }


    }

    #deleteCart
    public function deleteCart(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'menu_details_id'=>'required|exists:menu_details,id',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $user = User::find($request->user()->id);
            $restaurant = MenuDetails::find($request->get('menu_details_id'));

            $dataCheck = MenuDetails::find($request->menu_details_id);
            if (empty($user))
            {
                return $this->apiResponse('','We can\'t find a user with that this id',404);
            }
            else
            {
                $dataCart = Cart::where('user_id',$user->id)
                    ->where('menu_details_id',$request->menu_details_id)->get();
                if ($dataCart->isEmpty())
                {
                    return $this->apiResponse('','cart is empty',404);
                }
                else
                {
                    if ($restaurant->restaurant_id == $dataCheck->restaurant_id)
                    {
                        $action = \DB::table('carts')
                            ->where('user_id','=',$user->id)
                            ->where('menu_details_id','=',$request->get('menu_details_id'))
                            ->delete();
                        if ($action)
                        {
                            return $this->apiResponse('delete success','',200);
                        }
                        else
                        {
                            return $this->apiResponse('please try again','',500);
                        }
                    }
                    else
                    {
                        return $this->apiResponse('','Please choose the meal from the same restaurant',400);
                    }
                }
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #minCart
    public function minCart(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'menu_details_id'=>'required|exists:menu_details,id',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $user = User::find($request->user()->id);
            $restaurant = MenuDetails::find($request->get('menu_details_id'));

            $checkCart = Cart::where('user_id',$user->id)->first();
            if (empty($user))
            {
                return $this->apiResponse('','We can\'t find a user with that this id',404);
            }
            else
            {
                if (empty($user))
                {
                    return $this->apiResponse('','We can\'t find a user with that this id',404);
                }
                else
                {
                    $cart = \DB::table('carts')
                        ->where('user_id','=',$user->id)
                        ->where('menu_details_id','=',$request->get('menu_details_id'))
                        ->get();
                    if ($cart->isEmpty())
                    {
                        return $this->apiResponse('cart is empty','',400);
                    }
                    elseif ( $cart[0]->qty == 0)
                    {
                        if ($restaurant->restaurant_id == $checkCart->restaurant_id)
                        {
                            \DB::table('carts')
                                ->where('user_id','=',$user->id)
                                ->where('menu_details_id','=',$request->get('menu_details_id'))->delete();

                            return $this->apiResponse('delete all cart success','',200);
                        }
                        else
                        {
                            return $this->apiResponse('','Please choose the meal from the same restaurant',400);
                        }
                    }
                    else
                    {
                        if ($restaurant->restaurant_id == $checkCart->restaurant_id)
                        {
                            $action = \DB::table('carts')
                                ->where('user_id','=',$user->id)
                                ->where('menu_details_id','=',$request->get('menu_details_id'))
                                ->update(['qty'=>$cart[0]->qty - 1]);
                            if ($action)
                            {
                                return $this->apiResponse('min qty cart success','',200);
                            }
                            else
                            {
                                return $this->apiResponse('please try again','',200);
                            }
                        }
                        else
                        {
                            return $this->apiResponse('','Please choose the meal from the same restaurant',400);
                        }

                    }
                }

            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }


    }

    #plusCart
    public function plusCart(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'menu_details_id'=>'required|exists:menu_details,id',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $user = User::find($request->user()->id);
            $restaurant = MenuDetails::find($request->get('menu_details_id'));

            $checkCart = Cart::where('user_id',$user->id)->first();
            if (empty($user))
            {
                return $this->apiResponse('','We can\'t find a user with that this id',404);
            }
            else
            {
                if (empty($user))
                {
                    return $this->apiResponse('','We can\'t find a user with that this id',404);
                }
                else
                {
                    $cart = \DB::table('carts')
                        ->where('user_id','=',$user->id)
                        ->where('menu_details_id','=',$request->get('menu_details_id'))
                        ->get();
                    if ($cart->isEmpty())
                    {
                        return $this->apiResponse('cart is empty','',400);
                    }
                    else
                    {
                        if ($restaurant->restaurant_id == $checkCart->restaurant_id)
                        {
                            $action = \DB::table('carts')
                                ->where('user_id','=',$user->id)
                                ->where('menu_details_id','=',$request->get('menu_details_id'))
                                ->update(['qty'=>$cart[0]->qty + 1]);
                            if ($action)
                            {
                                return $this->apiResponse('add cart success','',200);
                            }
                            else
                            {
                                return $this->apiResponse('please try again','',200);
                            }
                        }
                        else
                        {
                            return $this->apiResponse('','Please choose the meal from the same restaurant',400);
                        }
                    }
                }

            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }


    }

    #myCart
    public function myCart(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang'=>'required|in:en,ar',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }

            $user = User::find($request->user()->id);

            if (empty($user))
            {
                return $this->apiResponse('','We can\'t find a user with that this id',404);
            }
            else
            {

                if (empty($user))
                {
                    return $this->apiResponse('','We can\'t find a user with that this id',404);
                }
                else
                {
                    $cart = Cart::where('user_id',$user->id)->get();
                    if ($cart->isEmpty())
                    {
                        return $this->apiResponse('','Cart is Empty',404);
                    }
                    else
                    {
                        $total = 0;
                        for ($i = 0; $i<count($cart); $i++)
                        {
                            $total += percentageOrder(MenuDetails::find($cart[$i]->menu_details_id)->price) * $cart[$i]->qty;
                            $details [] =
                                [
                                    'cartId'=>$cart[$i]->id,
                                    'menuDetailsId'=>$cart[$i]->menu_details_id,
                                    'mealName'=> MenuDetails::find($cart[$i]->menu_details_id)->translate($request->lang)->name,
                                    'mealPrice'=> percentageOrder(MenuDetails::find($cart[$i]->menu_details_id)->price),
                                    'mealImage'=> 'public/upload/meal/'.MenuDetails::find($cart[$i]->menu_details_id)->image,
                                    'qty'=>$cart[$i]->qty,
                                    'totalPrice' =>$total,
                                ];
                        }
                        return $this->apiResponse($details,'',200);
                    }
                }

            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }


    }


    #logout
    public function logout(Request $request)
    {
        try{

            $request->user()->token()->revoke();
            return $this->apiResponse('Successfully logged out','',200);

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #data user use passport used profile
    public function infoUser(Request $request)
    {
        try{

            $wallet = Wallet::where('user_id',$request->user()->id)->first();
            if (empty($wallet))
            {
                return $this->apiResponse('','please call support system',404);
            }
            else
            {
                $data =
                    [
                        'name'=> $request->user()->firstName.' '.$request->user()->lastName,
                        'email'=> $request->user()->email,
                        'phone'=> $request->user()->phone == null ? '' : $request->user()->phone,
                        'address'=> $request->user()->address == null ? '' :$request->user()->address,
                        'image'=> $request->user()->image == null ? 'public/' .
                            'upload/images/default.png' : '/public/upload/users/'.$request->user()->image,
                        'amount' => $wallet->account,
                        'status' => $wallet->status == 1 ? 'Active' : 'In-Active'
                    ];
                return $this->apiResponse($data,'',200);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #update profile
    public function updateProfile(Request $request)
    {
        try{
            $filename ='';
            $validator = \Validator::make($request->all(),
                [
                    'firstName' => 'required|min:3',
                    'email'=>'required|string|email|unique:admins|unique:users,email,'.$request->user()->id,
                    'image'=>'sometimes|nullable|'.validateImage(),
                    'phone'=>'sometimes|nullable',
                    'address'=>'sometimes|nullable|string',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            else
            {
                $user = User::find($request->user()->id);
                if (empty($user))
                {
                    $request->user()->token()->revoke();
                    return $this->apiResponse('Please login first','',404);
                }
                else
                {
                    if($request->file('image'))
                    {
                        $filename = uploadImages($request->image,'users/',$request->user()->image);
                    }
                    else
                    {
                        $filename = $request->user()->image ;
                    }
                    $user->firstName = $request->firstName;
                    $user->lastName = '';
                    $user->email = $request->email;
                    $user->phone = $request->phone;
                    $user->address = $request->address;
                    $user->image = $filename;
                    $user->save();
                    if ($user->save())
                    {
                        $data =
                            [
                                'name'=> $user->firstName.' '.$user->lastName,
                                'email'=> $user->email,
                                'phone'=> $user->phone == null ? '' : $user->phone,
                                'address'=> $user->address == null ? '' :$user->address,
                                'image'=> $user->image == null ? '/upload/images/default.png': '/public/upload/users/'.$user->image,
                                'message'=> 'Data changed successfully',
                            ];
                        return $this->apiResponse($data,'',200);
                    }
                    else
                    {
                        return $this->apiResponse('Please try again','',400);
                    }
                }
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #change password
    public function updatePassword(Request $request)
    {
        try{

            $validator = \Validator::make($request->all(),
                [
                    'old_password'=>'required|string|min:3',
                    'new_password' => 'min:3|required_with:confirm_password|same:confirm_password',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            else
            {

            }
            $user = User::find($request->user()->id);
            if (empty($user))
            {
                $request->user()->token()->revoke();
                return $this->apiResponse('Please login first','',404);
            }
            else
            {
                if (\Hash::check($request->old_password,$user->password))
                {
                    $user->password = \Hash::make($request->new_password);
                    $user->save();
                    if ($user->save())
                    {
                        return $this->apiResponse('Password changed successfully','',200);
                    }
                    else
                    {
                        return $this->apiResponse('Please try again','',400);
                    }
                }
                else
                {
                    return $this->apiResponse('Please make sure your old password','',400);
                }
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #gerFAQ
    public function gerFAQ(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang'=>'required|in:ar,en',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $faq = QuestionTranslation::join('questions','question_translations.question_id','=','questions.id')
                ->where('questions.status',1)
                ->where('question_translations.locale',$request->lang)
                ->select('question_translations.title as title','question_translations.description as description')
                ->get();
            if ($faq->isEmpty())
            {
                return $this->NotFountResponse();
            }
            else
            {
                return $this->apiResponse($faq,'',200);
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #gerFAQ
    public function getPrivacy(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang'=>'required|in:ar,en',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $privacy = PrivacyTranslation::join('privacies','privacy_translations.privacy_id','=','privacies.id')
                ->where('privacies.status',1)
                ->where('privacy_translations.locale',$request->lang)
                ->select('privacy_translations.title as title','privacy_translations.description as description')
                ->get();
            if ($privacy->isEmpty())
            {
                return $this->NotFountResponse();
            }
            else
            {
                return $this->apiResponse($privacy,'',200);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #order
    public function order(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'address'=>'required|min:3',
                    'payment_type'=>'required|in:cart,wallet,cash',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $user = User::find($request->user()->id);
            if (empty($user))
            {
                return $this->apiResponse('','We can\'t find a user with that this id',404);
            }
            else
            {
                if ($request->payment_type == 'wallet')
                {
                    $wallet = Wallet::where('user_id',$request->user()->id)->first();
                    if (empty($wallet))
                    {
                        return $this->apiResponse('','please call support system',404);
                    }
                    else
                    {
                        $ordinalTotal = 0;
                        $total = 0 ;
                        $cart = Cart::where('user_id',$request->user()->id)->get();
                        if ($cart->isEmpty())
                        {
                            return $this->apiResponse('','We can\'t find a cart with that this users '.$user->firstName.' '.$user->lastName,404);
                        }
                        else
                        {
                            for ($i = 0; $i<count($cart); $i++)
                            {
                                $total += $cart[$i]->qty * percentageOrder(\DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price')) ;
                                $ordinalTotal += $cart[$i]->qty * \DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price');

                            }
                            if ($wallet->account >= $total )
                            {
                                $order = new Order();
                                $order->restaurant_id = $cart[0]->restaurant_id;
                                $order->user_id = $user->id;
                                $order->amount = $total;
                                $order->dateTime = Carbon::now();
                                $order->amount_delivery = deliveryFees()['amountDelivery'];
                                $order->type_shift = deliveryFees()['typeShift'];
                                $order->payment_type = $request->payment_type;

                                $order->status = 'pending';
                                $order->save();
                                if ($order->save())
                                {
                                    createTrip($order->id);
                                    for ($i=0; $i<count($cart); $i++)
                                    {
                                        $orderDetails = new OrderDetails();
                                        $orderDetails->order_id = $order->id;
                                        $orderDetails->menu_details_id = $cart[$i]->menu_details_id;
                                        $orderDetails->price = MenuDetails::find($cart[$i]->menu_details_id)->price;
                                        $orderDetails->qty = $cart[$i]->qty;
                                        $orderDetails->save();

                                    }
                                    $address = new AddressOrder();
                                    $address->address = $request->adress;
                                    $address->order_id = $order->id;
                                    $address->user_id = $user->id;
                                    $address->lat = $request->lat;
                                    $address->lng = $request->lng;
                                    $address->save();
                                }
                                $success = Cart::where('user_id',$request->user()->id)->delete();
                                if ($success)
                                {
                                    $successWallet = \DB::table('wallets')->where('user_id','=',$request->user()->id)
                                        ->update(['account' => $wallet->account - $order->amount ]);
                                    if ($successWallet)
                                    {
                                        $date = new UserWalletDetails();
                                        $date->user_id = $order->user_id;
                                        $date->balance = $wallet->account;
                                        $date->previous = $order->amount;
                                        $date->date = Carbon::now();
                                        $date->save();
                                        if ($date->save())
                                        {
                                            $per = $total - $ordinalTotal;
                                            statementTransaction($ordinalTotal,$user->id,$order->restaurant_id,'user','vendor','paid',$order->id,'wallet');
                                            statementTransaction($per,$user->id,Admin::find(1)->id,'user','admin','paid',$order->id,'wallet');
                                            statementTransaction(deliveryFees()['amountAdmin'],$user->id,Admin::find(1)->id,'user','admin','paid',$order->id,'wallet');
                                            statementTransaction(deliveryFees()['amountMan'],$user->id,null,'user','delivery','paid',$order->id,'wallet');
                                            return $this->apiResponse(['orderId' => $order->id],'',200);
                                        }


                                    }
                                    else
                                    {
                                        return $this->apiResponse('','please try again',500);

                                    }
                                }
                                else
                                {
                                    return $this->apiResponse('','try again',500);
                                }
                            }
                            else
                            {
                                return $this->apiResponse('','price order big account wallet total order = '.$total.' and wallet = '.$wallet->account,400);
                            }
                        }
                    }
                }
                elseif ($request->payment_type == 'cash')
                {
                    $total = $ordinalTotal = 0 ;

                    $cart = Cart::where('user_id',$request->user()->id)->get();
                    if ($cart->isEmpty())
                    {
                        return $this->apiResponse('','We can\'t find a cart with that this users '.$user->firstName.' '.$user->lastName,404);
                    }
                    else
                    {
                        for ($i = 0; $i<count($cart); $i++)
                        {
                            $total += $cart[$i]->qty * percentageOrder(\DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price')) ;
                            $ordinalTotal += $cart[$i]->qty * \DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price');
                        }
                        $order = new Order();
                        $order->restaurant_id = $cart[0]->restaurant_id;
                        $order->user_id = $user->id;
                        $order->amount = $total;
                        $order->dateTime = Carbon::now();
                        $order->amount_delivery = deliveryFees()['amountDelivery'];
                        $order->type_shift = deliveryFees()['typeShift'];
                        $order->payment_type = $request->payment_type;

                        $order->status = 'pending';
                        $order->save();
                        if ($order->save())
                        {
                            createTrip($order->id);
                            statementTransaction($total,$user->id,null,'user','delivery','paid',$order->id,'cash');
                            for ($i=0; $i<count($cart); $i++)
                            {
                                $orderDetails = new OrderDetails();
                                $orderDetails->order_id = $order->id;
                                $orderDetails->menu_details_id = $cart[$i]->menu_details_id;
                                $orderDetails->price = MenuDetails::find($cart[$i]->menu_details_id)->price;
                                $orderDetails->qty = $cart[$i]->qty;
                                $orderDetails->save();

                            }
                            $address = new AddressOrder();
                            $address->address = $request->adress;
                            $address->order_id = $order->id;
                            $address->user_id = $user->id;
                            $address->lat = $request->lat;
                            $address->lng = $request->lng;
                            $address->save();
                        }
                        $success = Cart::where('user_id',$request->user()->id)->delete();
                        if ($success)
                        {
                            return $this->apiResponse(['orderId' => $order->id],'',200);
                        }
                        else
                        {
                            return $this->apiResponse('','try again',500);
                        }
                    }
                }
                elseif ($request->payment_type == 'cart')
                {

                }
                else
                {
                    return $this->apiResponse('','please select payment type',400);
                }


            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }


    }

    #myOrders
    public function myOrders(Request $request)
    {
        try{

            $user = User::find($request->user()->id);
            if (empty($user))
            {
                return $this->apiResponse('','this users not authorization',404);
            }
            $orders = Order::where('user_id',$user->id)
                ->orderBy('created_at','DESC')->get();
            if ($orders->isEmpty())
            {
                return $this->apiResponse('','not found orders',200);
            }
            else
            {
                for ($i=0; $i<count($orders); $i++)
                {
                    $details[] =
                        [

                            'orderId'=>$orders[$i]->id,
                            'amount'=>$orders[$i]->amount,
                            'amountDelivery'=>$orders[$i]->amount_delivery,
                            'typeShift'=>$orders[$i]->type_shift == 'morning' ? 'Morning' : 'Night',
                            'status'=>$orders[$i]->status,
                            'date'=>date('d-m-Y',strtotime($orders[$i]->created_at)),
                            'time'=>date('h:m A',strtotime($orders[$i]->created_at)),
                            'totalPrice'=>$orders[$i]->amount + $orders[$i]->amount_delivery,
                        ];
                }
                return $this->apiResponse($details,'',200);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #myOrderDetails
    public function myOrderDetails(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'order_id'=>'required',
                    'lang'=>'required|in:en,ar',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $user = User::find($request->user()->id);
            if (empty($user))
            {
                return $this->apiResponse('','this users not authorization',404);
            }
            $orders = OrderDetails::where('order_id',$request->order_id)
                ->orderBy('created_at','DESC')->get();
            if ($orders->isEmpty())
            {
                return $this->apiResponse('','not found orders',200);
            }
            else
            {
                for ($i=0; $i<count($orders); $i++)
                {
                    $details[] =
                        [

                            'mealName'=> MenuDetails::find($orders[$i]->menu_details_id)->translate($request->lang)->name,
                            'price'=> $orders[$i]->price,
                            'qty'=> $orders[$i]->qty,
                            'total' =>$orders[$i]->price * $orders[$i]->qty

                        ];
                }
                return $this->apiResponse($details,'',200);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #rating
    public function rating(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'rating'=>'required|min:1|max:5',
                    'comment'=>'required',
                    'restaurant_id'=>'required|exists:restaurants,id',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $user = User::find($request->user()->id);
            if (empty($user))
            {
                return $this->apiResponse('','We can\'t find a user with that this id',404);
            }
            else
            {
                $rating = RatingRestaurant::where('user_id',$user->id)
                    ->where('restaurant_id',$request->restaurant_id)->first();
                if (empty($rating))
                {
                    $rating = new RatingRestaurant();
                    $rating->user_id = $user->id;
                    $rating->restaurant_id = $request->restaurant_id;
                    $rating->rating = $request->rating;
                    $rating->comment = $request->comment;
                    $rating->save();
                    if ($rating->save())
                    {
                        return $this->apiResponse('rating success','',200);
                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }
                }
                else
                {
                    $data = \DB::table('rating_restaurants')
                        ->where('user_id','=',$user->id)
                        ->where('restaurant_id','=',$request->restaurant_id)
                        ->update([
                            'rating' => $request->rating,
                            'comment' => $request->comment,
                        ]);
                    if ($data)
                    {
                        return $this->apiResponse('rating success','',200);
                    }
                    else
                    {
                        return $this->apiResponse('please try again','',500);
                    }
                }
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #getOffersRestaurant
    public function getOffersRestaurant(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang'=>'required|in:en,ar',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $getOffersRestaurant = Offer::join('restaurants','restaurants.id','=','offers.restaurant_id')
                ->where('restaurants.status',1)
                ->where('offers.status',1)
                ->whereDate('offers.fromDate', '<=', date("Y-m-d",strtotime(Carbon::now())))
                ->whereDate('offers.toDate', '>=', date("Y-m-d",strtotime(Carbon::now())))
                ->select('restaurants.id as restaurantId')
                ->distinct()->get(['restaurantId']);

            if (empty($getOffersRestaurant))
            {
                return $this->apiResponse('','We can\'t find a offers',404);
            }
            else
            {
                for ($i=0; $i<count($getOffersRestaurant); $i++)
                {
                    $rating = RatingRestaurant::where('restaurant_id',$getOffersRestaurant[$i]->restaurantId)->get();
                    if ($rating->isEmpty())
                    {
                        $data = "No Rating" ;
                    }
                    else
                    {
                        $total = $rating->sum('rating') / count($rating);

                        $data = number_format((int)$total, 0, '.', '');
                    }
                    $details [] =
                        [
                            'restaurantId'=>$getOffersRestaurant[$i]->restaurantId,
                            'restaurantName'=>Restaurant::find($getOffersRestaurant[$i]->restaurantId)->translate($request->lang)->name,
                            'restaurantImage'=>Restaurant::find($getOffersRestaurant[$i]->restaurantId)->imagePath,
                            'restaurantLogo'=>Restaurant::find($getOffersRestaurant[$i]->restaurantId)->logoPath,
                            'rating' =>$data,
                        ];
                }
                return $this->apiResponse($details,'',200);

            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #getOffers
    public function getOffers(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang'=>'required|in:en,ar',
                    'restaurantId' => 'required|exists:restaurants,id'
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $offers = Offer::where('restaurant_id',$request->restaurantId)
                ->where('status',1)
                ->whereDate('fromDate', '<=', date("Y-m-d",strtotime(Carbon::now())))
                ->whereDate('toDate', '>=', date("Y-m-d",strtotime(Carbon::now())))
                ->get();

            if (empty($offers))
            {
                return $this->apiResponse('','We can\'t find a offers',404);
            }
            else
            {
                for ($i=0; $i<count($offers); $i++)
                {
                    $details [] =
                        [
                            'offerId'=>$offers[$i]->id,
                            'menuDetailsId'=>$offers[$i]->menu_details_id,
                            'menuDetailsName'=>MenuDetails::find($offers[$i]->menu_details_id)->translate($request->lang)->name,
                            'originalPrice'=>percentageOrder(MenuDetails::find($offers[$i]->menu_details_id)->price),
                            'offerPrice'=>percentageOrder($offers[$i]->price),
                            'fromDate'=>date('d-m-Y',strtotime($offers[$i]->fromDate)),
                            'toDate'=>date('d-m-Y',strtotime($offers[$i]->toDate)),
                            'fromTime'=>date('H:m A',strtotime($offers[$i]->fromTime)),
                            'toTime'=>date('H:m A',strtotime($offers[$i]->toTime)),
                            'menuDetailsImage'=>MenuDetails::find($offers[$i]->menu_details_id)->imagePath,
                        ];
                }
                return $this->apiResponse($details,'',200);

            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #chargingWallets
    public function chargingWallets(Request $request)
    {
        try{

            $validator = \Validator::make($request->all(),
                [
                    'package_id' => 'required|exists:packages,id'
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $user = User::find($request->user()->id);
            $wallet = Wallet::where('user_id',$user->id)->first();
            $wallet->account;
            if (empty($user))
            {
                return $this->apiResponse('','this user not Authorization',404);
            }
            else
            {
                $charging = new ChargingWallet();
                $charging->user_id = $user->id;
                $charging->package_id = $request->package_id;
                $charging->date = Carbon::now();
                $charging->save();
                if ($charging->save())
                {
                    $data = \DB::table('wallets')
                        ->where('user_id','=',$charging->user_id)
                        ->update(['account'=>$wallet->account + Package::find($charging->package_id)->point]);
                    if ($data)
                    {
                        return $this->apiResponse('done charging wallet success','',200);
                    }
                    else
                    {
                        return $this->apiResponse('','please check wallet',500);
                    }
                }
                else
                {
                    return $this->apiResponse('','please check network',500);
                }
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }


    }

    #myWallets
    public function myWallets(Request $request)
    {
        try{

            $user = User::find($request->user()->id);
            if (empty($user))
            {
                return $this->apiResponse('','this users not authorization',404);
            }
            $wallet = Wallet::where('user_id',$user->id)->first();
            if (empty($wallet))
            {
                return $this->apiResponse('','please call support system',404);
            }
            else
            {
                $details =
                    [
                        'amount' => $wallet->account,
                        'status' => $wallet->status == 1 ? 'Active' : 'In-Active'
                    ];
                return $this->apiResponse($details,'',200);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #dateWallet
    public function dateWallet(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'lang' => 'required|in:ar,en'
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $user = User::find($request->user()->id);
            if (empty($user))
            {
                return $this->apiResponse('','this users not authorization',404);
            }
            $wallet = ChargingWallet::where('user_id',$user->id)->get();
            if ($wallet->isEmpty())
            {
                return $this->apiResponse('','please Charging wallet first',404);
            }
            else
            {
                for ($i =0; $i<count($wallet); $i++)
                {
                    $details[] =
                        [
                            'packageName'=>Package::find($wallet[$i]->package_id)->transalte($request->lang)->name,
                            'packagePrice'=>Package::find($wallet[$i]->package_id)->price,
                            'packagePoint'=>Package::find($wallet[$i]->package_id)->point,
                            'date'=>date('d-m-Y',strtotime($wallet[$i]->date)),
                            'time'=>date('h:m A',strtotime($wallet[$i]->date)),
                        ];
                }
                return $this->apiResponse($details,'',200);
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }
    }

    #previousTransactions
    public function previousTransactions(Request $request)
    {
        $user = User::find($request->user()->id);
        if (empty($user))
        {
            return $this->apiResponse('','this users not authorization',404);
        }
        else
        {
            $data = UserWalletDetails::where('user_id',$request->user()->id)->get();
            if ($data->isEmpty())
            {
                return $this->apiResponse('','not found data',404);
            }
            else
            {
                for ($a=0; $a<count($data); $a++)
                {
                    $details[] =
                        [
                            'balance' => $data[$a]->balance,
                            'previous' => $data[$a]->previous,
                            'date' => date('d-m-Y',strtotime($data[$a]->date)),
                            'time' => date('h:m A',strtotime($data[$a]->date)),
                        ];
                }
                return $this->apiResponse($details,'',200);
            }
        }
    }

    #all restaurants
    public function allRestaurants(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'lang' => 'required|in:ar,en'
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $restaurants = Restaurant::where('status','=',1)->get();
        if ($restaurants->isEmpty())
        {
            return $this->apiResponse('','not found restaurants',404);
        }
        else
        {
            for ($i=0; $i<count($restaurants); $i++)
            {
                $details[] =
                    [
                        'id' => $restaurants[$i]->id,
                        'name' =>$restaurants[$i]->translate($request->lang)->name,
                    ];
            }
            return $this->apiResponse($details,'',200);
        }

    }

    #pay of restaurant

    public function payOfRestaurant(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'restaurant_id' =>'required|exists:restaurants,id',
                'amount' =>'required'
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        else
        {
            $user = User::find($request->user()->id);
            if (empty($user))
            {
                return $this->apiResponse('','this users not login',400);
            }
            else
            {
                $wallet = Wallet::where('user_id',$user->id)->first();
                $money = $wallet->account;
                if ($money >= $request->amount)
                {
                    $pay = new PayOfRestaurant();
                    $pay->user_id = $user->id;
                    $pay->restaurant_id = $request->restaurant_id;
                    $pay->amount = $money - $request->amount;
                    $pay->save();
                    if ($pay->save())
                    {
                        $success = \DB::table('wallets')
                            ->where('user_id','=',$user->id)
                            ->update(['account'=>$money - $request->amount]);
                        statementTransaction($pay->amount,$user->id,$pay->restaurant_id,'user','vendor','paid','','wallet');
                    }
                    if ($success)
                    {
                        $date = new UserWalletDetails();
                        $date->user_id = $user->id;
                        $date->balance = $wallet->account;
                        $date->previous = $money - $request->amount;
                        $date->date = Carbon::now();
                        $date->save();
                        return $this->apiResponse('Payment successful','',200);
                    }
                    else
                    {
                        return $this->apiResponse('','please try again',400);
                    }
                }
                else
                {
                    return $this->apiResponse('','please check in wallet '.$request->amount .' is bigger then '.$money,400);
                }

            }

        }
    }

    #checkPhoneShare
    public function checkPhoneShare(Request $request)
    {
//        if($request->user()->check_before == 0){


        $validator = \Validator::make($request->all(),
            [
                'deviceType' =>'required',
                'phone' =>'required',
                'amount'=>'required',
                'restaurant_id' =>'required|exists:restaurants,id',

            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $user = User::find($request->user()->id);
        $user_sender=User::find($request->user()->id);
        if (empty($user))
        {
            return $this->apiResponse('','this users  not found',404);
        }
        else
        {
            $array = explode(' ', $request->phone);
            $num=1;
            $count_use=count($array)+$num;
            foreach ($array as $key=> $arr){
                $phone=$arr;
                $users = User::where('phone',$phone)->first();

                if (($users) == null)
                {
                    return $this->apiResponse('','this phone '.$phone.'not found please enter another',404);
                }

            }
            $wallet_sender = Wallet::where('user_id',$user_sender->id)->first();
//            dd($wallet_sender);
            if($wallet_sender->account > 0){
                foreach ($array as $key=> $arr){
                    $phone=$arr;
                    // dump($array);
                    $users = User::where('phone',$phone)->first();

                    $wallet = Wallet::where('user_id',$users->id)->first();

                    if ($wallet->account < 0)
                    {

                        return $this->apiResponse('','this user not found wallet '.$phone,404);
                    }
                    else
                    {

                        $money = $request->amount / ($count_use);


                        if ($money < 0 )
                        {
                            return $this->apiResponse('','please enter amount true',420);
                        }
                        else
                        {
                            if (checkWallet($users->id,$money) == true)
                            {

                                if ($phone != null)
                                {
                                    if (checkWallet(sendNotifyShare($phone)->id,$money) == true)
                                    {


                                    }
                                    else
                                    {
                                        return $this->apiResponse('','The amount is not enough this phone '.$phone,404);
                                    }
                                }

                            }

                        }

//                    C:\xampp\htdocs\otlob\app\Http\Controllers\Api\ApiController.php
//  dd($users->id);
                        \DB::table('send_shares')->where('send_by','=',$user_sender->id)->where("receive_by",$users->id)->delete();
//                    dd(sendNotifyShare($phone)->id);
                        saveSendShare($user_sender->id,$money,sendNotifyShare($phone)->id,$request->restaurant_id,$request->check_before);


                        $tokens_android = User::where('deviceType','android')->where('phone',$phone) ->pluck('token_app')->toArray();

                        $tokens_ios = User::where('deviceType','ios')->where('phone',$phone) ->pluck('token_app')->toArray();
                        $mag=['firstName' => $request->user()->firstName,"Want to Share payment with you ",'amount' => $money];
                        $share_masg=implode("  ",$mag);
                        // dd($share_masg);
                        if($tokens_android){
                            $this->notification_to_android( $tokens_android ,$share_masg);
                        }
                        if($tokens_ios){
                            $this->notification_to_ios( $tokens_ios , $share_masg);
                        }
                    }
                }
            }else{
                $user_sender=User::find($request->user()->id);
                return $this->apiResponse('','The amount is not enough this phone '.$user_sender->phone,404);

            }




            return $this->apiResponse('done share success','',200);



        }
//        }else{
//            return $this->apiResponse('','you add check before',404);
//
//        }
    }

    //
    public function gettoken(Request $request){
        $validator = \Validator::make($request->all(),
            [
                'token_app'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $user = User::where('user_token',$request->user()->user_token)->first();
        $user->token_app =$request->token_app;
        $user->save();
        return $this->apiResponse('done  success','',200);

    }

    //eslam
    public function getusershare(Request $request){

        $user_id=$request->user()->id;
        $send_shares = SendShare::where('send_by',$user_id)->where('accept',0)->get();

        $data = \DB::table('send_shares')->where('send_shares.send_by',$user_id)->where('accept','!=',3)
            ->select('users.firstName','users.phone','send_shares.money','send_shares.accept')
            ->join('users', 'send_shares.receive_by', '=', 'users.id')
            ->get();



        return response()->json([
            'status' => true,
            'data' =>$data
        ]);

    }
//eslam
    public function getanswar(Request $request){
        $validator = \Validator::make($request->all(),
            [
                'answar'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }

        $user_id=$request->user()->id;

        $send_shares = SendShare::where('receive_by',$user_id)->where('accept',0)->first();
        if($send_shares  == null ){
            return $this->apiResponse('user not share ','',400);

        }else{
            $id=$send_shares->id;
            $send_sh = SendShare::find($id);
            $send_sh->accept =$request->answar;
            $send_sh->save();

            $user_send_share=$send_sh->send_by;
            $user_answare = User::where('id',$send_shares->receive_by)->first();
            $tokens_android = User::where('id',$user_send_share) ->pluck('token_app')->toArray();

            if($send_sh->accept == "1"){

                $msg=[$user_answare->firstName," Accept Share Payment"];


            }else{

                $msg=[$user_answare->firstName," Refuse Share Payment"];

            }

            $share_masg=implode("  ",$msg);
            $push = new PushNotification('fcm');
            $push->setMessage([
                'data' => [
                    'title'=>'Share payment Raaleat',
                    'message'=>"$share_masg",
                ]
            ])
                ->setApiKey('AAAAJxLQ_Ok:APA91bEkevxLjnkGc4427Vik86V2WHxZQay6LyqTcWl6L14kByqttkf45HBZQlFsE0cy_10UxlsfAz9G_Q15psfygYiYcRoOmPcLycsLLcJkrOiO88S_yCKVzBn-Jd-RjLqDiuIJ-FgI')
                ->setDevicesToken($tokens_android)
                ->send()
                ->getFeedback();


            return $this->apiResponse('done  success','',200);
        }



    }

    //eslam
    public function getuser(Request $request){

        $user_id=$request->user()->id;

        $send_shares = SendShare::where('receive_by',$user_id)->where('accept',0)->first();

        if(($send_shares) == null)  {

            return response()->json([
                'status' => true,
                'data' =>""
            ]);
        }else{

            $data = \DB::table('send_shares')->where('send_shares.send_by',$send_shares->send_by)->where('accept',0)
                ->select('users.firstName','users.phone','send_shares.money','send_shares.accept')
                ->join('users', 'send_shares.send_by', '=', 'users.id')
                ->first();
            $a=array();
            array_push($a,$data);
            // dd($data);
            return response()->json([
                'status' => true,
                'data' =>$a
            ]);

        }

    }

    public function confirm(Request $request){


        $user_id=$request->user()->id;

        $send_shares = SendShare::where('send_by',$user_id)->where('accept','!=',3)->get();

        $a=array();

        foreach($send_shares as $send_share){

            array_push($a,$send_share->accept);

        }

        $check_val=in_array('2',$a);
        $check_vl=in_array('0',$a);

        $phone_refuse = SendShare::where('send_by',$user_id)->where('accept',2)
            ->select('users.firstName','users.phone','send_shares.id as id_share')
            ->join('users', 'send_shares.receive_by', '=', 'users.id')->get();
        if($phone_refuse){
            foreach ($phone_refuse as $key=> $arr) {
                $id_share = $arr->id_share;
                $send_sh = SendShare::find($id_share);
                $send_sh->delete();
                $send_sh->save();

            }
            return $this->apiResponse('his share failed this phone refuse ','error',200);

        }

        $phone = SendShare::where('send_by',$user_id)->where('accept',0)
            ->select('users.firstName','users.phone','send_shares.id as id_share')
            ->join('users', 'send_shares.receive_by', '=', 'users.id')->get();

        if($phone){
            foreach ($phone as $key=> $arr) {
                $id_share = $arr->id_share;
                $send_sh = SendShare::find($id_share);
                $send_sh->delete();
                $send_sh->save();

            }
            return $this->apiResponse('his share failed this phone refuse ','error',200);

        }


        if($check_val == true || $check_vl == true){
//             $phone_refuse = SendShare::where('send_by',$user_id)->where('accept',2)
//                 ->select('users.firstName','users.phone','send_shares.id as id_share')
//                 ->join('users', 'send_shares.receive_by', '=', 'users.id')->get();

//             if($phone_refuse != null ){

//                 $user_before= \App\User::find($user_id);
//                 $user_before->check_before=0;
//                 $user_before->save();

//                 foreach ($phone_refuse as $key=> $arr) {
//                     $id_share = $arr->id_share;
//                     $send_sh = SendShare::find($id_share);
//                     $send_sh->delete();
//                     $send_sh->save();

//                 }
//                 return $this->apiResponse('his share failed this phone refuse ','error',200);

//             }
//             $phone = SendShare::where('send_by',$user_id)->where('accept',0)
//                 ->select('users.firstName','users.phone','send_shares.id as id_share')
//                 ->join('users', 'send_shares.receive_by', '=', 'users.id')->first();
//             if($phone != null ){

// //                foreach ($phone as $key=> $arr) {
// //                    $id_share = $arr->id_share;
// //                    $send_sh = SendShare::find($id_share);
// //                    $send_sh->accept = 3;
// //                    $send_sh->save();
// ////                dd($id_share);
// //                }
//                 return $this->apiResponse('his share failed this phone not replay Yet'.$phone->phone,'error',200);
//             }


        }else{

            $send_shar = SendShare::where('send_by',$user_id)->where('accept','!=',3)
                ->select('users.firstName','users.id','users.phone','send_shares.money','send_shares.id as id_share','send_shares.restaurant_id','send_shares.accept')
                ->join('users', 'send_shares.receive_by', '=', 'users.id')->get();

            //   dd($send_shar);
            // dd($user_id);

            $count_use=count($send_shar);

            foreach ($send_shar as $key=> $arr){
                // $phone=$arr;
                // $users = User::where('phone',$phone)->first();
                $money = $arr->money / ($count_use);

                $walletBeforeUser = Wallet::where('user_id',$arr->id)->first();
                $walletBeforeUser_send = Wallet::where('user_id',$user_id)->first();
                $da_wallet_send = Wallet::where('user_id','=',$user_id)->first();
                $amount_send= number_format($walletBeforeUser_send->account) - number_format($money);
                $da_wallet_send->account=$amount_send;
                $da_wallet_send->save();

                $da_wallet = Wallet::where('user_id','=',$arr->id)->first();
                $amount= number_format($walletBeforeUser->account) - number_format($money);
                $da_wallet->account=$amount;
                $da_wallet->save();

                if ($da_wallet)
                {
                    if (calWallet(sendNotifyShare($arr->phone)->id,$money) == true)
                    {
                        statementTransaction($money,sendNotifyShare($arr->phone)->id,$arr->restaurant_id,'user','vendor','paid',null,'wallet');
                    }
                    else
                    {
                        return $this->apiResponse('','his share failed',420);
                    }
                }
                $user_before= \App\User::find($user_id);
                $user_before->check_before=0;
                $user_before->save();

                $id_share =$arr->id_share;
                $send_sh = SendShare::find($id_share);
                $send_sh->delete();

                // $send_sh->accept =3;
                // $send_sh->save();


            }




        }







        return $this->apiResponse('done success','',200);

    }



    public function notification_to_android(   $tokens , $msg )
    {

        $push = new PushNotification('fcm');

        //   dd($msg);
        // $push->setMessage([  'msg'=>$msg  ])
        $push->setMessage([
            'data' => [
                'title'=>'Share payment Raaleat',
                'message'=>$msg,
            ]
        ])
            ->setApiKey('AAAAJxLQ_Ok:APA91bEkevxLjnkGc4427Vik86V2WHxZQay6LyqTcWl6L14kByqttkf45HBZQlFsE0cy_10UxlsfAz9G_Q15psfygYiYcRoOmPcLycsLLcJkrOiO88S_yCKVzBn-Jd-RjLqDiuIJ-FgI')
            ->setDevicesToken($tokens)
            ->send()
            ->getFeedback();


        return true;

    }


    /*
   **  we add the certificate in the folder
   ** vendor\edujugon\push-notification\src\Config\iosCertificates\Certificates1.pem
   **
   */
    public function notification_to_ios($tokens, $msg )
    {
        // apn : not working  for ios
        // fcm : working for  both ios and android
        $push = new PushNotification('fcm');

        $push->setMessage([
            'data' => [
                'title'=>'Share payment Raaleat',
                'message'=>$msg,
            ]
        ])
            ->setApiKey('AAAAJxLQ_Ok:APA91bEkevxLjnkGc4427Vik86V2WHxZQay6LyqTcWl6L14kByqttkf45HBZQlFsE0cy_10UxlsfAz9G_Q15psfygYiYcRoOmPcLycsLLcJkrOiO88S_yCKVzBn-Jd-RjLqDiuIJ-FgI')
            ->setDevicesToken($tokens)
            ->send()
            ->getFeedback();

        return true;
    }


    #sendNotifyShare
    public function sendNotifyShare(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'phone' =>'required',
                'count'=>'required|min:1|max:5'
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $users = User::find($request->user()->id);

        if (empty($users))
        {
            return $this->apiResponse('','this user not found',404);
        }
        else
        {
            \DB::table('send_shares')->where('send_by','=',$users->id)->delete();
            saveSendShare($users->id,sendNotifyShare($request->phone1)->id);
            if ($request->phone2 != null)
            {
                saveSendShare($users->id,sendNotifyShare($request->phone2)->id);
            }
            if ($request->phone3 != null)
            {
                saveSendShare($users->id,sendNotifyShare($request->phone3)->id);
            }
            if ($request->phone4 != null)
            {
                saveSendShare($users->id,sendNotifyShare($request->phone4)->id);
            }
            if ($request->phone5 != null)
            {
                saveSendShare($users->id,sendNotifyShare($request->phone5)->id);
            }
            return $this->apiResponse('done send notify','',200);
        }

    }

    #appletOrRefusal
    public function appletOrRefusal(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'answer' =>'required|in:yes,no',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $users = User::find($request->user()->id);

        if (empty($users))
        {
            return $this->apiResponse('','this user not found',404);
        }
        else
        {
            if ($request->answer == 'yes')
            {
                $data = \DB::table('send_shares')
                    ->where('receive_by','=',$users->id)
                    ->update(['accept'=>1]);
                if ($data)
                {
                    return $this->apiResponse('this user accept share','',200);
                }
                else
                {
                    return $this->apiResponse('','please try again',420);
                }
            }
            else
            {
                $data = \DB::table('send_shares')
                    ->where('receive_by','=',$users->id)
                    ->delete();
                if ($data)
                {
                    return $this->apiResponse('this user Refusal share','',200);
                }
                else
                {
                    return $this->apiResponse('','please try again',420);
                }
            }

        }

    }

    #getAnswer
    public function getAnswer(Request $request)
    {
        dd('jj');
        $users = User::find($request->user()->id);

        if (empty($users))
        {
            return $this->apiResponse('','this user not found',404);
        }
        else
        {
            $sendShares = SendShare::where('send_by',$users->id)->get();
            if ($sendShares->isEmpty())
            {
                return $this->apiResponse('','not found data',404);
            }
            else
            {
                for ($i=0; $i<count($sendShares); $i++)
                {
                    $details[] = [
                        'name'=>User::find($sendShares[$i]->id)->firstName.' '.User::find($sendShares[$i]->id)->lastName,
                        'phone'=>User::find($sendShares[$i]->id)->phone,
                        'answer'=>$sendShares[$i]->accept == 0 ? 'No':'Yes',
                    ];
                }
                return $this->apiResponse($details,'',200);
            }
        }
    }

    #confirmShare
    public function confirmShare(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'phone1' =>'required',
//                'count' =>'required|min:1|max:5',
                'amount'=>'required'
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $users = User::find($request->user()->id);

        if (empty($users))
        {
            return $this->apiResponse('','this user not found',404);
        }
        else
        {
            $money = $request->amount / ($request->count + 1);
            if ($money < 0 )
            {
                return $this->apiResponse('','please enter amount true',420);
            }
            else
            {
                if (checkWallet($users->id,$money) == true)
                {
                    if ($request->phone1 != null)
                    {
                        if (checkWallet(sendNotifyShare($request->phone1)->id,$money) == true)
                        {
                            if ($request->phone2 != null)
                            {
                                if (checkWallet(sendNotifyShare($request->phone2)->id,$money) == true)
                                {
                                    if ($request->phone3 != null)
                                    {
                                        if (checkWallet(sendNotifyShare($request->phone3)->id,$money) == true)
                                        {
                                            if ($request->phone4 != null)
                                            {
                                                if (checkWallet(sendNotifyShare($request->phone4)->id,$money) == true)
                                                {
                                                    if ($request->phone5 != null)
                                                    {
                                                        if (checkWallet(sendNotifyShare($request->phone5)->id,$money) == true)
                                                        {
                                                            return $this->apiResponse('The amount is enough','',200);
                                                        }
                                                        else
                                                        {
                                                            return $this->apiResponse('','The amount is not enough this phone '.$request->phone5,404);
                                                        }
                                                    }
                                                    return $this->apiResponse('The amount is enough','',200);
                                                }
                                                else
                                                {
                                                    return $this->apiResponse('','The amount is not enough this phone '.$request->phone4,404);
                                                }
                                            }
                                            return $this->apiResponse('The amount is enough','',200);
                                        }
                                        else
                                        {
                                            return $this->apiResponse('','The amount is not enough this phone '.$request->phone3,404);
                                        }
                                    }
                                    return $this->apiResponse('The amount is enough','',200);
                                }
                                else
                                {
                                    return $this->apiResponse('','The amount is not enough this phone '.$request->phone2,404);
                                }
                            }
                            return $this->apiResponse('The amount is enough','',200);
                        }
                        else
                        {
                            return $this->apiResponse('','The amount is not enough this phone '.$request->phone1,404);
                        }
                    }
                    return $this->apiResponse('The amount is enough','',200);
                }
                else
                {
                    return $this->apiResponse('','The amount is not enough this send share '.$users->firstName,404);
                }

            }
        }
    }

    #confirmFinal
    public function confirmFinal(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'phone1' =>'required',
                'count' =>'required|min:1|max:5',
                'restaurant_id' =>'required|exists:restaurants,id',
                'amount'=>'required'
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $users = User::find($request->user()->id);

        if (empty($users))
        {
            return $this->apiResponse('','this user not found',404);
        }
        else
        {
            $money = $request->amount / ($request->count + 1);
            if ($money < 0 )
            {
                return $this->apiResponse('','please enter amount true',420);
            }
            else
            {
                $walletBeforeUser = Wallet::where('user_id',$users->id)->first();
                $data = \DB::table('wallets')->where('user_id','=',$users->id)
                    ->update(['account'=>$walletBeforeUser->account - $money]);

                if ($data)
                {
                    statementTransaction($money,$users->id,$request->restaurant_id,'user','vendor','paid',null,'wallet');
                    if (calWallet(sendNotifyShare($request->phone1)->id,$money) == true)
                    {
                        statementTransaction($money,sendNotifyShare($request->phone1)->id,$request->restaurant_id,'user','vendor','paid',null,'wallet');
                        if ($request->phone2 != null)
                        {
                            if (calWallet(sendNotifyShare($request->phone2)->id,$money) == true)
                            {
                                statementTransaction($money,sendNotifyShare($request->phone2)->id,$request->restaurant_id,'user','vendor','paid',null,'wallet');
                                if ($request->phone3 != null)
                                {
                                    if (calWallet(sendNotifyShare($request->phone3)->id,$money) == true)
                                    {
                                        statementTransaction($money,sendNotifyShare($request->phone3)->id,$request->restaurant_id,'user','vendor','paid',null,'wallet');
                                        if ($request->phone4 != null)
                                        {
                                            if (calWallet(sendNotifyShare($request->phone4)->id,$money) == true)
                                            {
                                                statementTransaction($money,sendNotifyShare($request->phone4)->id,$request->restaurant_id,'user','vendor','paid',null,'wallet');
                                                if ($request->phone5 != null)
                                                {
                                                    if (calWallet(sendNotifyShare($request->phone5)->id,$money) == true)
                                                    {
                                                        statementTransaction($money,sendNotifyShare($request->phone5)->id,$request->restaurant_id,'user','vendor','paid',null,'wallet');
                                                        \DB::table('send_shares')
                                                            ->where('send_by','=',$users->id)
                                                            ->delete();
                                                        return $this->apiResponse('done share success','',200);

                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        return $this->apiResponse('done share success','',200);
                    }
                    else
                    {
                        return $this->apiResponse('','his share failed',420);
                    }
                }

            }
        }
    }








}
