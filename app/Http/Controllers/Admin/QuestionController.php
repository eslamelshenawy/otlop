<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\DataTables\QuestionDataTable;
use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','permission:read_question'])->only('index');
        $this->middleware(['auth:admin','permission:create_question'])->only('create','store');
        $this->middleware(['auth:admin','permission:update_question'])->only('edit','update');
        $this->middleware(['auth:admin','permission:delete_question'])->only('destroy','multiDelete');
    }

    public function index(QuestionDataTable $questionDataTable)
    {
        try{
            return $questionDataTable->render('backend.question.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function create()
    {
        try{
            $category = Category::where('status',1)->orderBy('created_at','DESC')->get();
            return view('backend.question.create',compact('category'));

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
                    $locale .'.title' =>['required',Rule::unique('question_translations','title')],
                    $locale .'.description' =>['required','min:10']
                    ,'status'=>'required|in:1,0'
                    ,'category_id'=>'required|exists:categories,id'
                ];
                $message += [
                    $locale .'.title.required' =>trans('admin.'.$locale.'.titleQuestionReq'),
                    $locale .'.description.required' =>trans('admin.'.$locale.'.questionDescriptionReq'),
                    'status.required' =>trans('admin.Status is required'),
                    'category_id.required' =>trans('admin.Category is required'),
                    ];

            }
            $validator = \Validator::make($request->all(), $rules,$message);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            Question::create($request->all());

            session()->flash('success',trans('admin.Data has been added successfully'));
            return redirect()->route('admin.question.index');
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function edit($id)
    {
        try{
            $category = Category::orderBy('created_at','DESC')->get();
            $question = Question::find($id);
            if (empty($question))
            {
                return redirect()->back()->with('warning',trans('admin.Please refresh this page and try again'))->withInput();
            }
            else
            {
                return view('backend.question.edit',compact('question','category'));
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
                    $locale . '.title' =>['required', Rule::unique('question_translations','title')->ignore($request->get('id'),'question_id')],
                    'status'=>'required|in:1,0'
                    ,'category_id'=>'required|exists:categories,id'
                ];
                $message += [
                    $locale .'.title.required' =>trans('admin.'.$locale.'.titleQuestionReq'),
                    'status.required' =>trans('admin.Status is required'),
                    'category_id.required' =>trans('admin.Category is required'),
                ];

            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $question = Question::find($request->get('id'));
            if (empty($question))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            $question->update($request->all());
            session()->flash('success',trans('admin.Data has been updated successfully'));
            return redirect()->route('admin.question.index');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try{
            $question = Question::find($id);
            if(empty($question))
            {
                session()->flash('warning',trans('admin.DPlease refresh this page and try again'));
                return redirect()->back()->withInput();
            }
            Question::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.question.index');

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
                Question::destroy(\request('item'));
            }
            else
            {
                Question::find(\request('item'))->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.question.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
}
