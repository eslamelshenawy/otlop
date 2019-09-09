<?php



#setting
use App\ManageShift;
use App\RatingRestaurant;
use App\User;
use App\UserWalletDetails;
use Carbon\Carbon;

if (!function_exists('setting'))
{
    function setting()
    {
        return \App\Setting::orderBy('id','desc')->first();
    }

}

#setting
if (!function_exists('seo'))
{
    function seo()
    {
        return \App\Seo::orderBy('id','desc')->first();
    }

}

#setting
if (!function_exists('information'))
{
    function information()
    {
        return \App\ContactInformation::orderBy('id','desc')->first();
    }

}

#seo
if (!function_exists('seo'))
{
    function seo()
    {
        return \App\Seo::orderBy('id','desc')->first();
    }

}
#contactInformation
if (!function_exists('contactInformation'))
{
    function contactInformation()
    {
        return \App\contactInformation::orderBy('id','desc')->first();
    }

}
#socialMedia
if (!function_exists('socialMedia'))
{
    function socialMedia()
    {
        return \App\SocialMedia::orderBy('id','desc')->get();
    }

}

#setting
if (!function_exists('setting'))
{
    # admin url
    function setting()
    {
        return \App\Setting::orderBy('id','desc')->first();
    }

}

#route admin

if (!function_exists('routeAdmin'))
{
    function routeAdmin($route = '')
    {
        if ($route == '')
        {
            return route('admin.home');
        }
        else
        {
            return route('admin.'.$route);
        }
    }

}

if (!function_exists('res'))
{
    function res($route = '')
    {
        $vendorId = Auth::guard('admin')->user()->id;
    }

}
# because to load abatch or source code

#------------------Delete Image-----------

if (!function_exists('DeleteImage'))
{
    function DeleteImage($DeleteFileWithName)
    {
        if(file_exists($DeleteFileWithName))
        {
            \File::delete($DeleteFileWithName);
        }
    }

}

#upload image
if (!function_exists('uploadImages'))
{
    function uploadImages($request,$path,$deleteFileWithName = '')
    {

        if($deleteFileWithName != '')
        {
            #Delete Image
            DeleteImage(public_path('upload/'.$path.$deleteFileWithName));
        }
        \Intervention\Image\Facades\Image::make($request)->save(public_path('upload/'.$path.$request->hashName()));
        ;
        if($deleteFileWithName != '')
        {
            DeleteImage(public_path('upload/'.$path.$deleteFileWithName));
        }

        return $request->hashName();
    }

}



#auth guard admin
if (!function_exists('admin'))
{
    function admin()
    {
        return auth()->guard('admin');
    }

}
#acctive side bar
if (!function_exists('activeMenu'))
{
    function activeMenu($link)
    {
        if (preg_match('/'.$link.'/i',Request::segment(2))){

            return ['menu-open','display:block'];
        }
        elseif (preg_match('/'.$link.'/i',Request::segment(2))){

        }
        else
        {
            return ['',''];
        }
    }

}


# dataTable Language

if (!function_exists('dataTaleLang'))
{
    function dataTaleLang()
    {
        return
            [
                'sProcessing'=>trans('admin.sProcessing'),
                'sLengthMenu'=>trans('admin.sLengthMenu'),
                'sZeroRecords'=>trans('admin.sZeroRecords'),
                'sEmptyTable'=>trans('admin.sEmptyTable'),
                'sInfo'=>trans('admin.sInfo'),
                'sInfoEmpty'=>trans('admin.sInfoEmpty'),
                'sInfoFiltered'=>trans('admin.sInfoFiltered'),
                'sInfoPostFix'=>trans('admin.sInfoPostFix'),
                'sSearch'=>trans('admin.sSearch'),
                'sUrl'=>trans('admin.sUrl'),
                'sInfoThousands'=>trans('admin.sInfoThousands'),
                'sLoadingRecords'=>trans('admin.sLoadingRecords'),
                'oPaginate'=>[
                    'sFirst'=>trans('admin.sFirst'),
                    'sLast'=>trans('admin.sLast'),
                    'sNext'=>trans('admin.sNext'),
                    'sPrevious'=>trans('admin.sPrevious'),
                ],

                'oAria'=>[
                    'sSortAscending'=>trans('admin.sSortAscending'),
                    'sSortDescending'=>trans('admin.sSortDescending'),
                ],
            ];
    }

}

#validate helper function

if (!function_exists('validateImage'))
{
    function validateImage($ext = null)
    {
        if ($ext == null)
        {
            return 'image|mimes:jpg,jpeg,png,bmp';
        }
        else
        {
            return 'image|mimes:'.$ext;
        }
    }

}


if (!function_exists('removeImage'))
{
    function removeImage($path)
    {
        File::delete('upload/'.$path);
    }

}

if (!function_exists('checkData'))
{
    function checkData($data)
    {
        if (empty($data))
        {
            session()->flash('warning',trans('admin.Please refresh this page please'));
            return redirect()->back()->withInput();
        }
    }

}

if (!function_exists('checkEmail'))
{
    function checkEmail($table,$request)
    {
        $data = DB::table($table)->where('email','=',$request)->first();
        return $data;
    }

}

if (!function_exists('cdataEmpty'))
{
    function cdataEmpty($table,$col,$request)
    {
        $data = DB::table($table)->where($col,'=',$request)->first();
        return $data;
    }

}


if (!function_exists('getAge'))
{
    function getAge($birth_day)
    {
        list($year, $month, $day) = explode("-", $birth_day);

        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
        if($month_diff < 0)
        {
            $year_diff --;
        }
        else if(($month_diff == 0) && ($day_diff < 0))
        {
            $year_diff --;
        }
        return $year_diff;

    }

}
#get data auth admin
if (!function_exists('getDataAdmin'))
{
    function getDataAdmin($auth)
    {
        $data = \App\Admin::find($auth);
        if (empty($data))
        {
            Auth::guard('admin')->logout();
        }
        else
        {
            return $data;
        }
    }

}

#get data auth vendor and know res
if (!function_exists('getDataRestaurant'))
{
    function getDataRestaurant($auth)
    {

        $data = \App\Admin::find($auth);

        if ($data->parent_id == null)
        {
            $restaurant = \App\Restaurant::where('admin_id',$auth)->first();
            return $restaurant;
        }
        else
        {
            $restaurant = \App\Restaurant::where('admin_id',$data->parent_id)->first();

            return $restaurant;
        }
    }

}

#get data auth vendor and WHEN AUTH user vendor
if (!function_exists('getDataVendor'))
{
    function getDataVendor($auth)
    {

        $data = \App\Admin::find($auth);
        if ($data->parent_id == null)
        {
            $vendor = \App\Admin::where('id',$auth)->first();
            return $vendor->id;

        }
        else
        {
            $vendor = \App\Admin::find($auth);
            return $vendor->parent_id;
        }
    }

}

if (!function_exists('getRestaurant'))
{
    function getRestaurant($auth)
    {
        $data = \App\Restaurant::where('admin_id',$auth)->first();
        if (empty($data))
        {
            Auth::guard('admin')->logout();
        }
        else
        {
            return $data;
        }
    }

}

if (!function_exists('getUrl'))
{
    function getUrl($routeName )
    {
        if (Request::route()->getName() == $routeName)
        {
            return 'active';
        }
        else
        {
            return '';
        }

    }

}

if (!function_exists('getRoute'))
{
    function getRoute($routeName )
    {
        if (Request::route()->getName() == $routeName)
        {
            return  true;
        }
        else
        {
            return false;
        }

    }

}

#frontent

if (!function_exists('getDelivery'))
{
    function getDelivery()
    {
        $delivery = \App\ManageShift::find(1);
        $time =  \Carbon\Carbon::now();
        dd(date('h:m:A',strtotime($time)));

    }

}

if (!function_exists('deliveryFees'))
{
    function deliveryFees()
    {

        $data =  $amountDelivery = ManageShift::find(1);
        $current_time = date('H:i a',strtotime(Carbon::now()));
        $sunrise = date('h:m A',strtotime($data->fromTime));//"5:00 am";
        $sunset = date('h:m A',strtotime($data->toTime));//"12:00 am";

        $date1 = \DateTime::createFromFormat('H:i a', $current_time);
        $date2 = \DateTime::createFromFormat('H:i a', $sunrise);
        $date3 = \DateTime::createFromFormat('H:i a', $sunset);

        $percentageOrder = ManageShift::find(1)->percentageOrder;
        if ($date1 < $date2 && $date1 < $date3)
        {
            $amountDelivery = ManageShift::find(1)->total_price_night;
            $amountAdmin = ManageShift::find(1)->organization_visa_night;
            $amountMan = ManageShift::find(1)->delivery_visa_night;
            $typeShift = 'night';
        }
        else
        {
            $amountDelivery = ManageShift::find(1)->total_price_morning;
            $amountAdmin = ManageShift::find(1)->organization_visa_morning;
            $amountMan = ManageShift::find(1)->delivery_visa_morning;
            $typeShift = 'morning';
        }
        return [
            'amountDelivery' => $amountDelivery,
            'typeShift' => $typeShift,
            'amountAdmin' =>$amountAdmin,
            'amountMan' => $amountMan,
            'percentageOrder' => $percentageOrder,
        ];

    }

}
if (!function_exists('deliverytime'))
{
    function deliverytime($data_order)
    {

        $data =  $amountDelivery = ManageShift::find(1);
        $current_time = date('a',strtotime($data_order));
        $sunrise = date('h:m A',strtotime($data->fromTime));//"5:00 am";
        $sunset = date('h:m A',strtotime($data->toTime));//"12:00 am";

        $date1 = \DateTime::createFromFormat('H:i a', $current_time);
        $date2 = \DateTime::createFromFormat('H:i a', $sunrise);
        $date3 = \DateTime::createFromFormat('H:i a', $sunset);
        $percentageOrder = ManageShift::find(1)->percentageOrder;
        if ($current_time == "pm")
        {
            $amountDelivery = ManageShift::find(1)->total_price_night;
            $amountAdmin = ManageShift::find(1)->organization_visa_night;
            $amountMan = ManageShift::find(1)->delivery_visa_night;
            $typeShift = 'night';
        }
        else
        {

            $amountDelivery = ManageShift::find(1)->total_price_morning;
            $amountAdmin = ManageShift::find(1)->organization_visa_morning;
            $amountMan = ManageShift::find(1)->delivery_visa_morning;
            $typeShift = 'morning';
        }
        return [
            'amountDelivery' => $amountDelivery,
            'typeShift' => $typeShift,
            'amountAdmin' =>$amountAdmin,
            'amountMan' => $amountMan,
            'percentageOrder' => $percentageOrder,
        ];

    }

}

if (!function_exists('percentageOrder'))
{
    function percentageOrder($priceOrder)
    {

        $data =  $amountDelivery = ManageShift::find(1);
        $operation = ( $priceOrder  ) * $data->percentageOrder ;
        $total = $operation + $priceOrder;

        return $total;
    }

}



if (!function_exists('ratingRestaurant'))
{
    function ratingRestaurant($restaurantId)
    {
        $rating = RatingRestaurant::where('restaurant_id',$restaurantId)->get();
        if ($rating->isEmpty())
        {
            $data = 0 ;
        }
        else
        {
            $total = $rating->sum('rating') / count($rating);

            $data = number_format((int)$total, 0, '.', '');
        }

        return $data;

    }

}

if (!function_exists('myCart'))
{
    function myCart($userId)
    {
        $cart = \App\Cart::where('user_id',$userId)->get();

        return $cart;
    }


}

if (!function_exists('sumCart'))
{
    function sumCart($userId)
    {
        $cart = \App\Cart::where('user_id',$userId)->get();

        $sum = 0 ;
        for ($i =0; $i<count($cart); $i++)
        {
            $sum += \App\MenuDetails::find($cart[$i]->menu_details_id)->price *$cart[$i]->qty ;
        }
        return percentageOrder($sum);
    }


}

if (!function_exists('CurrentUrl'))
{
    function CurrentUrl()
    {
        session()->put('CurrentUrl',\Request::url());
    }


}

if (!function_exists('statementTransaction'))
{
    function statementTransaction($amount,$from,$to,$fromType,$toType,$status,$orderId,$paymentMethod)
    {
        \App\StatementTransaction::create([
            'from_id' => $from,
            'to_id' => $to,
            'from_user_type' => $fromType,
            'to_user_type' => $toType,
            'amount' => $amount,
            'due_date' => Carbon::now(),
            'status' => $status,
            'order_id' => $orderId,
            'payment_method' =>$paymentMethod
            //'note' =>$note,
        ]);
    }


}

if (!function_exists('checkTheSameRestaurant'))
{
    function checkTheSameRestaurant($userAuth,$restaurantID)
    {
        $cart = \App\Cart::where('user_id',$userAuth->id)->first();
        if (empty($cart))
        {
            return true;
        }
        else
        {
            if ($cart->restaurant_id == $restaurantID)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }


}


if (!function_exists('sendNotifyShare'))
{
    function sendNotifyShare($request)
    {
        $user = User::where('phone',$request)->first();
        return $user;
    }
}

if (!function_exists('saveSendShare'))
{
    function saveSendShare($sendBy,$money,$receiveBy,$restaurant_id)
    {
        \App\SendShare::create([
            'send_by' =>$sendBy,
            'receive_by' =>$receiveBy,
            'money' =>$money,
            'restaurant_id' =>$restaurant_id,
        ]);



    }
}

if (!function_exists('checkWallet'))
{
    function checkWallet($user,$mount)
    {
        $wallet = \App\Wallet::where('user_id',$user)->first();
        if (number_format($wallet->account) >= number_format($mount))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

if (!function_exists('calWallet'))
{
    function calWallet($user,$mount)
    {
        $before = \App\Wallet::where('user_id',$user)->first();
        $data =  \DB::table('wallets')->where('user_id','=',$user)
            ->update(['account'=>$before->account - $mount]);
        if ($data)
        {
            $UserWalletDetails = new UserWalletDetails();
            $UserWalletDetails->user_id = $user;
            $UserWalletDetails->balance = $before->account;
            $UserWalletDetails->previous = $mount;
            $UserWalletDetails->date = Carbon::now();
            $UserWalletDetails->save();
            if ($UserWalletDetails->save())
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }
}


if (!function_exists('createTrip'))
{
    function createTrip($orderId)
    {
        $data = \App\Trip::create([
            'order_id' =>$orderId,
            'trips_status_id'=>1,
            'trips_driver_status'=>1,
        ]);
        if ($data)
        {
            return true;
        }
        return false;
    }
}








