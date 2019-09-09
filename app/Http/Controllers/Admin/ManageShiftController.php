<?php

namespace App\Http\Controllers\Admin;

use App\ManageShift;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManageShiftController extends Controller
{
    public function __construct()
    {

    }

    public function create()
    {
        try{
            $data = ManageShift::find(1);
            if (empty($data))
            {
               $data =  ManageShift::create([]);
               return view('backend.account.manage_account_deliver',compact('data'));
            }
            else
            {
                return view('backend.account.manage_account_deliver',compact('data'));
            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function store(Request $request)
    {
        try{
            $rules = [
                'total_price_morning'=>'required|regex:/^\d*(\.\d{1,2})?$/',
                'total_price_night'=>'required|regex:/^\d*(\.\d{1,2})?$/',
                'delivery_visa_morning'=>'required|regex:/^\d*(\.\d{1,2})?$/|lte:total_price_morning',
                'delivery_visa_night'=>'required|regex:/^\d*(\.\d{1,2})?$/|lte:total_price_night',
                'organization_visa_morning'=>'required|regex:/^\d*(\.\d{1,2})?$/|lte:total_price_morning',
                'organization_visa_night'=>'required|regex:/^\d*(\.\d{1,2})?$/|lte:total_price_night',
                'fromTime'=>'required|before:toTime',
                'toTime'=>'required|after:fromTime',
                'percentageOrder'=>'required|min:0|max:100|numeric'
            ];
            $message = [
                'total_price_morning.required' => trans('admin.Total price morning is required'),
                'total_price_night.required' => trans('admin.Total price night is required'),
                'delivery_visa_morning.required' => trans('admin.Delivery visa morning is required'),
                'delivery_visa_night.required' => trans('admin.Delivery visa night is required'),
                'organization_visa_morning.required' => trans('admin.Organization visa morning is required'),
                'organization_visa_night.required' => trans('admin.Organization visa night is required'),
                'fromTime.required' => trans('admin.Start time is required'),
                'toTime.required' => trans('admin.End time is required'),
                'percentageOrder.required' => trans('admin.Percentage order is required'),
            ];
            $validate = \Validator::make($request->all(),$rules,$message);
            if ($validate->fails())
            {
                return redirect()->back()->withErrors($validate)->withInput();
            }
            if ($request->delivery_visa_morning + $request->organization_visa_morning  != $request->total_price_morning) {

              return redirect()->back()->with('warning',trans('admin.The sum of ').trans('admin.delivery_visa_morning').trans('admin.and').
                  trans('admin.organization_visa_morning').trans('admin. must be equal').trans('admin.total_price_morning'));
            }
            elseif ($request->delivery_visa_night + $request->organization_visa_night  != $request->total_price_night)
            {

                return redirect()->back()->with('warning',trans('admin.The sum of ').trans('admin.delivery_visa_night').trans('admin.and').
                    trans('admin.organization_visa_night').trans('admin. must be equal').trans('admin.total_price_night'))->withInput();

            }
            $data = ManageShift::find(1);
            if (empty($data))
            {
                ManageShift::create([
                    'total_price_morning'=>$request->total_price_morning,
                    'total_price_night'=>$request->total_price_night,
                    'delivery_visa_morning'=>$request->delivery_visa_morning,
                    'delivery_visa_night'=>$request->delivery_visa_night,
                    'organization_visa_morning'=>$request->organization_visa_morning,
                    'organization_visa_night'=>$request->organization_visa_night,
                    'fromTime'=>$request->fromTime,
                    'toTime'=>$request->toTime,
                    'percentageOrder'=>$request->percentageOrder,
                ]);
                session()->flash('success',trans('admin.Done updated data successfully'));
                return redirect()->route('admin.manage.shift');
            }
            else
            {
                $data->total_price_morning = $request->total_price_morning;
                $data->total_price_night = $request->total_price_night;
                $data->delivery_visa_morning = $request->delivery_visa_morning;
                $data->delivery_visa_night = $request->delivery_visa_night;
                $data->organization_visa_morning = $request->organization_visa_morning;
                $data->organization_visa_night = $request->organization_visa_night;
                $data->fromTime = $request->fromTime;
                $data->toTime = $request->toTime;
                $data->percentageOrder = $request->percentageOrder;

                $data->save ();
                if ($data->save())
                {
                    session()->flash('success',trans('admin.Done updated data successfully'));
                    return redirect()->route('admin.manage.shift');
                }
            }
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
