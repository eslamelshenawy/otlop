<?php

namespace App\Http\Controllers\Admin;

use App\Blog;
use App\DataTables\BlogDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_blog'])->only('index');
        $this->middleware(['auth:admin','permission:create_blog'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_blog'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_blog'])->only('destroy','multiDelete');
    }

    public function index(BlogDataTable $blogDataTable)
    {
        try{
            return $blogDataTable->render('backend.blog.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            return view('backend.blog.create');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try{

            $rules = $message = [];
            $requestData = $request->except('_token','image');
            foreach (config('translatable.locales') as $locale)
            {
                $rules += [
                    $locale .'.title' =>['required',Rule::unique('blog_translations','title')],
                    $locale .'.description' =>['required','min:10']
                    ,'status'=>'required|in:1,0'
                    ,'image'=>'required|'.validateImage()
                ];
                $message += [
                    $locale .'.title.required' =>trans('admin.'.$locale.'.titleBlogReq'),
                    $locale .'.description.required' =>trans('admin.'.$locale.'.blogDescriptionReq'),
                    'status.required' =>trans('admin.Status is required'),
                    'image.required' =>trans('admin.Logo is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'blog/','');
            }
            $requestData['image'] = $filename;
            $requestData['created_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            Blog::create($requestData);

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.blog.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $blog = Blog::find($id);
            if (empty($blog))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.blog.edit',compact('blog'));
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
            $requestData = $request->except('_token','image');
            foreach (config('translatable.locales') as $locale)
            {
                $rules += [
                    $locale . '.title' =>['required', Rule::unique('blog_translations','title')->ignore($request->get('id'),'blog_id')],
                    'status'=>'required|in:1,0'
                    ,'image'=>validateImage(),
                ];
                $message += [
                    $locale .'.title.required' =>trans('admin.'.$locale.'.titleQuestionReq'),
                    'status.required' =>trans('admin.Status is required'),
                    'image.required' =>trans('admin.Logo is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $blog = Blog::find($request->get('id'));
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'blog/',$blog->image);
            }
            else
            {
                $filename = $blog->image;
            }
            $requestData['image'] = $filename;
            if (empty($blog))
            {
                session()->flash('warning',trans('admin.Please refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $requestData['updated_by'] = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $blog->update($requestData);
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.blog.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $blog = Blog::find($id);
            removeImage('blog/'.$blog->image);
            if(empty($blog))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Blog::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.blog.index');

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
                    $data = Blog::find(\request('item')[$i]);
                    removeImage('blog/'.$data->image);
                }
                Blog::destroy(\request('item'));
            }
            else
            {
                $blog =  blog::find(\request('item'));
                removeImage('admin/'.$blog->image);
                blog::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.blog.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
