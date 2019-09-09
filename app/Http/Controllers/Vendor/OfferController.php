<?php

namespace App\Http\Controllers\Vendor;

use App\DataTables\OfferDataTable;
use App\Menu;
use App\MenuDetails;
use App\Offer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_menu'])->only('index');
        $this->middleware(['auth:admin','permission:create_menu'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_menu'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_menu'])->only('destroy','multiDelete');
    }

    public function index(OfferDataTable $offerDataTable)
    {
        try{
            return $offerDataTable->render('backend.vendor.offer.index');

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
                if (\request()->has('menu_details_id'))
                {
                    $menu = MenuDetails::find(\request('menu_details_id'));
                    return \Form::number('originalPrice',$menu->price,
                        ['class'=>'form-control','readonly','step'=>0.1,'min'=>0]) ;

                }
            }
            $menu = MenuDetails::where('restaurant_id',getRestaurant(\Auth::guard('admin')->user()->id)->id)
                ->where('status',1)->get();
            return view('backend.vendor.offer.create',compact('menu'));

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try{

            $date = Carbon::now();
            $thisDate = date('d-m-Y',strtotime($date));
            $rules = [
                'status'=>'required|in:1,0',
                'menu_details_id' => 'required|exists:menu_details,id',
                'fromTime'=>'required|date_format:H:i',
                'toTime'=>'required|date_format:H:i',
                'fromDate'=>'required|date_format:Y-m-d|before_or_equal:toDate|after_or_equal:'.$thisDate,
                'toDate'=>'required|date_format:Y-m-d|after_or_equal:fromDate',
                'originalPrice'=>'required|regex:/^\d*(\.\d{1,2})?$/',
                'price'=>'required|regex:/^\d*(\.\d{1,2})?$/|lte:originalPrice',
            ];
            $message = [
                'status.required' =>trans('admin.Status is required'),
                'menu_details_id.required' =>trans('admin.Meal name is required'),
                'fromTime.required' =>trans('admin.Start time name is required'),
                'toTime.required' =>trans('admin.End time is required'),
                'fromDate.required' =>trans('admin.Start date name is required'),
                'toDate.required' =>trans('admin.End date is required'),
                'originalPrice.required' =>trans('admin.Original price is required'),
                'price.required' =>trans('admin.Offer price is required'),
            ];
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $requestData = $request->except('_token','originalPrice');
            $requestData['restaurant_id'] = getDataRestaurant(\Auth::guard('admin')->user()->id)->id;
            $requestData['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            Offer::create($requestData);

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('vendor.offer.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $offer = Offer::find($id);
            $menu = MenuDetails::where('restaurant_id',getRestaurant(\Auth::guard('admin')->user()->id)->id)->get();
            if (empty($offer))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.vendor.offer.edit',compact('offer','menu'));
            }

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function update(Request $request)
    {
        try{
            $date = Carbon::now();
            $thisDate = date('d-m-Y',strtotime($date));
            $rules = [
                'status'=>'required|in:1,0',
                'menu_details_id' => 'required|exists:menu_details,id',
                'fromTime'=>'required',
                'toTime'=>'required',
                'fromDate'=>'required|date_format:Y-m-d|before_or_equal:toDate|after_or_equal:'.$thisDate,
                'toDate'=>'required|date_format:Y-m-d|after_or_equal:fromDate',
                'originalPrice'=>'required|regex:/^\d*(\.\d{1,2})?$/',
                'price'=>'required|regex:/^\d*(\.\d{1,2})?$/|lte:originalPrice',
            ];
            $message = [
                'status.required' =>trans('admin.Status is required'),
                'menu_details_id.required' =>trans('admin.Meal name is required'),
                'fromTime.required' =>trans('admin.Start time name is required'),
                'toTime.required' =>trans('admin.End time is required'),
                'fromDate.required' =>trans('admin.Start date name is required'),
                'toDate.required' =>trans('admin.End date is required'),
                'originalPrice.required' =>trans('admin.Original price is required'),
                'price.required' =>trans('admin.Offer price is required'),
            ];
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }

            $offer = Offer::find($request->get('id'));
            if (empty($offer))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $requestData = $request->except('originalPrice');

            $requestData['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;

            $offer->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('vendor.offer.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $offer = Offer::find($id);
            if(empty($offer))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            removeImage('offer/'.$offer->image);
            Offer::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.offer.index');

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
                Offer::destroy(\request('item'));
            }
            else
            {
                Offer::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.offer.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
