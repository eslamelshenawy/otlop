<?php

namespace App\Http\Controllers\Admin;

use App\blog;
use App\DataTables\blogDataTable;
use App\DataTables\RequestWorkingDataTable;
use App\RequestWorking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class RequestWorkingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_request'])->only('index');
        $this->middleware(['auth:admin','permission:create_request'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_request'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_request'])->only('destroy','multiDelete');
    }

    public function index(RequestWorkingDataTable $requestWorkingDataTable)
    {
        try{
            return $requestWorkingDataTable->render('backend.request.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {

    }

    public function store(Request $request)
    {


    }
    public function edit($id)
    {

    }

    public function update(Request $request)
    {

    }

    public function destroy($id)
    {
        try{
            $request = RequestWorking::find($id);
            \Storage::delete($request->file);
            if(empty($request))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            RequestWorking::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.request-working.index');

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
                    $request = RequestWorking::find(\request('item')[$i]);
                    \Storage::delete($request->file);
                }
                RequestWorking::destroy(\request('item'));
            }
            else
            {
                $request =  RequestWorking::find(\request('item'));
                \Storage::delete($request->file);
                RequestWorking::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.request-working.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
