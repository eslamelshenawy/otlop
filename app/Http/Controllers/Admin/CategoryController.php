<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\DataTables\CategoryDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_category'])->only('index');
        $this->middleware(['auth:admin','permission:create_category'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_category'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_category'])->only('destroy','multiDelete');
    }

    public function index(CategoryDataTable $categoryDataTable)
    {
        try{
            return $categoryDataTable->render('backend.category.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            return view('backend.category.create');

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
                    $locale .'.name' =>['required',Rule::unique('category_translations','name')]
                    ,'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameCategory'),
                    'status.required' =>trans('admin.Status is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $request['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            Category::create($request->all());

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.category.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $category = Category::find($id);
            if (empty($category))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.category.edit',compact('category'));
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
                    $locale . '.name' =>['required', Rule::unique('category_translations','name')->ignore($request->get('id'),'category_id')],
                    'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameCategory'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $category = Category::find($request->get('id'));
            if (empty($category))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $request['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $category->update($request->all());
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.category.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $category = Category::find($id);
            if(empty($category))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Category::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.category.index');

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
                Category::destroy(\request('item'));
            }
            else
            {
                Category::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.category.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
