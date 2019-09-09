<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\City;
use App\DataTables\RestaurantDataTable;
use App\DataTables\TypeDataTable;
use App\Location;
use App\Restaurant;
use App\State;
use App\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_restaurants'])->only('index');
        $this->middleware(['auth:admin','permission:create_restaurants'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_restaurants'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_restaurants'])->only('destroy','multiDelete');
    }

    public function index(RestaurantDataTable $restaurantDataTable)
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
            $restaurants = Restaurant::all();
            $restaurant [] ='';
            for ($i=0; $i<count($restaurants); $i++)
            {
                $restaurant [] = $restaurants[$i]->admin_id;
            }
            $vendor = Admin::where('userType','vendor')
                ->whereNotIn('id',$restaurant)
                ->where('status',1)->get();

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
            $requestData['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $restaurant = Restaurant::create($requestData);
            if ($restaurant)
            {
                $location = new Location();
                $location->vendor_id = $restaurant->admin_id;
                $location->restaurant_id = $restaurant->id;
                $location->city_id = $restaurant->city_id;
                $location->state_id = $restaurant->state_id;
                $location->save();
                if ($location->save())
                {
                    session()->flash('success',trans('admin.Data has been added successfully'));
                    return redirect()->route('admin.restaurants.index');
                }
            }
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $restaurant = Restaurant::find($id);

            $city = City::where('status',1)->orderBy('id','desc')->get();
            $type = Type::where('status',1)->orderBy('id','desc')->get();

            $vendor = Admin::where('userType','vendor')
                ->where('id',$restaurant->admin_id)
                ->where('status',1)->get();
            if (empty($vendor) || empty($city) || empty($type) || empty($restaurant) )
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            else
            {

                return view('backend.restaurants.edit',compact('restaurant','vendor','city','type'));
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
                    $locale .'.address' =>'required|string'
                    ,'state_id'=>'required|integer'
                    ,'city_id'=>'required|integer'
                    ,'type_id'=>'required|integer'
                    ,'status'=>'required|in:1,0'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameRes'),
                    $locale .'.address.required' =>trans('admin.'.$locale.'.addressRes'),
                    'status.required' =>trans('admin.Status is required'),
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
            $city = City::find($request->city_id);
            $state = State::find($request->state_id);
            $type = Type::find($request->type_id);
            if ( empty($city) || empty($state) || empty($type) || empty($restaurant))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }

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
            if (empty($type))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $requestData = $request->except('_token','image','logo');

            $requestData['image'] = $image;
            $requestData['logo'] = $logo;
            $requestData['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;

            $restaurant->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.restaurants.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
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
                for ($i = 0; $i<count(\request('item')); $i++)
                {
                    $data = Restaurant::find(\request('item')[$i]);
                    removeImage('restaurant/'.$data->image);
                    removeImage('restaurant/'.$data->logo);
                }
                Restaurant::destroy(\request('item'));
            }
            else
            {
                $restaurant = Restaurant::find(\request('item'));
                removeImage('restaurant/'.$restaurant->image);
                removeImage('restaurant/'.$restaurant->logo);
                $restaurant->delete();

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
