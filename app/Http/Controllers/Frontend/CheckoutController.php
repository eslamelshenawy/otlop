<?php

namespace App\Http\Controllers\Frontend;

use App\AddressOrder;
use App\Admin;
use App\Cart;
use App\MenuDetails;
use App\Order;
use App\OrderDetails;
use App\UserWalletDetails;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Paytabs;
use Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function getCheckout()
    {
        $cart = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();

        $total = 0;
        for ($i = 0; $i<count($cart); $i++)
        {
            $total+= $cart[$i]->qty *  \App\MenuDetails::find($cart[$i]->menu_details_id)->price;
        }

        return view('frontend.checkout.checkout',compact('cart','total'));
    }
    public function storeOrder(Request $request)
    {
        $messages = [
            'payment_type.required' =>trans('web.Please choose your payment method'),
        ];
        $validator = \Validator::make($request->all(), [
            'payment_type'=>'required|in:card,cash,wallet',

        ], $messages);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }
        $cart = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();
        if ($cart->isEmpty())
        {
            return redirect()->back()->with('warning',trans('web.Please choose a meal at least'));
        }
        else
        {
            if ($request->payment_type == 'wallet')
            {
                $wallet = Wallet::where('user_id',\Auth::guard('web')->user()->id)->first();

                if (empty($wallet))
                {
                    return redirect()->back()->with('warning',trans('web.please call support system'));
                }
                else
                {
                    $total = 0 ;
                    $ordinalTotal = 0;
                    $cart = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();

                    for ($i = 0; $i<count($cart); $i++)
                    {
                        $total += $cart[$i]->qty * percentageOrder(\DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price')) ;
                        $ordinalTotal += $cart[$i]->qty * \DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price');
                    }
//                    dd($wallet->account >= $total);
                    if ($wallet->account >= $total )
                    {
                        $order = new Order();
                        $order->restaurant_id = $cart[0]->restaurant_id;
                        $order->user_id = \Auth::guard('web')->user()->id;
                        $order->amount = $total;
                        $order->dateTime = Carbon::now();
                        $order->amount_delivery = deliveryFees()['amountDelivery'];
                        $order->type_shift = deliveryFees()['typeShift'];
                        $order->payment_type = $request->payment_type;
                        $order->status = 'pending';
                        $order->save();
                        if ($order->save())
                        {
                            createTrip($order->id);
                            for ($i=0; $i<count($cart); $i++)
                            {
                                $orderDetails = new OrderDetails();
                                $orderDetails->order_id = $order->id;
                                $orderDetails->menu_details_id = $cart[$i]->menu_details_id;
                                $orderDetails->price = MenuDetails::find($cart[$i]->menu_details_id)->price;
                                $orderDetails->qty = $cart[$i]->qty;
                                $orderDetails->save();

                            }
                            $address = new AddressOrder();
                            $address->address = $request->adress;
                            $address->order_id = $order->id;
                            $address->user_id = \Auth::guard('web')->user()->id;
                            $address->lat = $request->lat;
                            $address->lng = $request->lng;
                            $address->save();
                        }
                        $success = Cart::where('user_id',\Auth::guard('web')->user()->id)->delete();
                        if ($success)
                        {
                            $successWallet = \DB::table('wallets')->where('user_id','=',\Auth::guard('web')->user()->id)
                                ->update(['account' => $wallet->account - $order->amount ]);
                            if ($successWallet)
                            {
                                $date = new UserWalletDetails();
                                $date->user_id = $order->user_id;
                                $date->balance = $wallet->account;
                                $date->previous = $order->amount;
                                $date->date = Carbon::now();
                                $date->save();
                                if ($date->save())
                                {
                                    $per = $total - $ordinalTotal;
                                    statementTransaction($ordinalTotal,$date->user_id,$order->restaurant_id,'user','vendor','paid',$order->id,'wallet');
                                    statementTransaction($per,$date->user_id,Admin::find(1)->id,'user','admin','paid',$order->id,'wallet');
                                    statementTransaction(deliveryFees()['amountAdmin'],$date->user_id,Admin::find(1)->id,'user','admin','paid',$order->id,'wallet');
                                    statementTransaction(deliveryFees()['amountMan'],$date->user_id,null,'user','delivery','paid',$order->id,'wallet');
                                    return $this->apiResponse(['orderId' => $order->id],'',200);
                                }
                                session()->put('profileOrder','active');

                                return redirect()->route('web.users.profile')->with('success',trans('web.Request successfully received pending message'));

                            }
                            else
                            {
                                return redirect()->back()->with('warning',trans('web.please try again'));

                            }
                        }
                        else
                        {
                            return redirect()->back()->with('warning',trans('web.try again'));
                        }
                    }
                    else
                    {
                        return redirect()->back()->with('warning',trans('web.Price order big account wallet total order = ') .$total.' '.trans('web.and wallet = ').$wallet->account);

//                        return redirect()->back()->with('warning',trans('web.Price order big account wallet total order = ') .$total.' '.trans('web.and wallet = ').$wallet->account);
                    }
                }
            }

            elseif ($request->payment_type == 'cash')
            {
                $total = $ordinalTotal = 0 ;

                $cart = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();
                if ($cart->isEmpty())
                {
                    session('warning',trans('web.We can\'t find a cart with that this users ').\Auth::guard('web')->user()->firstName.' '.\Auth::guard('web')->user()->lastName);
                    return redirect()->back();
                }
                else
                {
                    for ($i = 0; $i<count($cart); $i++)
                    {
                        $total += $cart[$i]->qty * percentageOrder(\DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price')) ;
                        $ordinalTotal += $cart[$i]->qty * \DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price');
                    }
                    $order = new Order();
                    $order->restaurant_id = $cart[0]->restaurant_id;
                    $order->user_id = \Auth::guard('web')->user()->id;
                    $order->amount = $total;
                    $order->dateTime = Carbon::now();
                    $order->amount_delivery = deliveryFees()['amountDelivery'];
                    $order->type_shift = deliveryFees()['typeShift'];
                    $order->payment_type = $request->payment_type;

                    $order->status = 'pending';
                    $order->save();
                    if ($order->save())
                    {
                        createTrip($order->id);
                        statementTransaction($total,\Auth::guard('web')->user()->id,null,'user','delivery','paid',$order->id,'cash');
                        for ($i=0; $i<count($cart); $i++)
                        {
                            $orderDetails = new OrderDetails();
                            $orderDetails->order_id = $order->id;
                            $orderDetails->menu_details_id = $cart[$i]->menu_details_id;
                            $orderDetails->price = MenuDetails::find($cart[$i]->menu_details_id)->price;
                            $orderDetails->qty = $cart[$i]->qty;
                            $orderDetails->save();

                        }
                        $address = new AddressOrder();
                        $address->address = $request->adress;
                        $address->order_id = $order->id;
                        $address->user_id = \Auth::guard('web')->user()->id;
                        $address->lat = $request->lat;
                        $address->lng = $request->lng;
                        $address->save();
                    }
                    $success = Cart::where('user_id',\Auth::guard('web')->user()->id)->delete();
                    if ($success)
                    {
                        session('success',trans('web.Done save order success'));
                        return redirect()->route('web.users.profile');
                    }
                    else
                    {
                        session('success',trans('web.Done save order success'));
                        return redirect()->route('web.please tyr again');
                    }
                }
            }
            elseif ($request->payment_type == 'card')
            {
                $total = $ordinalTotal = 0 ;

                $cart = Cart::where('user_id',\Auth::guard('web')->user()->id)->get();
                if ($cart->isEmpty())
                {
                    session('warning',trans('web.We can\'t find a cart with that this users ').\Auth::guard('web')->user()->firstName.' '.\Auth::guard('web')->user()->lastName);
                    return redirect()->back();
                }
                else
                {
                    for ($i = 0; $i<count($cart); $i++)
                    {
                        $total += $cart[$i]->qty * percentageOrder(\DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price')) ;
                        $ordinalTotal += $cart[$i]->qty * \DB::table('menu_details')->where('id','=',$cart[$i]->menu_details_id)->value('price');
                    }
                    $order = new Order();
                    $order->restaurant_id = $cart[0]->restaurant_id;
                    $order->user_id = \Auth::guard('web')->user()->id;
                    $order->amount = $total;
                    $order->dateTime = Carbon::now();
                    $order->amount_delivery = deliveryFees()['amountDelivery'];
                    $order->type_shift = deliveryFees()['typeShift'];
                    $order->payment_type = $request->payment_type;
                    $order->status = 'pending';

                    $order->save();
                    if ($order->save())
                    {
                        createTrip($order->id);
                        statementTransaction($total,\Auth::guard('web')->user()->id,null,'user','delivery','paid',$order->id,'card');
                        for ($i=0; $i<count($cart); $i++)
                        {
                            $orderDetails = new OrderDetails();
                            $orderDetails->order_id = $order->id;
                            $orderDetails->menu_details_id = $cart[$i]->menu_details_id;
                            $orderDetails->price = MenuDetails::find($cart[$i]->menu_details_id)->price;
                            $orderDetails->qty = $cart[$i]->qty;
                            $orderDetails->save();

                        }
                        $address = new AddressOrder();
                        $address->address = $request->adress;
                        $address->order_id = $order->id;
                        $address->user_id = \Auth::guard('web')->user()->id;
                        $address->lat = $request->lat;
                        $address->lng = $request->lng;
                        $address->save();
                    }
                    $success = Cart::where('user_id',\Auth::guard('web')->user()->id)->delete();
                    if ($success)
                    {
                        $user=Auth::guard('web')->user();
                        return redirect('payment/paytabs/request')->with('data', [$user,$order]);
//                        return redirect()->route('payment.paytabs.request',['user' =>$user]);

                    }
                    else
                    {
                        session('success',trans('web.Done save order success'));
                        return redirect()->route('web.please tyr again');
                    }
                }
            }
        }

    }



}
