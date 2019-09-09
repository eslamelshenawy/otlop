<?php

namespace App\Http\Controllers\Vendor;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function __construct()
    {
        try{
            $this->middleware('admin');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
    #profile
    public function profile()
    {

        try{
            $data = \Auth::guard('admin')->user();

            return view('backend.vendor.profile.profile',compact('data'));
        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function updateProfile(Request $request)
    {
        try{
            $rules = [
                'firstName'=>'required|string|min:3',
                'lastName'=>'required|string|min:3',
                'email'=>'required|string|email|unique:admins,email,'.$request->id,
                'phone'=>'required|numeric',
                'address'=>'required|string',
                'image' => validateImage(),
            ];
            $message = [
                'firstName.required' => trans('admin.First name is required'),
                'lastName.required' => trans('admin.Last name is required'),
                'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                'Phone.required' => trans('admin.Phone is required'),
                'address.required' => trans('admin.Address is required'),
                'image' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
            ];
            $validate = Validator::make($request->all(),$rules,$message);
            if ($validate->fails())
            {
                return redirect()->back()->withErrors($validate)->withInput();
            }
            $admin = Admin::find($request->get('id'));

            if (empty($admin))
            {
                return redirect()->back()->with('warning',trans('admin.Not found this id vendor'));
            }
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'admin/',$admin->image);
            }
            else
            {
                $filename = $admin->image;
            }
            $admin->firstName = $request->get('firstName');
            $admin->lastName = $request->get('lastName');
            $admin->email = $request->get('email');
            $admin->address = $request->get('address');
            $admin->phone = $request->get('phone');
            $admin->image = $filename;
            $admin->save();
            if ($admin->save())
            {
                return redirect()->route('vendor.profile')->with('success',trans('admin.Done update profile success'));
            }
            else
            {
                return redirect()->back()->with('warning',trans('admin.Please try again'));
            }

        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function changePassword(Request $request)
    {
        try{
            $rules = [
                'old_password'=>'required|string|min:3',
                'new_password' => 'min:3|required_with:confirm_password|same:confirm_password',
            ];
            $message = [
                'old_password.required' => trans('admin.Old password is required'),
                'new_password.required' => trans('admin.New password is required'),
            ];
            $validate = Validator::make($request->all(),$rules,$message);
            if ($validate->fails())
            {
                return redirect()->back()->withErrors($validate)->withInput();
            }

            $data = \DB::table('admins')->where('id','=',$request->get('id'))->get();
            if ($data->isEmpty())
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page'));
            }
            else
            {
                if (\Hash::check($request->get('old_password'),$data[0]->password))
                {
                    \DB::table('admins')->where('id','=',$request->get('id'))
                        ->update(['password'=>\Hash::make($request->get('new_password'))]);
                    return redirect()->back()->with('success',trans('admin.Password is changed success'));
                }
                else
                {
                    return redirect()->back()->with('warning',trans('admin.Please check old password is correct'));
                }
            }


        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
}
