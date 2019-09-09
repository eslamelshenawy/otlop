<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PrivacyDataTable;
use App\Privacy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class PrivacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_privacy'])->only('index');
        $this->middleware(['auth:admin','permission:create_privacy'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_privacy'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_privacy'])->only('destroy','multiDelete');
    }

    public function index(PrivacyDataTable $privacyDataTable)
    {
        try{
            return $privacyDataTable->render('backend.privacy.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            return view('backend.privacy.create');

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
                    $locale .'.title' =>['required',Rule::unique('privacy_translations','title')],
                    $locale .'.description' =>['required','min:10']
                    ,'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.title.required' =>trans('admin.'.$locale.'.titlePrivacyReq'),
                    $locale .'.description.required' =>trans('admin.'.$locale.'.privacyDescriptionReq'),
                    'status.required' =>trans('admin.Status is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            Privacy::create($request->all());

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.privacy.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $privacy = Privacy::find($id);
            if (empty($privacy))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.privacy.edit',compact('privacy'));
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
                    $locale . '.title' =>['required', Rule::unique('privacy_translations','title')->ignore($request->get('id'),'privacy_id')],
                    'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.title.required' =>trans('admin.'.$locale.'.titlePrivacyReq'),
                    'status.required' =>trans('admin.Status is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $privacy = Privacy::find($request->get('id'));
            if (empty($privacy))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $privacy->update($request->all());
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.privacy.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $privacy = Privacy::find($id);
            if(empty($privacy))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Privacy::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.privacy.index');

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
                Privacy::destroy(\request('item'));
            }
            else
            {
                Privacy::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.privacy.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
