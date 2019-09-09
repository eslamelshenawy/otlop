<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\DataTables\CityDataTable;
use App\DataTables\OpinionDataTable;
use App\DataTables\PageDataTable;
use App\DataTables\PageyDataTable;
use App\Opinion;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class OpinionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','role:super_admin']);
    }

    public function index(OpinionDataTable $opinionDataTable)
    {
        try{
            return $opinionDataTable->render('backend.opinion.index');

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
            $opinion = Opinion::find($id);
            if(empty($opinion))
            {
                session()->flash('warning',trans('admin.Please refresh this Opinion and try again'));
                return redirect()->back()->withInput();
            }
            Opinion::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.opinion.index');

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
                Opinion::destroy(\request('item'));
            }
            else
            {
                Opinion::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.opinion.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
