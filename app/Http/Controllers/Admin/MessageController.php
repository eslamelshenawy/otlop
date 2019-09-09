<?php

namespace App\Http\Controllers\Admin;

use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware(['auth:admin','role:super_admin']);
    }
    #message
    public function viewMessage()
    {
        try{

            $message = \DB::table('messages')->orderBy('created_at','DESC')->paginate(10);
            return view('backend.message.inbox',compact('message'));

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function composeMessage()
    {
        return view('backend.message.compose');
    }

    public function readMessage($id)
    {
        $message = \DB::table('messages')->where('id','=',$id)->get();
        if ($message->isEmpty())
        {
            return redirect()->back()->with('error',trans('admin.Not Found this message'));
        }
        else
        {
            $message = \DB::table('messages')->where('id','=',$id)->first();
            \DB::table('messages')->where('id','=',$id)
                ->update(['read'=>1]);
            return view('backend.message.read',compact('message'));

        }
    }

    public function deleteMessageMessage($id)
    {
        $message = \DB::table('messages')->where('id','=',$id)->get();
        if ($message->isEmpty())
        {
            return redirect()->back()->with('error',trans('admin.Not Found this message'));
        }
        else
        {
            \DB::table('messages')->where('id','=',$id)->delete();
            return redirect()->route('admin.message.inbox')->with('success',trans('admin.Delete Message success'));

        }
    }

    public function getReplyMessage($id)
    {
        $message = \DB::table('messages')->where('id','=',$id)->first();
        if (empty($message))
        {
            return redirect()->back()->with('error',trans('admin.Not Found this message'));
        }
        else
        {
            return view('backend.message.reply',compact('message'));
        }
    }

    public function sendMailMessage(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'emailTo'=>'required|email|string',
                    'subject'=>'required|min:3|string',
                    'type'=>'required|in:vendor,customer,others,delivery',
                    'message'=>'required'
                ]);
            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $message = new Message();
            $message->name = \Auth::guard('admin')->user()->firstName.' '.\Auth::guard('admin')->user()->lastName;
            $message->emailTo = $request->get('emailTo');
            $message->emailSend = \Auth::guard('admin')->user()->email;
            $message->subject = $request->get('subject');
            $message->message = $request->get('message');
            $message->type = $request->get('type');
            $message->read = 1;
            $message->send = 1;
            $message->save();
            if ($message->save())
            {
                $data =
                    [
                        'email'=>$message->email,
                        'subject'=>$message->subject,
                        'bodyMessage'=>$message->message,

                    ];
                \Mail::send('backend.mail.mail',$data,function ($messages) use ($data){
                    $messages->from(\Auth::guard('admin')->user()->email);
                    $messages->to($data['email']);
                    $messages->subject($data['subject']);

                });

                return redirect()->route('admin.message.inbox')->with('success',trans('admin.Done Send Mail Success'));
            }
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }

   /* public function sendMailMessageDashboard(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(),
                [
                    'emailTo'=>'required|email|string',
                    'subject'=>'required|min:3|string',
                    'message'=>'required'
                ]);
            if ($validator->fails())
            {
                return redirect()->back()->with('warning',$validator->errors()->first())->withInput();
            }
            $message = new Message();
            $message->name = \Auth::guard('admin')->user()->name;
            $message->emailTo = $request->get('emailTo');
            $message->emailSend = \Auth::guard('admin')->user()->email;
            $message->subject = $request->get('subject');
            $message->message = $request->get('message');
            $message->read = 1;
            $message->send = 1;
            $message->save();
            if ($message->save())
            {
                $data =
                    [
                        'email'=>$message->email,
                        'subject'=>$message->subject,
                        'bodyMessage'=>$message->message,

                    ];
                \Mail::send('backend.mail.mail',$data,function ($messages) use ($data){
                    $messages->from(\Auth::guard('admin')->user()->email);
                    $messages->to($data['email']);
                    $messages->subject($data['subject']);

                });

                return redirect()->route('admin.home')->with('success',trans('admin.Done Send Mail Success'));
            }
        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }
    }*/
}
