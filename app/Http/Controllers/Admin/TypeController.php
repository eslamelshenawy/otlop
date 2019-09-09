<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TypeDataTable;
use App\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_restaurants'])->only('index');
        $this->middleware(['auth:admin','permission:create_restaurants'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_restaurants'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_restaurants'])->only('destroy','multiDelete');
    }

    public function index(TypeDataTable $typeDataTable)
    {
        try{
            return $typeDataTable->render('backend.restaurants.type.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            return view('backend.restaurants.type.create');

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
                    $locale .'.name' =>['required',Rule::unique('type_translations','name')]
                    ,'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameTypeRes'),
                    'status.required' =>trans('admin.Status is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $request['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            Type::create($request->all());

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.types.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $type = Type::find($id);
            if (empty($type))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.restaurants.type.edit',compact('type'));
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
                    $locale . '.name' =>['required', Rule::unique('type_translations','name')->ignore($request->get('id'),'type_id')],
                    'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameTypeRes'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $type = Type::find($request->get('id'));
            if (empty($type))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $request['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $type->update($request->all());
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.types.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $type = Type::find($id);
            if(empty($type))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Type::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.types.index');

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
                Type::destroy(\request('item'));
            }
            else
            {
                Type::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.types.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
