<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\DataTables\CityDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_cities'])->only('index');
        $this->middleware(['auth:admin','permission:create_cities'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_cities'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_cities'])->only('destroy','multiDelete');
    }

    public function index(CityDataTable $cityDataTable)
    {
        try{
            return $cityDataTable->render('backend.city.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            return view('backend.city.create');

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
                    $locale .'.name' =>['required',Rule::unique('city_translations','name')]
                    ,'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameCity'),
                    'status.required' =>trans('admin.Status is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $request['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            City::create($request->all());

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.city.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $city = City::find($id);
            if (empty($city))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.city.edit',compact('city'));
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
                    $locale . '.name' =>['required', Rule::unique('city_translations','name')->ignore($request->get('id'),'city_id')],
                    'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameCity'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $city = City::find($request->get('id'));
            if (empty($city))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $request['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $city->update($request->all());
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.city.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $city = City::find($id);
            if(empty($city))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            City::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.city.index');

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
                City::destroy(\request('item'));
            }
            else
            {
                City::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.city.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
