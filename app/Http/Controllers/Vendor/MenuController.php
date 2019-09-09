<?php

namespace App\Http\Controllers\Vendor;

use App\DataTables\MenuDataTable;
use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_menu'])->only('index');
        $this->middleware(['auth:admin','permission:create_menu'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_menu'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_menu'])->only('destroy','multiDelete');
    }

    public function index(MenuDataTable $menuDataTable)
    {
        try{
            return $menuDataTable->render('backend.vendor.menu.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            return view('backend.vendor.menu.create');

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
                    $locale .'.name' =>['required','min:3']
                    ,'status'=>'required|in:1,0',
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameMenu'),
                    'status.required' =>trans('admin.Status is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $requestData = $request->except('_token');
            $requestData['restaurant_id'] = getDataRestaurant(\Auth::guard('admin')->user()->id)->id;
            $requestData['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            Menu::create($requestData);

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('vendor.menu.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $menu = Menu::find($id);
            if (empty($menu))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.vendor.menu.edit',compact('menu'));
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
                    $locale . '.name' =>['required','min:3'],
                    'status'=>'required|in:1,0',
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameMenu'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }

            $menu = Menu::find($request->get('id'));
            if (empty($menu))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $requestData = $request->except('image');

            $requestData['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;

            $menu->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('vendor.menu.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $menu = Menu::find($id);
            if(empty($menu))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            removeImage('menu/'.$menu->image);
            Menu::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.menu.index');

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
                Menu::destroy(\request('item'));
            }
            else
            {
                Menu::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.menu.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
