<?php

namespace App\Http\Controllers\Frontend;

use App\RatingRestaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class reviewRestaurants extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function reviewRes(Request $request)
    {
//        dd($request->all());
        if ($request->ajax())
        {
            $messages = [
                'comment.required' =>trans('web.Please fill content review'),
                'restaurant_id.required' =>trans('web.Please refresh this page'),
            ];
            $validator = \Validator::make($request->all(), [
                'comment'=>'required|min:3',
                'restaurant_id'=>'required|exists:restaurants,id',
            ], $messages);
            if ($validator->fails()) {
                return json_encode(['code'=>404, 'msg'=>$validator->errors()->first()]);
            }
            $review = RatingRestaurant::where('user_id',\Auth::guard('web')->user()->id)
                ->where('restaurant_id',$request->restaurant_id)
                ->first();
            if (empty($review))
            {
                $data = new RatingRestaurant();
                $data->user_id = \Auth::guard('web')->user()->id;
                $data->restaurant_id = $request->restaurant_id;
                $data->email = \Auth::guard('web')->user()->email;
                $data->mobile = \Auth::guard('web')->user()->mobile;
                $data->rating = $request->star;
                $data->comment = $request->comment;
                $data->save();

                if ($data->save())
                {
                    return json_encode(['code'=>200, 'msg'=>trans('web.Done review successfully')]);
                }
            }
            else
            {
                $success = RatingRestaurant::find($review->id);
                $success->user_id = \Auth::guard('web')->user()->id;
                $success->restaurant_id = $request->restaurant_id;
                $success->email = \Auth::guard('web')->user()->email;
                $success->mobile = \Auth::guard('web')->user()->mobile;
                $success->rating = $request->star;
                $success->comment = $request->comment;
                $success->save();
                if ($success->save())
                {
                    return json_encode(['code'=>200, 'msg'=>trans('web.Done review successfully')]);

                }
            }

        }
    }
}
