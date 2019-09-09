<?php

namespace App\Http\Controllers\Vendor;

use App\Admin;
use App\City;
use App\DataTables\RestaurantDataTable;
use App\DataTables\TypeDataTable;
use App\Day;
use App\Restaurant;
use App\State;
use App\Type;
use App\WorkingHour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','role:vendor'])->only('edit','update');
    }

   /* public function index(RestaurantDataTable $restaurantDataTable)
    {
        try{
            return $restaurantDataTable->render('backend.restaurants.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            $vendor = Admin::where('userType','vendor')->where('status',1)->get();
            $city = City::where('status',1)->orderBy('id','desc')->get();
            $type = Type::where('status',1)->orderBy('id','desc')->get();
            return view('backend.restaurants.create',compact('vendor','type','city'));

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
                    $locale .'.name' =>'required|string|min:2',
                    $locale .'.address' =>'required|string'
                    ,'city_id'=>'required|integer'
                    ,'state_id'=>'required|integer'
                    ,'type_id'=>'required|integer'
                    ,'admin_id'=>'required|integer'
                    ,'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameRes'),
                    $locale .'.address.required' =>trans('admin.'.$locale.'.addressRes'),
                    'status.required' =>trans('admin.Status is required'),
                    'city_id.required' =>trans('admin.City name is required'),
                    'state_id.required' =>trans('admin.State name is required'),
                    'type_id.required' =>trans('admin.Name of restaurant type is required'),
                    'admin_id.required' =>trans('admin.Name of restaurant owner is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $admin = Admin::find($request->admin_id);
            $city = City::find($request->city_id);
            $state = State::find($request->state_id);
            $type = Type::find($request->type_id);
            if (empty($admin) || empty($city) || empty($state) || empty($type))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }

            if ( $request->file('image') )
            {
                $image = uploadImages($request->image,'restaurant/','');
            }
            else
            {
                $image =  '';
            }
            if ( $request->file('logo'))
            {
                $logo = uploadImages($request->logo,'restaurant/','');
            }
            else
            {
                 $logo = '';
            }

            $requestData = $request->except('_token','image','logo');

            $requestData['image'] = $image;
            $requestData['logo'] = $logo;

            Restaurant::create($requestData);

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.restaurants.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }*/


    public function edit($id)
    {
        try{
            $city = City::where('status',1)->orderBy('id','desc')->get();
            $type = Type::where('status',1)->orderBy('id','desc')->get();
            $restaurant = Restaurant::find($id);
            $day = Day::all();
            if ( empty($city) || empty($type) || empty($restaurant) )
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            else
            {

                return view('backend.vendor.restaurants.edit',compact('restaurant','vendor','city','type','day'));
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
                    $locale .'.name' =>'required|string|min:2',
                    $locale .'.address' =>'required|string',
                    'city_id'=>'required|exists:cities,id',
                     'state_id'=>'required|exists:states,id'
                    ,'type_id'=>'required|exists:types,id'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameRes'),
                    $locale .'.address.required' =>trans('admin.'.$locale.'.addressRes'),
                    'city_id.required' =>trans('admin.City name is required'),
                    'state_id.required' =>trans('admin.State name is required'),
                    'type_id.required' =>trans('admin.Name of restaurant type is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $restaurant = Restaurant::find($request->get('id'));

            if ( $request->file('image') )
            {
                $image = uploadImages($request->image,'restaurant/',$restaurant->image);
            }
            else
            {
                $image =  $restaurant->image;
            }
            if ( $request->file('logo'))
            {
                $logo = uploadImages($request->logo,'restaurant/',$restaurant->logo);
            }
            else
            {
                $logo = $restaurant->logo;
            }

            $requestData = $request->except('_token','image','logo');

            $requestData['image'] = $image;
            $requestData['logo'] = $logo;
            $restaurant->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('vendor.home');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function workHours(Request $request)
    {
        $rules = [
            'id'=>'required|exists:restaurants,id',
            'day_id'=>'required|exists:days,id|unique:working_hours'
            ,'from'=>'required',
             'to'=>'required'
        ];
        $message = [
            'id.required' =>trans('admin.Please refresh page'),
            'day_id.required' =>trans('admin.Day name is required'),
            'from.required' =>trans('admin.From time is required'),
            'to.required' =>trans('admin.To time is required'),
        ];
        $validator = \Validator::make($request->all(), $rules,$message);

        if ($validator->fails())
        {
            return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
        }
        $data = WorkingHour::where('day_id',$request->day_id)->first();
        if (empty($data))
        {
            $day = new WorkingHour();
            $day->restaurant_id = $request->id;
            $day->day_id = $request->day_id;
            $day->from = $request->from;
            $day->to = $request->to;
            $day->save();
            if ($day->save())
            {
                session()->flash('success',trans('admin.Data has been updated successfully'));
                return redirect()->route('vendor.home');
            }
        }
        else
        {
            $update = WorkingHour::find($data->id);
            $update->from = $request->from;
            $update->to = $request->to;
            $update->save();
            if ($update->save())
            {
                session()->flash('success',trans('admin.Data has been updated successfully'));
                return redirect()->route('vendor.home');            }
        }

    }

   /* public function destroy($id)
    {
        try{
            $restaurant = Restaurant::find($id);
            if(empty($restaurant))
            {
                \File::delete('upload/restaurant/'.$restaurant->image);
                \File::delete('upload/restaurant/'.$restaurant->logo);
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Restaurant::find($id)->delete();
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
                Restaurant::destroy(\request('item'));
            }
            else
            {
                Restaurant::find(\request('item'))->delete();

            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.types.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }*/
}
