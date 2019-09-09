<?php

namespace App\Http\Controllers\Vendor;

use App\DataTables\AdminDataTable;
use App\Admin;
use App\DataTables\UserVendorDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserVendorController extends Controller
{

    public function __construct()
    {
        try{
            $this->middleware('admin');
            $this->middleware(['auth:admin','permission:read_user_vendors'])->only('index');
            $this->middleware(['auth:admin','permission:create_user_vendors'])->only('create','store');
            $this->middleware(['auth:admin','permission:update_user_vendors'])->only('edit','update');
            $this->middleware(['auth:admin','permission:delete_user_vendors'])->only('destroy','multiDelete');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function index(UserVendorDataTable $userVendorDataTable)
    {
        try{

            return $userVendorDataTable->render('backend.vendor.userVendor.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function create()
    {
        try{
            return view('backend.vendor.userVendor.create');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function store(Request $request)
    {
        try{

            $data = checkEmail('users',$request->email);

            if (!empty($data))
            {
                session()->flash('warning',trans('admin.This email is used'));
            }

                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins',
                    'password'=>'required|min:1',
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'permission'=>'required|min:1',
                    'address'=>'required|string',
                    'image' => 'required|'.validateImage(),
                ];
                $message = [
                    'firstName.required' => trans('admin.First name is required'),
                    'lastName.required' => trans('admin.Last name is required'),
                    'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                    'password.required' => trans('admin.Password is required must be 6 characters'),
                    'Phone.required' => trans('admin.Phone is required'),
                    'status.required' => trans('admin.Status is required'),
                    'address.required' => trans('admin.Address is required'),
                    'permission.required' => trans('admin.Permission is required and select min one'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];
            $validate = Validator::make($request->all(),$rules,$message);
            if ($validate->fails())
            {
                return redirect()->back()->withErrors($validate)->withInput();
            }
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'admin/','');
            }
            $admin = new Admin();
            $admin->firstName = $request->firstName;
            $admin->lastName = $request->lastName;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->phone = $request->phone;
            $admin->address = $request->address;
            $admin->userType = $request->userType;
            $admin->status = $request->status;
            $admin->user_token = Str::random(60);
            $admin->image = $filename;
            $admin->userType = 'user_vendor';
            $admin->parent_id = getDataVendor(\Auth::guard('admin')->user()->id);
            $admin->created_by = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $admin->save();
            if ($admin->save())
            {
                    $admin->attachRole('vendor');
                    $admin->syncPermissions($request->get('permission'));
                    session()->flash('success',trans('admin.Data has been added successfully'));
                    return redirect()->route('vendor.userVendor.index');
            }




        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function show($id)
    {
        try{


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function edit($id)
    {
        try{
            $admin = Admin::find($id);
            checkData($admin);
            return view('backend.vendor.userVendor.edit',compact('admin'));


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        try{

            $data = checkEmail('users',$request->email);

            if (!empty($data))
            {
                session()->flash('warning',trans('admin.This email is used'));
            }

            $admin = Admin::find($request->id);
            checkData($admin);

                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins,email,'.$request->id,
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'permission'=>'required|min:1',
                    'address'=>'required|string',
                    'image' => validateImage(),
                ];
                $message = [
                    'firstName.required' => trans('admin.First name is required'),
                    'lastName.required' => trans('admin.Last name is required'),
                    'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                    'password.required' => trans('admin.Password is required must be 6 characters'),
                    'Phone.required' => trans('admin.Phone is required'),
                    'status.required' => trans('admin.Status is required'),
                    'address.required' => trans('admin.Address is required'),
                    'permission.required' => trans('admin.Permission is required and select min one'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];

            $validate = Validator::make($request->all(),$rules,$message);
            $filename = '';
            if ($validate->fails())
            {
                return redirect()->back()->withErrors($validate)->withInput();
            }
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'admin/',$admin->image);
            }
            else
            {
                $filename = $admin->image;
            }
            $admin->firstName = $request->firstName;
            $admin->lastName = $request->lastName;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->address = $request->address;
            $admin->status = $request->status;
            $admin->user_token = Str::random(60);
            $admin->image = $filename;
            $admin->updated_by = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $admin->save();
            if ($admin->save())
            {
                $admin->syncPermissions($request->get('permission'));
                session()->flash('success',trans('admin.Data has been added successfully'));
                return redirect()->route('vendor.userVendor.index');
            }

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy($id)
    {
        try{
            $admin = Admin::find($id);
            checkData($admin);
            removeImage('admin/'.$admin->image);
            Admin::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.userVendor.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }

    public function multiDelete()
    {
        try{

            if (is_array(\request('item')))
            {
                Admin::destroy(\request('item'));
            }
            else
            {
                Admin::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.userVendor.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
