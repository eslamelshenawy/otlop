<?php

namespace App\Http\Controllers\Vendor;

use App\DataTables\MenuDataTable;
use App\DataTables\MenuDetailsDataTable;
use App\Menu;
use App\MenuDetails;
use App\OtherDataMenu;
use App\OtherDataMenuTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class MenuDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_menu'])->only('index');
        $this->middleware(['auth:admin','permission:create_menu'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_menu'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_menu'])->only('destroy','multiDelete');
    }

    public function index(MenuDetailsDataTable $menuDetailsDataTable)
    {

        try{
            return $menuDetailsDataTable->render('backend.vendor.menu-details.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            $menu = Menu::where('restaurant_id',getRestaurant(\Auth::guard('admin')->user()->id)->id)
               ->where('status',1)->orderBy('created_at','desc')->get();
            return view('backend.vendor.menu-details.create',compact('menu'));

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try{

           $requestOtherData = $request->except('en.name','en.description','ar.name','ar.description','price','image');




            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [
                    $locale .'.name' =>['required','min:3'],
                    $locale .'.description' =>['required','min:3'],
                    'menu_id'=>'required|exists:menus,id',
                    'status'=>'required|in:1,0',
                    'image'=>'required|'.validateImage(),
                    'period'=>'required|numeric'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.mealNameReq'),
                    $locale .'.description.required' =>trans('admin.'.$locale.'.mealDescriptionReq'),
                    'status.required' =>trans('admin.Status is required'),
                    'menu_id.required' =>trans('admin.Menu name is required'),
                    'image.required' =>trans('admin.Image is required'),
                    'period.required' =>trans('admin.Period is required')
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $requestData = $request->except('_token','image','en.title','ar.title','other_data');



            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'meal/','');
            }
            $requestData['image'] = $filename;
            $requestData['restaurant_id'] = getDataRestaurant(\Auth::guard('admin')->user()->id)->id;
            $requestData['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $menuDetails =  MenuDetails::create($requestData);

            if ($request->other_data)
            {
                for ($i=0; $i<count($request->other_data); $i++)
                {
                    $otherData = new OtherDataMenu();
                    $otherData->restaurant_id = getDataRestaurant(\Auth::guard('admin')->user()->id)->id;
                    $otherData->menu_id = $menuDetails->menu_id;
                    $otherData->menu_details_id = $menuDetails->id;
                    $otherData->price = $request->other_data[$i];
                    $otherData->created_by = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
                    $otherData->save();
                    if ($otherData->save())
                    {
                        foreach (config('translatable.locales') as $locale)
                        {
                            $otherDataTrans =  new OtherDataMenuTranslation();
                            $otherDataTrans->other_data_menu_id = $otherData->id;
                            $otherDataTrans->title =  $request[$locale.'.title'][$i];
                            $otherDataTrans->locale = $locale;
                            $otherDataTrans->save();
                        }

                    }
                }
            }

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('vendor.menu-details.index');


        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $menu = Menu::where('restaurant_id',getRestaurant(\Auth::guard('admin')->user()->id)->id)
               ->orderBy('created_at','desc')->get();
            $menuDetails = MenuDetails::find($id);

            $otherData = OtherDataMenu::where('menu_details_id',$id)->get();
            if (empty($menu))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.vendor.menu-details.edit',compact('menu','menuDetails','otherData'));
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
                    $locale . '.name' =>['required','min:3'],
                    $locale . '.description' =>['required','min:3'],
                    'menu_id'=>'required|exists:menus,id',
                    'status'=>'required|in:1,0',
                    'image'=>validateImage(),
                    'period'=>'required|numeric'
                ];
                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.mealNameReq'),
                    $locale .'.description.required' =>trans('admin.'.$locale.'.mealDescriptionReq'),
                    'menu_id.required' =>trans('admin.Menu name is required'),
                    'status.required' =>trans('admin.Status is required'),
                    'image.required' =>trans('admin.Image is required'),
                    'period.required' =>trans('admin.Period is required')
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }

            $requestData = $request->except('_token','image','en.title','ar.title','other_data');

            $menu = MenuDetails::find($request->get('id'));
            if (empty($menu))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }


            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'meal/',$menu->image);
            }
            else
            {
                $filename = $menu->image;
            }
            $requestData['image'] = $filename;
            $requestData['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;

            $menu->update($requestData);

            OtherDataMenu::where('menu_details_id',$request->get('id'))->delete();

            if ($request->other_data)
            {
                for ($i=0; $i<count($request->other_data); $i++)
                {
                    $otherData = new OtherDataMenu();
                    $otherData->restaurant_id = getDataRestaurant(\Auth::guard('admin')->user()->id)->id;
                    $otherData->menu_id = $menu->menu_id;
                    $otherData->menu_details_id = $menu->id;
                    $otherData->price = $request->other_data[$i];
                    $otherData->updated_by = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
                    $otherData->save();
                    if ($otherData->save())
                    {
                        foreach (config('translatable.locales') as $locale)
                        {
                            $otherDataTrans =  new OtherDataMenuTranslation();
                            $otherDataTrans->other_data_menu_id = $otherData->id;
                            $otherDataTrans->title =  $request[$locale.'.title'][$i];
                            $otherDataTrans->locale = $locale;
                            $otherDataTrans->save();
                        }

                    }
                }

            }
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('vendor.menu-details.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $menu = MenuDetails::find($id);
            if(empty($menu))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            removeImage('meal/'.$menu->image);

            OtherDataMenu::where('menu_details_id',$id)->delete();
            MenuDetails::find($id)->delete();

            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.menu-details.index');

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
                    $data = MenuDetails::find(\request('item')[$i]);
                    removeImage('meal/'.$data->image);
                    OtherDataMenu::where('menu_details_id',\request('item')[$i])->delete();
                }
                MenuDetails::destroy(\request('item'));
            }
            else
            {
                $menu =  MenuDetails::find(\request('item'));
                removeImage('meal/'.$menu->image);
                OtherDataMenu::where('menu_details_id',\request('item'))->delete();
                MenuDetails::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('vendor.menu-details.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }

    public function deleteOtherData(Request $request)
    {
        try{

            $data = OtherDataMenu::find($request->input('id'));

            if (empty($data))
            {
                echo trans('admin.Not found data ... please refresh this page');
            }
            else
            {
                $data->delete();
                echo trans('admin.Data has been deleted successfully');
            }
        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
