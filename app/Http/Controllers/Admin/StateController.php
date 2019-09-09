<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\State;
use App\DataTables\StateDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_cities'])->only('index');
        $this->middleware(['auth:admin','permission:create_cities'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_cities'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_cities'])->only('destroy','multiDelete');
    }

    public function index(StateDataTable $stateDataTable)
    {
        try{
            return $stateDataTable->render('backend.state.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            if (\request()->ajax())
            {
                if (\request()->has('city_id'))
                {
                    $select = \request()->has('select') ? \request('select') : '' ;
                    return \Form::select('state_id',
                        \App\StateTranslation::join('states','state_translations.state_id','=','states.id')->
                        where('states.city_id',\request('city_id'))->
                        where('state_translations.locale',\App::getLocale())
                            ->pluck('state_translations.name','state_translations.state_id')
                        ,
                        $select,['class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('admin.Select State Name')]) ;

                }
            }
            $city = City::where('status',1)->orderBy('created_at','desc')->get();
            return view('backend.state.create',compact('city'));

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
                    $locale .'.name' =>['required',Rule::unique('state_translations','name')]
                    ,'status'=>'required|in:1,0'
                    ,'city_id'=>'required|integer'

                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameState'),
                    'status.required' =>trans('admin.Status is required'),
                    'city_id.required' =>trans('admin.City name is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $request['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            State::create($request->all());

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.state.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $state = State::find($id);
            $city = City::all();
            if (empty($state))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.state.edit',compact('state','city'));
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
                    $locale . '.name' =>['required', Rule::unique('state_translations','name')->ignore($request->get('id'),'state_id')],
                    'status'=>'required|in:1,0'
                    ,'city_id'=>'required|integer'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameState'),
                    'status.required' =>trans('admin.Status is required'),
                    'city_id.required' =>trans('admin.City name is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $state = State::find($request->get('id'));
            if (empty($state))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $request['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $state->update($request->all());
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.state.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $state = State::find($id);
            if(empty($state))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            State::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.state.index');

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
                State::destroy(\request('item'));
            }
            else
            {
                State::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.state.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
