<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AdminDataTable;
use App\Admin;
use App\LocationDelivery;
use App\WalletDelivery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function __construct()
    {
        try{
            $this->middleware('admin');
            $this->middleware(['auth:admin','permission:read_admins'])->only('index');
            $this->middleware(['auth:admin','permission:create_admins'])->only('create','store');
            $this->middleware(['auth:admin','permission:update_admins'])->only('edit','update');
            $this->middleware(['auth:admin','permission:delete_admins'])->only('destroy','multiDelete');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function index(AdminDataTable $adminDataTable)
    {
        try{
 
            return $adminDataTable->render('backend.admin.admin.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function create()
    {
        try{
            return view('backend.admin.admin.create');
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
            if ($request->userType == 'admin')
            {
                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins',
                    'password'=>'required|min:1',
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'userType'=>'required|in:admin,vendor,delivery',
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
                    'userType.required' => trans('admin.User type is required'),
                    'address.required' => trans('admin.Address is required'),
                    'permission.required' => trans('admin.Permission is required and select min one'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];
            }
            else
            {
                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins',
                    'password'=>'required|min:1',
                    'phone'=>'required|numeric|unique:admins',
                    'status'=>'required|in:1,0',
                    'userType'=>'required|in:admin,vendor,delivery',
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
                    'userType.required' => trans('admin.User type is required'),
                    'address.required' => trans('admin.Address is required'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];
            }
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
            if ($request->userType == 'admin')
            {
                $admin->parent_id = \Auth::guard('admin')->user()->id;
            }
            $admin->created_by = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $admin->save();
            if ($admin->save())
            {
                if ($admin->userType == 'admin')
                {
                    $admin->attachRole('admin');
                    $admin->syncPermissions($request->get('permission'));
                    session()->flash('success',trans('admin.Data has been added successfully'));
                    return redirect()->route('admin.admin.index');
                }
                elseif($admin->userType == 'vendor')
                {
                    $admin->attachRole('vendor');
                }
                elseif($admin->userType == 'delivery')
                {
                    WalletDelivery::create([
                        'delivery_id' => $admin->id,
                    ]);
                    $locationDelivery = new LocationDelivery();
                    $locationDelivery->delivery_id = $admin->id;
                    $locationDelivery->save();
                }
                session()->flash('success',trans('admin.Data has been added successfully'));
                return redirect()->route('admin.admin.index');
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
            return view('backend.admin.admin.edit',compact('admin'));


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

            if ($admin->userType == 'admin')
            {
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
            }
            else
            {
                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins,email,'.$request->id,
                    'password'=>'required|min:1',
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'address'=>'required|string',
                    'image' => validateImage(),
                ];
                $message = [
                    'firstName.required' => trans('admin.First name is required'),
                    'lastName.required' => trans('admin.Last name is required'),
                    'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                    'Phone.required' => trans('admin.Phone is required'),
                    'status.required' => trans('admin.Status is required'),
                    'address.required' => trans('admin.Address is required'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];
            }
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
                if ($admin->userType == 'admin')
                {
                    $admin->syncPermissions($request->get('permission'));
                    session()->flash('success',trans('admin.Data has been added successfully'));
                    return redirect()->route('admin.admin.index');
                }
                session()->flash('success',trans('admin.Data has been added successfully'));
                return redirect()->route('admin.admin.index');
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
            removeImage('admin/'.$admin->image);
            if ($admin->userType == 'delivery')
            {
                WalletDelivery::where('delivery_id',$admin->id)->delete();
            }
            Admin::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.admin.index');

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
                for ($i = 0; $i<count(\request('item')); $i++)
                {
                    $data = Admin::find(\request('item')[$i]);
                    removeImage('admin/'.$data->image);
                    if ($data->userType == 'delivery')
                    {
                        WalletDelivery::where('delivery_id',$data->id)->delete();
                    }
                }
                Admin::destroy(\request('item'));
            }
            else
            {
                $admin =  Admin::find(\request('item'));
                removeImage('admin/'.$admin->image);
                if ($admin->userType == 'delivery')
                {
                    WalletDelivery::where('delivery_id',$admin->id)->delete();
                }
                $admin->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.admin.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }


}
