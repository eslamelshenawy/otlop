<?php

namespace App\Http\Controllers\Frontend;

use App\Admin;
use App\City;
use App\File;
use App\Mail\AdminResetPassword;
use App\Mail\UserResetPassword;
use App\Notifications\RegisterDelivery;
use App\Notifications\RegisterUsers;
use App\User;
use App\Wallet;
use App\WalletDelivery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Socialite;
use Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logoutUsers');
    }

    public function showFormLogin()
    {
        try{

            return view('frontend.auth.users.login');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function showFormRegister()
    {
        try{
            $city = City::where('status',1)->orderBy('created_at','DESC')->get();

            return view('frontend.auth.users.register',compact('city'));
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function loginUsers(Request $request)
    {
        try{


            $messages = [
                'email.required' => 'We need to know your e-mail address!',
                'password.required' => 'We need to know your password',
            ];
            $validator = \Validator::make($request->all(), [
                'password' => 'min:3|required',
                'email'=>'required|email|string|exists:users,email',
            ], $messages);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)->withInput();
            }
            $email = \request('email');
            $password = \request('password');
            $remember =  \request()->has('remember') == 1 ? true : false ;
            if(\Auth::guard('web')->attempt(array('email'=> $email, 'password' => $password),$remember))
            {
                $user = User::where('email',$email)->first();

                if (empty($user))
                {
                    return redirect()->back()->with('warning',trans('admin.Login failed please try again'));

//                    session()->flash('warning',trans('admin.Login failed please try again'));
//                    return redirect()->back()->withInput();
                }
                else
                {
                    if (\Session::has('CurrentUrl'))
                    {
                        return redirect((\Session::get('CurrentUrl')));
                    }
                    else
                    {
                        return redirect()->route('web.home');
                    }
                }
            }
            else
            {
                return redirect()->back()->with('warning',trans('admin.Login failed please try again'));
//                return redirect()->with('warning',trans('web.try again'))->withInput();
            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function registerUser(Request $request)
    {
        try{

            $messages = [
                'firstName.required' => 'We need to know your first name',
                'lastName.required' => 'We need to know your last name',
                'email.required' => 'We need to know your e-mail address!',
                'image.required' => 'We need to know your image',
                'password.required' => 'We need to know your password',
                'phone.required' => 'We need to know your phone',
                'city_id.required' => 'We need to know your city name!',
            ];
            $validator = \Validator::make($request->all(), [
                'firstName'=>'required|min:3',
                'lastName'=>'required|min:3',
                'password' => 'min:3|required_with:password_confirmation|same:password_confirmation',
                'email'=>'required|email|string|unique:users,email|unique:admins,email',
                'image' =>'required|'.validateImage(),
                'phone'=>'required|numeric|unique:users,phone',
                'city_id' => 'required|exists:cities,id',
            ], $messages);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)->withInput();
            }
            $remember =  \request()->has('remember') == 1 ? true : false ;
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'users/','');
            }
            else
            {
                $filename = '';
            }
            $user = new User();
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->email = $request->email;
            $user->city_id = $request->city_id;
            $user->phone = $request->phone;
            $user->password = \Hash::make($request->password);
            $user->image = $filename;
            $user->user_token = Str::random(60);
            $user->save();
            if ($user->save())
            {
                Wallet::create([
                    'user_id' => $user->id,
                ]);
                $date = new \DateTime();

                $admin = Admin::where('userType','super_admin')->first();
                $admin->notify(new RegisterUsers($user->id,trans('admin.This user register now '.$user->firstName. ' '.$user->lastName),$date));
                if(\Auth::guard('web')->attempt(array('email'=> $request->email, 'password' => $request->password),$remember))
                {
                 return redirect()->route('web.home');
                }
                else
                {
                    if (\Session::has('CurrentUrl'))
                    {
                        return redirect((\Session::get('CurrentUrl')));
                    }
                    else
                    {
                        return redirect()->route('web.home');
                    }
                }
            }
            else
            {
                return redirect()->back()->with('warning',trans('web.Please tray again'));
            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }


    }

    public function resetPassword()
    {
        return view('frontend.auth.password.email');
    }

    public function postResetPassword(Request $request)
    {
        $messages = [
            'email.required' => 'We need to know your e-mail address!',
        ];
        $validator = \Validator::make($request->all(), [
            'email'=>'required|email|string|exists:users,email',
        ], $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = User::where('email',$request->get('email'))->first();
        if (!empty($user))
        {
            $token = app('auth.password.broker')->createToken($user);
            $data = \DB::table('password_resets')->insert(
                [
                    'email'=>$user->email,
                    'token'=>$token,
                    'created_at'=>Carbon::now()
                ]);
            // return new AdminResetPassword(['data'=>$admin,'token'=>$token]);
            \Mail::to($user->email)->send(new UserResetPassword(['data'=>$user,'token'=>$token]));
            session()->flash('success',trans('admin.Rest link is sent'));
        }
        return redirect()->back();
    }

    public function reset($token)
    {
        $checkToken = \DB::table('password_resets')
            ->where('token','=',$token)
            ->where('created_at','>',Carbon::now()->subHour(2))
            ->first();

        if (!empty($checkToken))
        {
            return view('frontend.auth.password.reset',['data'=>$checkToken]);
        }
        else
        {
            return redirect()->route('web.get.reset.password');
        }
    }

    public function postReset(Request $request ,$token)
    {

        $messages = [
            'email.required' => 'We need to know your e-mail address!',
            'password.required' => 'We need to know your password !',
            'password_confirmation.required' => 'We need to know your password confirmation !',
        ];
        $validator = \Validator::make($request->all(), [
            'email'=>'required|email|string|exists:users,email',
            'password'=>'required|confirmed',
            'password_confirmation'=>'required'
        ], $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $checkToken = \DB::table('password_resets')
            ->where('token','=',$token)
            ->where('created_at','>',Carbon::now()->subHour(2))
            ->first();
        if (!empty($checkToken))
        {
            User::where('email',$checkToken->email)
                ->update([
                    'email'=>$checkToken->email,
                    'password'=>\Hash::make(\request('password'))
                ]);
            \DB::table('password_resets')
                ->where('email','=',\request('email'))->delete();

            auth()->guard('web')->attempt(['email'=>$checkToken->email,'password'=>\request('password')] , true);
            return redirect()->route('web.home');
        }
        else
        {
            return redirect()->route('web.get.reset.password');
        }
    }

    public function showFormDelivery()
    {
        try{
            return view('frontend.auth.system.delivery');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function registerDelivery(Request $request)
    {
        try{

            $messages = [
                'firstName.required' => 'We need to know your first name',
                'lastName.required' => 'We need to know your last name',
                'email.required' => 'We need to know your e-mail address!',
                'phone.required' => 'We need to know your phone!',
                'address.required' => 'We need to know your address!',
                'image.required' => 'We need to know your image',
                'password.required' => 'We need to know your password',
            ];
            $validator = \Validator::make($request->all(), [
                'firstName'=>'required|min:3',
                'lastName'=>'required|min:3',
                'phone'=>'required|min:3',
                'address'=>'required|min:3',
                'password' => 'min:3|required_with:password_confirmation|same:password_confirmation',
                'email'=>'required|email|string|unique:users|unique:admins',
                'image' =>'required|'.validateImage(),
            ], $messages);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)->withInput();
            }

            $user = new Admin();
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
            //$user->image = $filename;
            $user->userType = 'delivery';
            $user->status = 0;
            $user->user_token = Str::random(60);
            $user->phone = $request->phone;
            $user->created_by = 'site';
            $user->address = $request->address;
            $user->save();
            if ($user->save())
            {
                WalletDelivery::create([
                    'delivery_id' => $user->id,
                ]);
                if ($request->file)
                {
                    $filename = uploadImages($request->image,'file/','');
                }
                $files = new File();
                $files->admin_id = $user->id;
                $files->type = 'delivery';
                $files->name = $filename;
                $files->save();

                $date = new \DateTime();

                $admin = Admin::where('userType','super_admin')->first();
                $admin->notify(new RegisterDelivery($user->id,trans('admin.This delivery register now '.$user->firstName. ' '.$user->lastName),$date));
                return redirect()->route('web.home')
                    ->with('success',trans('web.The data has been sent successfully, waiting for an answer to the email or a phone call'));
            }
            else
            {
                return redirect()->back()->with('warning',trans('web.Please tray again'));
            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }


    }


    public function registerRestaurant(Request $request)
    {
        return redirect()->back();
       /* try{

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [
                    $locale .'.name' =>'required|string|min:2',
                    $locale .'.address' =>'required|string'
                    ,'firstName'=>'required|min:3',
                    'lastName'=>'required|min:3',
                    'password' => 'min:3|required_with:password_confirmation|same:password_confirmation',
                    'email'=>'required|email|string|unique:users|unique:admins',
                    'image' =>validateImage(),
                    'file' =>'required|'.validateImage('pdf')
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameRes'),
                    $locale .'.address.required' =>trans('admin.'.$locale.'.addressRes'),
                    'firstName.required' => 'We need to know your first name',
                    'lastName.required' => 'We need to know your last name',
                    'email.required' => 'We need to know your e-mail address!',
                    'file.required' => 'Please upload files restaurant',
                ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if ($request->image)
            {
                $filename = uploadImages($request->image,'admin/','');
            }
            else
            {
                $filename = '';
            }

            if ($request->file)
            {
                $files = uploadImages($request->image,'file/','');
            }

            $user = new Admin();
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
            $user->image = $filename;
            $user->userType = 'vendor';
            $user->status = 0;
            $user->user_token = Str::random(60);
            $user->created_by = 'site';
            $user->save();
            if ($user->save())
            {
                $requestData = $request->except('_token','file','image','firstName',
                    'lastName','email','password');

                $date = new \DateTime();

                $admin = Admin::where('userType','super_admin')->first();
                $admin->notify(new RegisterDelivery($user->id,trans('admin.This delivery register now '.$user->firstName. ' '.$user->lastName),$date));
                return redirect()->route('web.home')
                    ->with('success',trans('web.The data has been sent successfully, waiting for an answer to the email or a phone call'));
            }
            else
            {
                return redirect()->back()->with('warning',trans('web.Please tray again'));
            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }*/


    }

    public function logoutUsers(Request $request)
    {
        \Auth::guard('web')->logout();
        return redirect('/');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function handleProviderCallback($provider)
    {
    $user = Socialite::driver($provider)->user();

    $authUser = $this->findOrCreateUser($user, $provider);
    Auth::login($authUser, true);
    return redirect($this->redirectTo);
    }


    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
    }


}
