<?php

namespace App\Http\Controllers\Frontend;

use App\Cart;
use App\MenuDetails;
use App\Restaurant;
use App\State;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('loadData');
    }

    public function fetchStats(Request $request)
    {

        if (\Auth::guard('web')->user())
        if (\request()->ajax())
        {
            if (\request()->has('menuDetailsId'))
            {
                $restaurant = MenuDetails::find($request->menuDetailsId);
                $cartCheck =  Cart::where('user_id',\Auth::guard('web')->user()->id)->first();
                if (empty($cartCheck))
                {
                    $carts = new Cart();
                    $carts->user_id = \Auth::guard('web')->user()->id;
                    $carts->menu_details_id = $request->menuDetailsId;
                    $carts->restaurant_id = MenuDetails::find($request->menuDetailsId)->restaurant_id;
                    $carts->qty = 1 ;
                    $carts->save();
                    if ($carts->save())
                    {
                        $all = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();


                        $Subtotal = 0;
                        $wel  ='';
                        $head = '<div class=" hidden_sm"> <h5>'.trans('web.Your Cart').'</h5> <span class="name_owner">'.\request('name').'</span>';
                        foreach($all as $key =>$value){
                            $Subtotal  += percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price) * $value->qty;
                            $wel = $wel.'<div class="qty_order pl-3 pr-3 clearfix"> <div class="number_pl"> <span class="minus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">-</span> <input type="text"  value="'.$value->qty.'"/> <span class="plus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">+</span> </div> <i class="fa fa-times-circle pull-right removeCart" data-id="'.$value->id.'" data-name="'.\request('name').'"></i>  <span class="name_order">'.trans('web.Price').' :'.percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price).'</span>  </div>   ';

                        }
                        $totalAmount = $Subtotal + deliveryFees()['amountDelivery'];
                        $footer = ' <div class="sub_total"> <ul class="nav pl-4 pr-4"> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Subtotal').'</span> <span class="pull-right"> '.$Subtotal.'</span> </li> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Delivery Fees').'</span> <span class="pull-right">'.deliveryFees()['amountDelivery'].'</span> </li> <li class="clearfix mb-3 mt-5"> <span class="pull-left">'.trans('web.Total Amount').'</span> <span class="pull-right">'.$totalAmount.'</span> </li> </ul> <a href="'.route('web.get.checkout').'" class="btn_chk_cart btn btn-primary btn-block"> '.trans('web.Proceed to Checkout').' </a> </div> </div>';

                        $content = '';
                        foreach($all as $key =>$value)
                        {
                            $content = '<li> <img src="'.MenuDetails::find($value->menu_details_id)->imagePath.'" class="img-fluid pull-left" alt=""> <div class="info_course_c pull-left"> <h6>'.MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name.'</h6> <div class="qty-wrap"> <input class="qty" type="text" value="'.$value->qty.'"> </div> <strong class="price">'.trans('web.S.R').' '.percentageOrder(MenuDetails::find($value->menu_details_id)->price).'</strong> </div> <i class="fa fa-times"></i> </li>';
                        }
                        $footer2 = '<li> <strong>'.trans('web.Total : S.R ').$Subtotal.'</strong> <a href="" class="btn_chk_cart">'.trans('web.Go To Cart').'</a> </li>';

                        $header = '<div class="cart_h pull-right mr-4 myCart"> <span id="btn_show_cart" class="cart_ppt"> <i id="show_cart" class="fa fa-shopping-cart fa-lg"></i> <b class="count_cart_ppt">'.count($all).'</b> </span> <ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i>';

                        return [
                            'cart'=>$head.$wel.$footer,
                            'myCart' => $header.$content.$footer2,
                        ];
                    }
                }
                if ($cartCheck->restaurant_id == $restaurant->restaurant_id)
                {
                    $cart = Cart::where('user_id',\Auth::guard('web')->user()->id)
                        ->where('menu_details_id',$request->menuDetailsId)->first();



                    if (empty($cart))
                    {
                        $carts = new Cart();
                        $carts->user_id = \Auth::guard('web')->user()->id;
                        $carts->menu_details_id = $request->menuDetailsId;
                        $carts->restaurant_id = MenuDetails::find($request->menuDetailsId)->restaurant_id;
                        $carts->qty = 1 ;
                        $carts->save();

                    }
                    else
                    {

                        $qty = $cart->qty;
                        $data = Cart::find($cart->id);
                        $data->qty = $qty + 1 ;
                        $data->save();
                    }
                    $all = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();


                    $Subtotal = 0;
                    $wel  ='';
                    $head = '<div class=" hidden_sm"> <h5>'.trans('web.Your Cart').'</h5> <span class="name_owner">'.\request('name').'</span>';
                    foreach($all as $key =>$value){
                        $Subtotal  += percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price) * $value->qty;
                        $wel = $wel.'<div class="qty_order pl-3 pr-3 clearfix"> <div class="number_pl"> <span class="minus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">-</span> <input type="text"  value="'.$value->qty.'"/> <span class="plus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">+</span> </div> <i class="fa fa-times-circle pull-right removeCart" data-id="'.$value->id.'" data-name="'.\request('name').'"></i>  <span class="name_order">'.trans('web.Price').' :'.percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price).'</span>  </div>   ';

                    }
                    $totalAmount = $Subtotal + deliveryFees()['amountDelivery'];
                    $footer = ' <div class="sub_total"> <ul class="nav pl-4 pr-4"> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Subtotal').'</span> <span class="pull-right"> '.$Subtotal.'</span> </li> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Delivery Fees').'</span> <span class="pull-right">'.deliveryFees()['amountDelivery'].'</span> </li> <li class="clearfix mb-3 mt-5"> <span class="pull-left">'.trans('web.Total Amount').'</span> <span class="pull-right">'.$totalAmount.'</span> </li> </ul> <a href="'.route('web.get.checkout').'" class="btn_chk_cart btn btn-primary btn-block"> '.trans('web.Proceed to Checkout').' </a> </div> </div>';

                    $content = '';
                    foreach($all as $key =>$value)
                    {
                        $content = '<li> <img src="'.MenuDetails::find($value->menu_details_id)->imagePath.'" class="img-fluid pull-left" alt=""> <div class="info_course_c pull-left"> <h6>'.MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name.'</h6> <div class="qty-wrap"> <input class="qty" type="text" value="'.$value->qty.'"> </div> <strong class="price">'.trans('web.S.R').' '.percentageOrder(MenuDetails::find($value->menu_details_id)->price).'</strong> </div> <i class="fa fa-times"></i> </li>';
                    }
                    $footer2 = '<li> <strong>'.trans('web.Total : S.R ').$Subtotal.'</strong> <a href="" class="btn_chk_cart">'.trans('web.Go To Cart').'</a> </li>';

                    $header = '<div class="cart_h pull-right mr-4 myCart"> <span id="btn_show_cart" class="cart_ppt"> <i id="show_cart" class="fa fa-shopping-cart fa-lg"></i> <b class="count_cart_ppt">'.count($all).'</b> </span> <ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i>';

                    return [
                        'cart'=>$head.$wel.$footer,
                        'myCart' => $header.$content.$footer2,
                        'myAlert' => 'false',
                    ];

                }
                else
                {
                    return [
                        'myAlert' => 'true',
                    ];
                }

            }

            if (\request()->has('cartId'))
            {
                $cart = Cart::find($request->cartId);
                if (empty($cart))
                {
                  return redirect()->back();
                }
                else
                {
                    $cart->delete();
                }
                $all = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();



                $Subtotal = 0;
                $wel  ='';
                $head = '<div class=" hidden_sm"> <h5>'.trans('web.Your Cart').'</h5> <span class="name_owner">'.\request('name').'</span>';
                foreach($all as $key =>$value){
                    $Subtotal  += percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price)  * $value->qty;
                    $wel = $wel.'<div class="qty_order pl-3 pr-3 clearfix"> <div class="number_pl"> <span class="minus" data-id="'. $value->menu_details_id .'" data-name="'. \request('name') .'">-</span> <input type="text"  value="'.$value->qty.'"/> <span class="plus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">+</span> </div> <i class="fa fa-times-circle pull-right removeCart" data-id="'.$value->id.'" data-name="'.\request('name').'"></i>  <span class="name_order">'.trans('web.Price').' :'.percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price).'</span>  </div>   ';

                }
                $totalAmount = $Subtotal + deliveryFees()['amountDelivery'];
                $footer = ' <div class="sub_total"> <ul class="nav pl-4 pr-4"> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Subtotal').'</span> <span class="pull-right"> '.$Subtotal.'</span> </li> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Delivery Fees').'</span> <span class="pull-right">'.deliveryFees()['amountDelivery'].'</span> </li> <li class="clearfix mb-3 mt-5"> <span class="pull-left">'.trans('web.Total Amount').'</span> <span class="pull-right">'.$totalAmount.'</span> </li> </ul> <a href="'.route('web.get.checkout').'" class="btn_chk_cart btn btn-primary btn-block"> '.trans('web.Proceed to Checkout').' </a> </div> </div>';

                $found = '';
                if (count($all) >0)
                {
                    $found = $wel.$footer;
                }
                else
                {
                    $found = '<div class="sub_total">  <span class="name_owner"> '.trans('web.Cart is Empty').'</span> </div>';
                }


                $content = '';
                foreach($all as $key =>$value)
                {
                    $content = '<li> <img src="'.MenuDetails::find($value->menu_details_id)->imagePath.'" class="img-fluid pull-left" alt=""> <div class="info_course_c pull-left"> <h6>'.MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name.'</h6> <div class="qty-wrap"> <input class="qty" type="text" value="'.$value->qty.'"> </div> <strong class="price">'.trans('web.S.R').' '.percentageOrder(MenuDetails::find($value->menu_details_id)->price).'</strong> </div> <i class="fa fa-times"></i> </li>';
                }

                $footer2 = '<li> <strong>'.trans('web.Total : S.R ').$Subtotal.'</strong> <a href="" class="btn_chk_cart">'.trans('web.Go To Cart').'</a> </li>';

                $header = '<div class="cart_h pull-right mr-4 myCart"> <span id="btn_show_cart" class="cart_ppt"> <i id="show_cart" class="fa fa-shopping-cart fa-lg"></i> <b class="count_cart_ppt">'.count($all).'</b> </span> <ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i>';

                return [
                    'cart'=>$head.$found,
                    'myCart' => $header.$content.$footer2,
                ];

            }

            if (\request()->has('minus'))
            {
                $cart = Cart::where('user_id',\Auth::guard('web')->user()->id)
                    ->where('menu_details_id',$request->minus)->first();

                $oldValue = $cart->qty;

                $success = \DB::table('carts')->
                where('user_id',\Auth::guard('web')->user()->id)
                        ->where('menu_details_id',$request->minus)
                    ->update(['qty'=>$oldValue - 1]);
                if ($success)
                {
                    $all = Cart::where('user_id',\Auth::guard('web')->user()->id)
                        ->get();


                    $Subtotal = 0;
                    $wel  ='';
                    $head = '<div class=" hidden_sm"> <h5>'.trans('web.Your Cart').'</h5> <span class="name_owner">'.\request('name').'</span>';
                    foreach($all as $key =>$value){
                        $Subtotal  += percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price) * $value->qty;
                        $wel = $wel.'<div class="qty_order pl-3 pr-3 clearfix"> <div class="number_pl"> <span class="minus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">-</span> <input type="text"  value="'.$value->qty.'"/> <span class="plus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">+</span> </div> <i class="fa fa-times-circle pull-right removeCart" data-id="'.$value->id.'" data-name="'.\request('name').'"></i>  <span class="name_order">'.trans('web.Price').' :'.percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price).'</span>  </div>   ';

                    }
                    $totalAmount = $Subtotal + deliveryFees()['amountDelivery'];
                    $footer = ' <div class="sub_total"> <ul class="nav pl-4 pr-4"> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Subtotal').'</span> <span class="pull-right"> '.$Subtotal.'</span> </li> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Delivery Fees').'</span> <span class="pull-right">'.deliveryFees()['amountDelivery'].'</span> </li> <li class="clearfix mb-3 mt-5"> <span class="pull-left">'.trans('web.Total Amount').'</span> <span class="pull-right">'.$totalAmount.'</span> </li> </ul> <a href="'.route('web.get.checkout').'" class="btn_chk_cart btn btn-primary btn-block"> '.trans('web.Proceed to Checkout').' </a> </div> </div>';

                    $content = '';
                    foreach($all as $key =>$value)
                    {
                        $content = '<li> <img src="'.MenuDetails::find($value->menu_details_id)->imagePath.'" class="img-fluid pull-left" alt=""> <div class="info_course_c pull-left"> <h6>'.MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name.'</h6> <div class="qty-wrap"> <input class="qty" type="text" value="'.$value->qty.'"> </div> <strong class="price">'.trans('web.S.R').' '.percentageOrder(MenuDetails::find($value->menu_details_id)->price).'</strong> </div> <i class="fa fa-times"></i> </li>';
                    }
                    $footer2 = '<li> <strong>'.trans('web.Total : S.R ').$Subtotal.'</strong> <a href="" class="btn_chk_cart">'.trans('web.Go To Cart').'</a> </li>';

                    $header = '<div class="cart_h pull-right mr-4 myCart"> <span id="btn_show_cart" class="cart_ppt"> <i id="show_cart" class="fa fa-shopping-cart fa-lg"></i> <b class="count_cart_ppt">'.count($all).'</b> </span> <ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i>';

                    return [
                        'cart'=>$head.$wel.$footer,
                        'myCart' => $header.$content.$footer2,
                    ];
                }



            }

            if (\request()->has('plus'))
            {
                $cart = Cart::where('user_id',\Auth::guard('web')->user()->id)
                    ->where('menu_details_id',$request->plus)->first();

                $oldValue = $cart->qty;

                $success = \DB::table('carts')->
                where('user_id',\Auth::guard('web')->user()->id)
                    ->where('menu_details_id',$request->plus)
                    ->update(['qty'=>$oldValue + 1]);
                if ($success)
                {
                    $all = Cart::where('user_id',\Auth::guard('web')->user()->id)
                        ->get();


                    $Subtotal = 0;
                    $wel  ='';
                    $head = '<div class=" hidden_sm"> <h5>'.trans('web.Your Cart').'</h5> <span class="name_owner">'.\request('name').'</span>';
                    foreach($all as $key =>$value){
                        $Subtotal  += percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price) * $value->qty;
                        $wel = $wel.'<div class="qty_order pl-3 pr-3 clearfix"> <div class="number_pl"> <span class="minus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">-</span> <input type="text"  value="'.$value->qty.'"/> <span class="plus" data-id="'. $value->menu_details_id .'" data-name="'.\request('name').'">+</span> </div> <i class="fa fa-times-circle pull-right removeCart" data-id="'.$value->id.'" data-name="'.\request('name').'"></i>  <span class="name_order">'.trans('web.Price').' :'.percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price).'</span>  </div>   ';

                    }
                    $totalAmount = $Subtotal + deliveryFees()['amountDelivery'];
                    $footer = ' <div class="sub_total"> <ul class="nav pl-4 pr-4"> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Subtotal').'</span> <span class="pull-right"> '.$Subtotal.'</span> </li> <li class="clearfix mb-3"> <span class="pull-left">'.trans('web.Delivery Fees').'</span> <span class="pull-right">'.deliveryFees()['amountDelivery'].'</span> </li> <li class="clearfix mb-3 mt-5"> <span class="pull-left">'.trans('web.Total Amount').'</span> <span class="pull-right">'.$totalAmount.'</span> </li> </ul> <a href="'.route('web.get.checkout').'" class="btn_chk_cart btn btn-primary btn-block"> '.trans('web.Proceed to Checkout').' </a> </div> </div>';

                    $content = '';
                    foreach($all as $key =>$value)
                    {
                        $content = '<li> <img src="'.MenuDetails::find($value->menu_details_id)->imagePath.'" class="img-fluid pull-left" alt=""> <div class="info_course_c pull-left"> <h6>'.MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name.'</h6> <div class="qty-wrap"> <input class="qty" type="text" value="'.$value->qty.'"> </div> <strong class="price">'.trans('web.S.R').' '.percentageOrder(MenuDetails::find($value->menu_details_id)->price).'</strong> </div> <i class="fa fa-times"></i> </li>';
                    }
                    $footer2 = '<li> <strong>'.trans('web.Total : S.R ').$Subtotal.'</strong> <a href="" class="btn_chk_cart">'.trans('web.Go To Cart').'</a> </li>';

                    $header = '<div class="cart_h pull-right mr-4 myCart"> <span id="btn_show_cart" class="cart_ppt"> <i id="show_cart" class="fa fa-shopping-cart fa-lg"></i> <b class="count_cart_ppt">'.count($all).'</b> </span> <ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i>';

                    return [
                        'cart'=>$head.$wel.$footer,
                        'myCart' => $header.$content.$footer2,
                    ];
                }



            }

        }
        else
        {
            return redirect()->route('web.get.login');
        }

    }
    public function loadData(Request $request)
    {
        if ($request->ajax())
        {
            if ($request->id > 0)
            {
               $data =  \DB::table('rating_restaurants')
                   ->where('id','<',$request->id)
                   ->where('restaurant_id','=',$request->resId)
                   ->orderBy('id','DESC')
                   ->limit(3)
                   ->get();
            }
            else
            {
                $data =  \DB::table('rating_restaurants')
                    ->where('restaurant_id','=',$request->resId)
                    ->orderBy('id','DESC')
                    ->limit(3)
                    ->get();
            }
            $output = '';
            $last_id = '';
            $rating='';
            if (!$data->isEmpty())
            {
                foreach ($data as $value)
                {
                   if ($value->rating == 1)
                   {
                       $rating .= ' <i class="fa fa-star"></i>';
                   }
                   elseif ($value->rating == 2)
                   {
                       $rating .= ' <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
                   }
                   elseif ($value->rating == 3)
                   {
                       $rating = ' <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
                   }
                   elseif ($value->rating == 4)
                   {
                       $rating .= ' <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
                   }
                   elseif ($value->rating == 5)
                   {
                       $rating .= ' <i class="fa fa-star"></i> <i class="fa fa-star"></i>  <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
                   }
                   else
                   {
                       $rating .= ' <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
                   }
                    $output .= '<div class="col-md-12"> <div class="comments_users"> <div class="name_rate mb-3"> '.$rating.' <strong>'.User::find($value->user_id)->firstNAme.' '.User::find($value->user_id)->firstNAme.'</strong> </div> <p>'.$value->comment.'</p> </div> </div>';
                    $last_id = $value->id;
                }
                $output .= '<div class="col-md-12"> <button type="button" name="load_more_button" data-id="'.$last_id.'" id="load_more_button" class="btn_more_comments btn-block"> '.trans('web.Read More Comments').' </button> </div>';
            }
            else
            {
                $output .= '<div id="post_data" class="row mt-5"> <div class="col-md-12"> <button type="button" name="load_more_button" class="btn_more_comments btn-block"> '.trans('web.Not Data Found').' </button> </div>  </div>';
            }

            echo $output;
        }
    }
    public function loadData2(Request $request)
    {
        if ($request->ajax())
        {
            if ($request->id > 0)
            {
                $data =  \DB::table('rating_restaurants')
                    ->where('id','<',$request->id)
                    ->where('restaurant_id','=',$request->resId)
                    ->orderBy('id','DESC')
                    ->limit(3)
                    ->get();
            }
            else
            {
                $data =  \DB::table('rating_restaurants')
                    ->where('restaurant_id','=',$request->resId)
                    ->orderBy('id','DESC')
                    ->limit(3)
                    ->get();
            }
            $output = '';
            $last_id = '';
            $rating='';
            if (!$data->isEmpty())
            {
                foreach ($data as $value)
                {
                    if ($value->rating == 1)
                    {
                        $rating .= ' <i class="fa fa-star"></i>';
                    }
                    elseif ($value->rating == 2)
                    {
                        $rating .= ' <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
                    }
                    elseif ($value->rating == 3)
                    {
                        $rating = ' <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
                    }
                    elseif ($value->rating == 4)
                    {
                        $rating .= ' <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
                    }
                    elseif ($value->rating == 5)
                    {
                        $rating .= ' <i class="fa fa-star"></i> <i class="fa fa-star"></i>  <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>';
                    }
                    else
                    {
                        $rating .= ' <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
                    }
                    $output .= '<div class="item_comment"><div class="head_comment clearfix"><div class="rating pull-left">'.$rating. '<span class="ml-4">'.User::find($value->user_id)->firstName.' '.User::find($value->user_id)->lastName.'</span>
</div> <div class="date_comments pull-right"><p>'.date('d-m-Y',strtotime($value->created_at)).'</p></div></div><div class="comments_user pl-3"><p>'.$value->comment.'</p></div></div>';
                    $last_id = $value->id;
                }
                $output .= '<div class="col-md-12"> <button type="button" name="load_more_button" data-id="'.$last_id.'" id="load_more_button" class="btn_more_comments btn-block"> '.trans('web.Read More Comments').' </button> </div><br/>';
            }
            else
            {
                $output .= ' <div  id="post_data" class="card_body"> <div class="col-md-12"> <button type="button" name="load_more_button" class="btn_more_comments btn-block"> '.trans('web.Not Data Found').' </button> </div>  <br/></div>';
            }

            echo $output;
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('term');

        $result = Restaurant::with('restaurantTranslations')
            ->whereHas('restaurantTranslations',function ($q) use ($search){
                $q->where('name', 'LIKE', '%'. $search. '%');
            })->get();


        return response()->json($result);
    }

    public function removeCart(Request $request)
    {
       if ($request->ajax())
       {
           if (\request()->has('removeCart'))
           {

               $cart = Cart::find($request->removeCart);
               if (($cart) == null)
               {

                   return redirect()->back();
               }
               else
               {

                   $cart->delete();
               }
               $all = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();


               $Subtotal = 0;
               $wel  ='';
               $head = '<ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i>';
               foreach($all as $key =>$value){
                   $Subtotal  += percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price)  * $value->qty;
                   $wel = $wel.'<li>   <img src="'. \App\MenuDetails::find($value->menu_details_id)->imagePath .'" class="img-fluid pull-left" alt=""> 
                   <div class="info_course_c pull-left"> <h6>'. MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name .'}</h6>
                   <div class="qty-wrap"> <input class="qty" type="text"  value="'. $value->qty .'">  </div>
                   <strong class="price">'.trans('web.S.R'). percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price ).'</strong>
                   </div>
                   <i class="fa fa-times removeCart" data-name="removeCart" data-id="'. $value->id .'"></i>
                   </li>
                   ' ;

               }
               $footer = '<li>  <strong>'.trans('web.Total : S.R') .sumCart(\Auth::guard('web')->user()->id).' </strong> <a href="'.route('web.get.checkout').'" class="btn_chk_cart">'.trans('web.Go To Cart').'</a> </li> </ul>';

               $found = '';
               if (count($all) >0)
               {
                   $found = $head.$wel.$footer;
               }
               else
               {
                   $found2 = ' <ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i> <span class="name_owner text-center"> '.trans('web.Cart is Empty').'</span> </ul>';
               }



               return [
                   'cart'=>$found,
                   'myCart' =>$found2,
               ];

           }

          /* if (\request()->has('menu_details_id'))
           {
               $cart = Cart::where('menu_details_id',$request->menu_details_id)->first();
               $value = $cart->qty;

               if ($value < $request->qty)
               {
                   $plus = $value + $request->qty;

                   $success = \DB::table('carts')
                       ->where('menu_details_id','=',$request->menu_details_id)
                       ->update(['qty'=>$plus]);
                   if ($success)
                   {
                       $all = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();


                       $Subtotal = 0;
                       $wel  ='';
                       $head = '<ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i>';
                       foreach($all as $key =>$value){
                           $Subtotal  += percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price)  * $value->qty;
                           $wel = $wel.'<li>   <img src="'. \App\MenuDetails::find($value->menu_details_id)->imagePath .'" class="img-fluid pull-left" alt=""> 
                   <div class="info_course_c pull-left"> <h6>'. MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name .'}</h6>
                   <div class="qty-wrap"> <input class="qty" type="text"  value="'. $value->qty .'">  </div>
                   <strong class="price">'.trans('web.S.R'). percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price ).'</strong>
                   </div>
                   <i class="fa fa-times removeCart" data-name="removeCart" data-id="'. $value->id .'"></i>
                   </li>
                   ' ;

                       }
                       $footer = '<li>  <strong>'.trans('web.Total : S.R') .sumCart(\Auth::guard('web')->user()->id).' </strong> <a href="'.route('web.get.checkout').'" class="btn_chk_cart">'.trans('web.Go To Cart').'</a> </li> </ul>';

                       $found = '';
                       if (count($all) >0)
                       {
                           $found = $head.$wel.$footer;
                       }
                       else
                       {
                           $found2 = ' <ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i> <span class="name_owner text-center"> '.trans('web.Cart is Empty').'</span> </ul>';
                       }

                       return [
                           'cart'=>$found,
                           'myCart' =>$found2,
                       ];
                   }
               }
               else
               {
                   $minus = $value - $request->qty;
                   $success = \DB::table('carts')
                       ->where('menu_details_id','=',$request->menu_details_id)
                       ->update(['qty'=>$minus]);
                   if ($success)
                   {
                       $all = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();


                       $Subtotal = 0;
                       $wel  ='';
                       $head = '<ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i>';
                       foreach($all as $key =>$value){
                           $Subtotal  += percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price)  * $value->qty;
                           $wel = $wel.'<li>   <img src="'. \App\MenuDetails::find($value->menu_details_id)->imagePath .'" class="img-fluid pull-left" alt=""> 
                   <div class="info_course_c pull-left"> <h6>'. MenuDetails::find($value->menu_details_id)->translate(\App::getLocale())->name .'}</h6>
                   <div class="qty-wrap"> <input class="qty" type="text"  value="'. $value->qty .'">  </div>
                   <strong class="price">'.trans('web.S.R'). percentageOrder(\App\MenuDetails::find($value->menu_details_id)->price ).'</strong>
                   </div>
                   <i class="fa fa-times removeCart" data-name="removeCart" data-id="'. $value->id .'"></i>
                   </li>
                   ' ;

                       }
                       $footer = '<li>  <strong>'.trans('web.Total : S.R') .sumCart(\Auth::guard('web')->user()->id).' </strong> <a href="'.route('web.get.checkout').'" class="btn_chk_cart">'.trans('web.Go To Cart').'</a> </li> </ul>';

                       $found = '';
                       if (count($all) >0)
                       {
                           $found = $head.$wel.$footer;
                       }
                       else
                       {
                           $found2 = ' <ul class="cart_down"> <i id="close_cart" class="fa fa-times"></i> <span class="name_owner text-center"> '.trans('web.Cart is Empty').'</span> </ul>';
                       }

                       return [
                           'cart'=>$found,
                           'myCart' =>$found2,
                       ];
                   }
               }
           }*/
       }


    }


}
