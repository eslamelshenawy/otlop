<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\DataTables\CityDataTable;
use App\DataTables\PackageDataTable;
use App\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_package'])->only('index');
        $this->middleware(['auth:admin','permission:create_package'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_package'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_package'])->only('destroy','multiDelete');
    }

    public function index(PackageDataTable $packageDataTable)
    {
        try{
            return $packageDataTable->render('backend.package.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            return view('backend.package.create');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try{

            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [
                    $locale .'.name' =>['required',Rule::unique('package_translations','name')]
                    ,'status'=>'required|in:1,0'
                    ,'price'=>'required|regex:/^\d*(\.\d{1,2})?$/|min:1'
                    ,'point'=>'required|integer|min:0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.namePackage'),
                    'status.required' =>trans('admin.Status is required'),
                    'price.required' =>trans('admin.Price is required'),
                    'point.required' =>trans('admin.Point is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $request['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            Package::create($request->all());

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.package.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $package = Package::find($id);
            if (empty($package))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.package.edit',compact('package'));
            }

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function update(Request $request)
    {
        try{
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [
                    $locale . '.name' =>['required', Rule::unique('package_translations','name')->ignore($request->get('id'),'package_id')],
                    'status'=>'required|in:1,0'
                    ,'price'=>'required|regex:/^\d*(\.\d{1,2})?$/|min:0'
                    ,'point'=>'required|integer|min:0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.namePackage'),
                    'status.required' =>trans('admin.Status is required'),
                    'price.required' =>trans('admin.Price is required'),
                    'point.required' =>trans('admin.Point is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $package = Package::find($request->get('id'));
            if (empty($package))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $request['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $package->update($request->all());
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.package.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $package = Package::find($id);
            if(empty($package))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Package::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.package.index');

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
                Package::destroy(\request('item'));
            }
            else
            {
                Package::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.package.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
