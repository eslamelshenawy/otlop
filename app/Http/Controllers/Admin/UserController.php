<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AdminDataTable;
use App\Admin;
//use App\ManageShift;
use App\DataTables\TransactionDataTable;
use App\DataTables\UserDataTable;
use App\DataTables\OrganizationAccountingTable;
use App\DataTables\WalletDeliveryDataTable;
use App\DataTables\WalletUserDataTable;
use App\StatementTransaction;
use App\WalletDelivery;
use App\Order;
use App\Restaurant;
use App\PermissonExchange;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{



    public function index(UserDataTable $userDataTable)
    {
        
        try{

            return $userDataTable->render('backend.users.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function walletUsers(WalletUserDataTable $userDataTable)
    {
        try{

            return $userDataTable->render('backend.users-wallet.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function walletDelivery(WalletDeliveryDataTable $walletDeliveryDataTable)
    {
        try{

            return $walletDeliveryDataTable->render('backend.users.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function create()
    {
        try{
            return view('backend.admin.admin.create');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function store(Request $request)
    {
        try{

            $data = checkEmail('users',$request->email);

            if (!empty($data))
            {
                session()->flash('warning',trans('admin.This email is used'));
            }
            if ($request->userType == 'admin')
            {
                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins',
                    'password'=>'required|min:1',
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'userType'=>'required|in:admin,vendor,delivery',
                    'permission'=>'required|min:1',
                    'address'=>'required|string',
                    'image' => 'required|'.validateImage(),
                ];
                $message = [
                    'firstName.required' => trans('admin.First name is required'),
                    'lastName.required' => trans('admin.Last name is required'),
                    'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                    'password.required' => trans('admin.Password is required must be 6 characters'),
                    'Phone.required' => trans('admin.Phone is required'),
                    'status.required' => trans('admin.Status is required'),
                    'userType.required' => trans('admin.User type is required'),
                    'address.required' => trans('admin.Address is required'),
                    'permission.required' => trans('admin.Permission is required and select min one'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];
            }
            else
            {
                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins',
                    'password'=>'required|min:1',
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'userType'=>'required|in:admin,vendor,delivery',
                    'address'=>'required|string',
                    'image' => 'required|'.validateImage(),
                ];
                $message = [
                    'firstName.required' => trans('admin.First name is required'),
                    'lastName.required' => trans('admin.Last name is required'),
                    'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                    'password.required' => trans('admin.Password is required must be 6 characters'),
                    'Phone.required' => trans('admin.Phone is required'),
                    'status.required' => trans('admin.Status is required'),
                    'userType.required' => trans('admin.User type is required'),
                    'address.required' => trans('admin.Address is required'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];
            }
            $validate = Validator::make($request->all(),$rules,$message);
            if ($validate->fails())
            {
                return redirect()->back()->withErrors($validate)->withInput();
            }
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'admin/','');
            }
            $admin = new Admin();
            $admin->firstName = $request->firstName;
            $admin->lastName = $request->lastName;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->phone = $request->phone;
            $admin->address = $request->address;
            $admin->userType = $request->userType;
            $admin->status = $request->status;
            $admin->user_token = Str::random(60);
            $admin->image = $filename;
            if ($request->userType == 'admin')
            {
                $admin->parent_id = \Auth::guard('admin')->user()->id;
            }
            $admin->created_by = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $admin->save();
            if ($admin->save())
            {
                if ($admin->userType == 'admin')
                {
                    $admin->attachRole('admin');
                    $admin->syncPermissions($request->get('permission'));
                    session()->flash('success',trans('admin.Data has been added successfully'));
                    return redirect()->route('admin.admin.index');
                }
                elseif($admin->userType == 'vendor')
                {
                    $admin->attachRole('vendor');
                }
                elseif($admin->userType == 'delivery')
                {
                    WalletDelivery::create([
                        'delivery_id' => $admin->id,
                        'date' => Carbon::now(),
                    ]);
                }
                session()->flash('success',trans('admin.Data has been added successfully'));
                return redirect()->route('admin.admin.index');
            }




        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function show($id)
    {
        try{


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function edit($id)
    {
        try{
            $admin = Admin::find($id);
            checkData($admin);
            return view('backend.admin.admin.edit',compact('admin'));


        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function update(Request $request, $id)
    {
        try{

            $data = checkEmail('users',$request->email);

            if (!empty($data))
            {
                session()->flash('warning',trans('admin.This email is used'));
            }

            $admin = Admin::find($request->id);
            checkData($admin);

            if ($admin->userType == 'admin')
            {
                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins,email,'.$request->id,
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'permission'=>'required|min:1',
                    'address'=>'required|string',
                    'image' => validateImage(),
                ];
                $message = [
                    'firstName.required' => trans('admin.First name is required'),
                    'lastName.required' => trans('admin.Last name is required'),
                    'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                    'password.required' => trans('admin.Password is required must be 6 characters'),
                    'Phone.required' => trans('admin.Phone is required'),
                    'status.required' => trans('admin.Status is required'),
                    'address.required' => trans('admin.Address is required'),
                    'permission.required' => trans('admin.Permission is required and select min one'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];
            }
            else
            {
                $rules = [
                    'firstName'=>'required|string|min:3',
                    'lastName'=>'required|string|min:3',
                    'email'=>'required|string|email|unique:admins,email,'.$request->id,
                    'password'=>'required|min:1',
                    'phone'=>'required|numeric',
                    'status'=>'required|in:1,0',
                    'address'=>'required|string',
                    'image' => validateImage(),
                ];
                $message = [
                    'firstName.required' => trans('admin.First name is required'),
                    'lastName.required' => trans('admin.Last name is required'),
                    'email.required' => trans('admin.E-mail is required and must be is E-mail'),
                    'Phone.required' => trans('admin.Phone is required'),
                    'status.required' => trans('admin.Status is required'),
                    'address.required' => trans('admin.Address is required'),
                    'image.required' => trans('admin.Image is required must be jpg,jpeg,png,bmp'),
                ];
            }
            $validate = Validator::make($request->all(),$rules,$message);
            $filename = '';
            if ($validate->fails())
            {
                return redirect()->back()->withErrors($validate)->withInput();
            }
            if ($request->file('image'))
            {
                $filename = uploadImages($request->image,'admin/',$admin->image);
            }
            else
            {
                $filename = $admin->image;
            }
            $admin->firstName = $request->firstName;
            $admin->lastName = $request->lastName;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->address = $request->address;
            $admin->status = $request->status;
            $admin->user_token = Str::random(60);
            $admin->image = $filename;
            $admin->updated_by = getDataAdmin(\Auth::guard('admin')->user()->id)->email;
            $admin->save();
            if ($admin->save())
            {
                if ($admin->userType == 'admin')
                {
                    $admin->syncPermissions($request->get('permission'));
                    session()->flash('success',trans('admin.Data has been added successfully'));
                    return redirect()->route('admin.admin.index');
                }
                session()->flash('success',trans('admin.Data has been added successfully'));
                return redirect()->route('admin.admin.index');
            }

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy($id)
    {
        try{
            $admin = Admin::find($id);
            removeImage('admin/'.$admin->image);
            Admin::find($id)->delete();
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.admin.index');

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
                    $data = Admin::find(\request('item')[$i]);
                    removeImage('admin/'.$data->image);
                }
                Admin::destroy(\request('item'));
            }
            else
            {
                $admin =  Admin::find(\request('item'));
                removeImage('admin/'.$admin->image);
                $admin->delete();
            }
            session()->flash('success',trans('admin.Data has been deleted successfully'));

            return redirect()->route('admin.admin.index');
        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }

    }
    #transaction
    public function transaction(TransactionDataTable $transactionDataTable)
    {
        try{

            return $transactionDataTable->render('backend.transaction.index');

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function organization_account(OrganizationAccountingTable $OrganizationAccountingTable)
    {

//        dd(deliveryFees()['amountAdmin']);
        try{
            return $OrganizationAccountingTable->render('backend.account.organization_accounting');

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function balance()
    {

        try{
            $order_balabces=Order::get();
            $restaurant=Restaurant::get();
            $delivery=Admin::where('userType','delivery')->get();
            $PermissonExchange=PermissonExchange::where('permisson_exchange.restaurant_id',"!=",null)->where("restaurant_translations.locale",\App::getLocale())
                ->leftjoin('restaurants', 'restaurants.id', '=', 'permisson_exchange.restaurant_id')
                ->leftjoin("restaurant_translations","restaurant_translations.restaurant_id","=","restaurants.id")
                ->select('restaurants.*',"permisson_exchange.*","restaurant_translations.name as name_restaurant","restaurant_translations.locale")
                ->paginate(10);

            $PermissonExchange_deliver=PermissonExchange::where('permisson_exchange.deliver_id',"!=",null)
                ->leftjoin('admins', 'admins.id', '=', 'permisson_exchange.deliver_id')
                ->select('admins.*',"permisson_exchange.*")
                ->paginate(10);

//            dd($PermissonExchange_deliver);
    $sum_order_total=0;
    $sum_restaurant=0;
    $sum_organize=0;
    $sum_delivery=0;
    $sum_PermissonExchange=0;
    $sum_PermissonExchange_deliver=0;
           foreach($PermissonExchange as $PermissonEx){
               $amout_PermissonExchange = $PermissonEx->amout;
               $sum_PermissonExchange+=$amout_PermissonExchange;

           }
           foreach($PermissonExchange_deliver as $PermissonEx_deliver){
               $amout_PermissonExchange_deliver = $PermissonEx_deliver->amout;
               $sum_PermissonExchange_deliver+=$amout_PermissonExchange_deliver;
           }
//           dd($sum_PermissonExchange_deliver);
            foreach($order_balabces as $key=> $order_balance) {

                $percent_number =(deliveryFees()['percentageOrder']*100 )+100;
                $amount = number_format($order_balance->amount);
                $amount_net = (number_format($order_balance->amount) *100)/number_format($percent_number);


                $data_order=$order_balance->dateTime;
                $amount_delivery = $order_balance->amount_delivery;
//                dd(deliveryFees($data_order)['amountMan']);
                  $amount_organize= ($amount_delivery - deliveryFees()['amountMan']) + (deliveryFees()['percentageOrder']*$amount_net);

                $amount_deliver=$amount_delivery - (deliveryFees()['amountAdmin']);

                $sum_delivery += $amount_deliver;

                $sum_organize += $amount_organize;
      $sum_restaurant += $amount_net;

      $order_total=$amount + $amount_delivery;
      $sum_order_total += $order_total;

            }
//            dd($sum_PermissonExchange);

//dd($sum_order_total);
            $order_balabces_cash=Order::where('payment_type','cash')->get();

//            dd($order_balabces_cash);
            $sum_order_total_cash=0;
            $sum_restaurant_cash=0;
            $sum_organize_cash=0;
            $sum_delivery_cash=0;

            foreach($order_balabces_cash as $order_balabce_cash) {


                $percent_number =(deliveryFees()['percentageOrder']*100 )+100;
                $amount_cash = number_format($order_balabce_cash->amount);

                $amount_net_cash = (number_format($order_balabce_cash->amount) *100)/number_format($percent_number);

                $data_order_cash=$order_balabce_cash->dateTime;
                $amount_delivery_cash = $order_balabce_cash->amount_delivery;
                $amount_organize_cash= ($amount_delivery - deliveryFees()['amountMan']) + (deliveryFees()['percentageOrder']*$amount_net);

                $amount_deliver_cash=$amount_delivery_cash - (deliveryFees()['amountAdmin']);

                $sum_delivery_cash += $amount_deliver_cash;

                $sum_organize_cash += $amount_organize_cash;
                $sum_restaurant_cash += $amount_net_cash;

                $order_total_cash=$amount_cash + $amount_delivery_cash;
                $sum_order_total_cash += $order_total_cash;

            }

    $order_balabces_card=Order::where('payment_type','card')->get();

//            dd($order_balabces_card);
            $sum_order_total_card=0;
            $sum_restaurant_card=0;
            $sum_organize_card=0;
            $sum_delivery_card=0;

            foreach($order_balabces_card as $order_balabce_card) {

                $percent_number =(deliveryFees()['percentageOrder']*100 )+100;
                $amount_card = number_format($order_balabce_card->amount);

                $amount_net_card = (number_format($order_balabce_card->amount) *100)/number_format($percent_number);


                $data_order_card=$order_balabce_card->dateTime;
                $amount_delivery_card = $order_balabce_card->amount_delivery;
                $amount_organize_card= ($amount_delivery_card -  deliveryFees()['amountMan']) + (deliveryFees()['percentageOrder']*$amount_net);

                $amount_deliver_card=$amount_delivery_card - (deliveryFees()['amountAdmin']);

                $sum_delivery_card += $amount_deliver_card;

                $sum_organize_card += $amount_organize_card;
                $sum_restaurant_card += $amount_net_card;

                $order_total_card=$amount_card + $amount_delivery_card;
                $sum_order_total_card += $order_total_card;

            }

            $order_balabces_wallet=Order::where('payment_type','wallet')->get();

//            dd($order_balabces_card);
            $sum_order_total_wallet=0;
            $sum_restaurant_wallet=0;
            $sum_organize_wallet=0;
            $sum_delivery_wallet=0;

            foreach($order_balabces_wallet as $order_balabce_wallet) {

                $percent_number =(deliveryFees()['percentageOrder']*100 )+100;
                $amount_wallet = number_format($order_balabce_wallet->amount);

                $amount_net_wallet = (number_format($order_balabce_wallet->amount) *100)/number_format($percent_number);



                $data_order_wallet=$order_balabce_wallet->dateTime;
                $amount_delivery_wallet = $order_balabce_wallet->amount_delivery;
                $amount_organize_wallet= ($amount_delivery_wallet -  deliveryFees()['amountMan']) + (deliveryFees()['percentageOrder']*$amount_net);

                $amount_deliver_wallet=$amount_delivery_wallet - (deliveryFees()['amountAdmin']);

                $sum_delivery_wallet += $amount_deliver_wallet;

                $sum_organize_wallet += $amount_organize_wallet;
//                dd($amount_net_wallet);
                $sum_restaurant_wallet += $amount_net_wallet;

                $order_total_wallet=$amount_wallet + $amount_delivery_wallet;
                $sum_order_total_wallet += $order_total_wallet;

            }

            return view('backend.account.balance',compact('order_balabces','sum_PermissonExchange_deliver','sum_PermissonExchange','PermissonExchange_deliver','PermissonExchange','delivery','restaurant','order_balabces_cash','order_balabces_wallet','order_balabces_card',
                'sum_restaurant','sum_restaurant_cash','sum_restaurant_card','sum_restaurant_wallet',
                'sum_organize','sum_organize_cash','sum_organize_card','sum_organize_wallet','sum_delivery','sum_delivery_cash','sum_delivery_card','sum_delivery_wallet','sum_order_total','sum_order_total_wallet','sum_order_total_card','sum_order_total_cash'));

        }catch (\Exception$exception )
        {
            return redirect()->back()->with('error',$exception->getMessage())->withInput();
        }

    }


}
