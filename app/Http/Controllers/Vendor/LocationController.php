<?php

namespace App\Http\Controllers\Vendor;

use App\City;
use App\DataTables\CityDataTable;
use App\DataTables\LocationDataTable;
use App\Location;
use App\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    public function __construct()
    {
       $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_location'])->only('index');
        $this->middleware(['auth:admin','permission:create_location'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_location'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_location'])
            ->only('destroy','multiDelete');
    }

    public function index(LocationDataTable $locationDataTable)
    {
        try{
            return $locationDataTable->render('backend.vendor.location.index');

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
           /* $restaurant = Restaurant::where('admin_id',\Auth::guard('admin')->user()->id)
                ->orderBy('created_at','desc')->get();*/
            $city = City::where('status',1) ->orderBy('created_at','desc')->get();
            return view('backend.vendor.location.create',compact('restaurant','city'));

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try{

            $rules = [
                'status'=>'required|in:1,0',
                'city_id'=>'required|exists:cities,id',
                'state_id'=>'required|exists:states,id',
                'address'=>'required|string|min:3',
                'lat'=>'required|string|min:3',
                'lng'=>'required|string|min:3'
            ];

            $message = [
                'status.required' =>trans('admin.Status is required'),
                'city_id.required' =>trans('admin.City name is required'),
                'state_id.required' =>trans('admin.State name is required'),
                'address.required' =>trans('admin.Address name is required'),
                'lat.required' =>trans('admin.Please refresh page'),
                'lng.required' =>trans('admin.Please refresh page'),

            ];


            $validator = \Validator::make($request->all(), $rules , $message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $location = new Location();
            $location->vendor_id = getDataVendor(\Auth::guard('admin')->user()->id);
            $location->restaurant_id = getDataRestaurant(\Auth::guard('admin')->user()->id)->id;
            $location->city_id = $request->city_id;
            $location->state_id = $request->state_id;
            $location->address = $request->address;
            $location->lat = $request->lat;
            $location->lng = $request->lng;
            $location->status = $request->status;
            $location->created_by = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $location->save();
            if ( $location->save())
            {
                session()->flash('success',trans('admin.Data has been added successfully'));
                return redirect()->route('vendor.location.index');
            }
            else
            {
                session()->flash('warning',trans('admin.Try again'));
                return redirect()->back()->withInput();
            }



        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $location = Location::find($id);
            $city = City::orderBy('created_at','desc')->get();
            if (empty($location))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.vendor.location.edit',compact('location','city'));
            }

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function update(Request $request)
    {
        try{
            $rules = [
                'status'=>'required|in:1,0',
                'city_id'=>'required|exists:cities,id',
                'state_id'=>'required|exists:states,id',
                'locationId'=>'required|exists:locations,id',
                'address'=>'required|string|min:3',
                'lat'=>'required|string|min:3',
                'lng'=>'required|string|min:3'
            ];

            $message = [
                'status.required' =>trans('admin.Status is required'),
                'city_id.required' =>trans('admin.City name is required'),
                'state_id.required' =>trans('admin.State name is required'),
                'address.required' =>trans('admin.Address name is required'),
                'lat.required' =>trans('admin.Please refresh page'),
                'lng.required' =>trans('admin.Please refresh page'),
                'locationId.required' =>trans('admin.Please refresh page'),

            ];
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $location = Location::find($request->get('locationId'));
            if (empty($location))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $request['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;

            $location->update($request->all());
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('vendor.location.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $location = Location::find($id);
            if(empty($location))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Location::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.location.index');

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
                Location::destroy(\request('item'));
            }
            else
            {
                Location::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.location.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
