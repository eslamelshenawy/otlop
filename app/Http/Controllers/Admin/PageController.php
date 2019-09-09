<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\DataTables\CityDataTable;
use App\DataTables\PageDataTable;
use App\DataTables\PageyDataTable;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','role:super_admin']);
    }

    public function index(PageDataTable $pageDataTable)
    {
        try{
            return $pageDataTable->render('backend.page.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            return view('backend.page.create');

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
                    $locale .'.name' =>['required',Rule::unique('page_translations','name')],
                    $locale .'.title' =>['required','min:3']
                    ,'status'=>'required|in:1,00',
                    'image' => validateImage(),
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.namePage'),
                    $locale .'.title.required' =>trans('admin.'.$locale.'.nameTitleReq'),
                    'status.required' =>trans('admin.Status is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'page/','');
            }
            Page::create($request->all());

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.page.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $page = Page::find($id);
            if (empty($page))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.page.edit',compact('page'));
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
                    $locale . '.name' =>['required', Rule::unique('page_translations','name')
                        ->ignore($request->get('id'),'page_id')],
                    $locale .'.title' =>['required','min:3'],
                    'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.namePage'),
                    $locale .'.title.required' =>trans('admin.'.$locale.'.nameTitleReq'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $page = Page::find($request->get('id'));
            if (empty($page))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $requestData = $request->except('image','_method','_token');
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'page/',$page->image);
            }
            else
            {
                $filename = $page->image;
            }

            $requestData['image'] = $filename;
            $page->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.page.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $page = Page::find($id);
            if(empty($page))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Page::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.page.index');

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
                Page::destroy(\request('item'));
            }
            else
            {
                Page::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.page.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
