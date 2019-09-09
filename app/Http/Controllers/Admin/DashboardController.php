<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Location;
use App\Order;
use App\User;
use App\Wallet;
use App\WalletDelivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard()
    {
        try{
            $users = User::orderBy('created_at','DESC')->get();

            $sumUsers = Wallet::all()->sum('account');

            $vendors = Admin::where('userType','vendor')->orderBy('created_at','DESC')->get();

            $delivery = Admin::where('userType','delivery')->orderBy('created_at','DESC')->get();

            $sumDelivery = WalletDelivery::all()->sum('account');

            $location = Location::orderBy('created_at','DESC')->get();

            $orders = Order::orderBy('created_at','DESC')->get();

            return view('backend.admin.dashboard.dashboard',
                compact('users','vendors','delivery','location','sumUsers','sumDelivery','orders'));

        }catch (\Exception$exception )
        {
            session()->flash('error',$exception->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
