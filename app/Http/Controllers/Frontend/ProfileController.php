<?php

namespace App\Http\Controllers\Frontend;

use App\ChargingWallet;
use App\City;
use App\Order;
use App\User;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        try{
            $city = City::orderBy('created_at','DESC')->get();
            $data = User::find(\Auth::guard('web')->user()->id);
            $orders = Order::with('orderDetails')
            ->where('user_id',\Auth::guard('web')->user()->id)
                ->orderBy('created_at','DESC')
                ->get();
            $chargingWallet = ChargingWallet::where('user_id',\Auth::guard('web')->user()->id)->get();
            $wallet = Wallet::where('user_id',\Auth::guard('web')->user()->id)->first();
            if (empty($data))
            {
                \Auth::guard('web')->logout();
                return redirect()->route('web.home');
            }
            else
            {
                return view('frontend.profile.profile',compact('data','city','orders','chargingWallet','wallet'));

            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }

    public function updateProfile(Request $request)
    {
        try{

            $data = User::find(\Auth::guard('web')->user()->id);
            if (empty($data))
            {
                \Auth::guard('web')->logout();
                return redirect()->route('web.home');
            }
            else
            {
                $messages = [
                    'firstName.required' => 'We need to know your first name',
                    'lastName.required' => 'We need to know your last name',
                    'email.required' => 'We need to know your e-mail address!',
                    'phone.required' => 'We need to know your phone!',
                    'city_id.required' => 'We need to know your city name!',
                ];
                $validator = \Validator::make($request->all(), [
                    'firstName'=>'required|min:3',
                    'lastName'=>'required|min:3',
                    'phone'=>'required|numeric|unique:users,phone,'.$data->id,
                    'email'=>'required|email|string|unique:admins|unique:users,email,'.$data->id,
                    'image' =>validateImage(),
                    'city_id' => 'required|exists:cities,id',
                ], $messages);
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)->withInput();
                }
                if ($request->image)
                {
                    $filename = uploadImages($request->image,'users/',$data->image);
                }
                else
                {
                    $filename = $data->image;
                }
                $data->firstName = $request->firstName;
                $data->lastName = $request->lastName;
                $data->email = $request->email;
                $data->city_id = $request->city_id;
                $data->address = $request->address;
                $data->phone = $request->phone;
                $data->image = $filename;
                $data->save();
                if ($data->save())
                {
                    return redirect()->back()->with('success',trans('web.Data modified successfully'));
                }
                else
                {
                    return redirect()->back()->with('warning',trans('web.Please try again'));
                }

            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
