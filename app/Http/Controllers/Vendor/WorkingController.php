<?php

namespace App\Http\Controllers\Vendor;

use App\DataTables\WorkingDataTable;
use App\Day;
use App\WorkingHour;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','role:vendor']);
        
    }

    public function index(WorkingDataTable $workingDataTable)
    {
        try{
            return $workingDataTable->render('backend.vendor.working.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            $day = Day::all();
            return view('backend.vendor.working.create',compact('day'));

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try{

            $rules = [
                'day_id'=>'required|exists:days,id|unique:working_hours'
                ,'from'=>'required',
                'to'=>'required',
                'status' =>'required|in:1,0'
            ];
            $message = [
                'day_id.required' =>trans('admin.Day name is required'),
                'from.required' =>trans('admin.From time is required'),
                'to.required' =>trans('admin.To time is required'),
                'status.required' =>trans('admin.Status time is required'),
            ];
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $day = new WorkingHour();
            $day->restaurant_id = \DB::table('restaurants')
                ->where('admin_id',\Auth::guard('admin')->user()->id)->value('id');
            $day->day_id = $request->day_id;
            $day->from = $request->from;
            $day->to = $request->to;
            $day->status = $request->status;
            $day->save();
            if ($day->save())
            {
                session()->flash('success',trans('admin.Data has been updated successfully'));
                return redirect()->route('vendor.working.index');
            }
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $day = Day::all();
            $working = WorkingHour::find($id);
            if (empty($working))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.vendor.working.edit',compact('working','day'));
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
                'day_id'=>'required|exists:days,id|unique:working_hours,day_id,'.$request->day_id,
                'from'=>'required',
                'to'=>'required',
                'status' =>'required|in:1,0'
            ];
            $message = [
                'day_id.required' =>trans('admin.Day name is required'),
                'from.required' =>trans('admin.From time is required'),
                'to.required' =>trans('admin.To time is required'),
                'status.required' =>trans('admin.Status time is required'),
            ];
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $day = WorkingHour::find($request->id);
            $day->day_id = $request->day_id;
            $day->from = $request->from;
            $day->to = $request->to;
            $day->status = $request->status;
            $day->save();
            if ($day->save())
            {
                session()->flash('success',trans('admin.Data has been updated successfully'));
                return redirect()->route('vendor.working.index');
            }
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $working = WorkingHour::find($id);
            if(empty($working))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            WorkingHour::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.working.index');

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
                WorkingHour::destroy(\request('item'));
            }
            else
            {
                WorkingHour::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.working.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
