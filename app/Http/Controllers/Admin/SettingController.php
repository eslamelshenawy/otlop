<?php

namespace App\Http\Controllers\Admin;

use App\ContactInformation;
use App\Seo;
use App\Setting;
use App\SocialMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','role:super_admin']);
    }

    public function getSetting()
    {
        try{
            $setting = Setting::find(\DB::table('settings')->value('id'));
            $seo = Seo::find(\DB::table('seos')->value('id'));

            $contactInformation = ContactInformation::find(\DB::table('seos')->value('id'));

            $socialMedia = SocialMedia::all();
            return view('backend.setting.setting',compact('setting','seo','contactInformation','socialMedia'));

        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function postSetting(Request $request)
    {
        try{
            $rules = $message = [];

            foreach (config('translatable.locales') as $locale)
            {
                $rules += [$locale . '.name' =>['required','string','min:3']
                    ,'copyright'=>'required|string|min:3',
                    'logo'=>validateImage(),
                    'icon'=>validateImage()
                ];

                $message += [
                    $locale .'.name.required' =>trans('admin.'.$locale.'.nameSetting'),
                    'copyright.required' =>trans('admin.Copyright is required'),
                ];
            }
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $updateSetting = Setting::find(\DB::table('settings')->value('id'));
            $requestData = $request->except(['logo','icon']);


            if($request->logo)
            {
                $requestData['logo'] = uploadImages($request->logo,'setting/',$updateSetting->logo);
            }
            else
            {
                $requestData['logo'] = $updateSetting->logo;
            }

            if($request->icon)
            {
                $requestData['icon'] = uploadImages($request->icon,'setting/',$updateSetting->icon);
            }
            else
            {
                $requestData['icon'] = $updateSetting->icon;

            }

            $updateSetting->update($requestData);

            return redirect()->back()->with('success',trans('admin.Data modified successfully '));

        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function seo(Request $request)
    {
        try{

            $rules = [];
            foreach (config('translatable.locales') as $locale)
            {
                $rules += [
                    $locale . '.keyword' =>'required|string|min:3',
                    $locale . '.description' =>'required|string|min:3'
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $requestData = $request->except(['_token']);

            $seo = Seo::find(\DB::table('seos')->value('id'));

            $seo->update($requestData);
            if ($seo)
            {
                return redirect()->back()->with('success',trans('admin.Data modified successfully'));

            }
            else
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }

        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function contactInformation(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'email' => ['required', 'string', 'email', 'max:255'],
                    'address'=>'required',
                    'phone'=>'required|numeric|numeric|min:2',
                    'mobile'=>'required|numeric|numeric|min:2',
                    'support_call'=>'required|numeric|min:2',
                ]);
            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $updateContactInfo = ContactInformation::find(\DB::table('contact_information')->value('id'));

            $updateContactInfo->email = $request->get('email');
            $updateContactInfo->phone = $request->get('phone');
            $updateContactInfo->address = $request->get('address');
            $updateContactInfo->mobile = $request->get('mobile');
            $updateContactInfo->support_call = $request->get('support_call');
            $updateContactInfo->save();
            if ($updateContactInfo->save())
            {
                return redirect()->back()
                    ->with('success',trans('admin.Data modified successfully '));
            }
            else
            {
                return redirect()->back()->with('warning',trans('admin.Try again'));
            }
        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function socialMedia(Request $request)
    {
        try{

            if ($request->value && $request->url)
            {
                 SocialMedia::query()->delete();
                for ($i = 0; $i<count(\request('value')); $i++)
                {
                    $social = new SocialMedia();
                    $social->key = str_slug($request->value[$i]);
                    $social->value = $request->value[$i];
                    $social->url = $request->url[$i];
                    $social->save();
                }

            }
            else
            {
                SocialMedia::query()->delete();
                return redirect()->back()
                    ->with('success',trans('admin.Data modified successfully '));
            }
            return redirect()->back()
                ->with('success',trans('admin.Data modified successfully '));
        } catch (\Exception $exception){

            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
