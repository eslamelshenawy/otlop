<?php

namespace App\Http\Controllers\Api;

use App\AddressOrder;
use App\Admin;
use App\MenuDetails;
use App\Order;
use App\OrderDetails;
use App\Restaurant;
use App\StatementTransaction;
use App\Trip;
use App\TripDriverDispatches;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class DriverController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {

    }
    #login
    public function login(Request $request)
    {
        try{

            $validator = \Validator::make($request->all(),
                [
                    'email'=>'required|string|email|exists:admins,email',
                    'password'=>'required|min:1',
                    'remember_me'=>'sometimes|nullable|string|boolean',
                ]);
            if ($validator->fails()) {
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            else
            {
                $credentials = request(['email', 'password']);
                if(!\Auth::guard('admin')->attempt($credentials))
                {
                    return $this->apiResponse(null,'E-mail or Password is not correct',401);
                }
                else
                {
                    $user = $request->user('admin');
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    if ($request->remember_me)
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    $token->save();

                    return response()->json([
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                    ]);
                }
            }

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }


    }

    #activeOnMap
    public function activeOnMap(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'status'=>'required|in:on,off',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $delivery = Admin::find($request->user()->id);
        if (empty($delivery))
        {
            return $this->apiResponse('','this delivery not found in system',404);
        }
        else
        {
            if ($delivery->userType != 'delivery')
            {
                return $this->apiResponse('','this delivery not found in system',404);
            }
            else
            {
                $data = \DB::table('location_deliveries')
                    ->where('delivery_id','=',$delivery->id)
                    ->update(['active'=>$request->status]);
                if ($data)
                {
                    return $this->apiResponse('done change active on map','',200);
                }
                else
                {
                    return $this->apiResponse('','please try again',500);
                }
            }
        }
    }

    #updatedLocation
    public function updatedLocation(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'lat'=>'required',
                'lng'=>'required',
            ]);
        if ($validator->fails()) {
            return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $delivery = Admin::find($request->user()->id);
        if (empty($delivery))
        {
            return $this->apiResponse('','this delivery not found in system',404);
        }
        else
        {
            if ($delivery->userType != 'delivery')
            {
                return $this->apiResponse('','this delivery not found in system',404);
            }
            else
            {
                $data = \DB::table('location_deliveries')
                    ->where('delivery_id','=',$delivery->id)
                    ->update([
                        'lat'=>$request->lat,
                        'lng'=>$request->lng,
                    ]);
                if ($data)
                {
                    return $this->apiResponse('done change lat and lng','',200);
                }
                else
                {
                    return $this->apiResponse('','please try again',500);
                }
            }
        }
    }

    #getAllTrips
    public function getAllTrips(Request $request){
        try{

            $delivery = Admin::find($request->user()->id);
            if (empty($delivery))
            {
                return $this->apiResponse('','this delivery not found in system',404);
            }
            else
            {
                $trips = TripDriverDispatches::where('driver_id',$delivery->id)->where('request_sent_status',1)->first();
                if (empty($trips))
                {
                   return $this->apiResponse('','not get trips yet',404);
                }
                $details = [
                    'tripId' =>$trips->trip_id,
                    'driverId' =>$trips->driver_id,
                    'orderId' =>Trip::find($trips->trip_id)->order_id,
                ];
                return $this->apiResponse($details,'',200);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #startDispatching
    public function startDispatching()
    {
        $readyToDispatchTrips = Trip::where('trips_status_id','!=',5)->where('driver_id',null)
            ->doesntHave('drivers_in_dispatching')->get();
        foreach ($readyToDispatchTrips as $trip){
            $trip->trip_main_statuses()->attach(2);
            $trip->update(['trips_status_id'=>2]);
            $driver= Admin::join('location_deliveries','location_deliveries.delivery_id','=','admins.id')
            ->where('location_deliveries.active','on')
                ->where('admins.userType','delivery')
                ->select('admins.*')
                ->get();
            $this->assignDriverToTrip($trip,$driver);
        }
        return $readyToDispatchTrips;
    }

    #reStartDispatching
    public function reStartDispatching(){
        $readyToDispatchTrips = Trip::where('trips_status_id','!=',5)->where('driver_id',null)
            ->doesntHave('drivers_in_dispatching')->get();
        foreach ($readyToDispatchTrips as $trip){
            if ($trip->drivers_in_dispatching->whereIn('pivot.request_sent_status',[1,0])->count()==0){
                $trip->drivers_in_dispatching()->detach();
            }
        }
    }

    #assignDriverToTrip
    private function assignDriverToTrip($trip, $drivers)
    {
        $deliveries=new Collection();

        foreach ($drivers as $delivery){

            $deliveries->add(['driver_id' => $delivery->id]);

        }
        $sorted=$deliveries->sortByDesc('id');

        $drivers_ids= $sorted->pluck('driver_id');
        $sorted=$sorted->values()->all();

        foreach ($sorted as $key=>$driver){

            if ($key==0){
                Admin::find($driver['driver_id'])->trips_in_queue()->attach($trip,["rank"=>$key,"request_sent_at"=>Carbon::now(),"request_sent_status"=>1]);
            }
            else {
                Admin::find($driver['driver_id'])->trips_in_queue()->attach($trip,["rank"=>$key]);
            }
        }
        return $drivers_ids;
    }

    #updateDispatchingUser
    public function updateDispatchingUser(){
        $readyToDispatchTrips = Trip::where('trips_status_id','!=',5)->where('driver_id',null)
            ->doesntHave('drivers_in_dispatching')->get();
        if ($readyToDispatchTrips->count()>0){
            $all_trips= $readyToDispatchTrips->load('dispatcher');
            foreach ($all_trips as $trip){
                $driverToSend=$trip->dispatcher->where('request_sent_status',1)->first();
                if ($driverToSend) {
                    if (Carbon::now()->subMinute() > $driverToSend->request_sent_at) {
                        $driverToSend->update(["request_sent_status" => 2]);
                        $this->giveTripToNextDriver($trip, $driverToSend->rank + 1);
                    }
                }
            }
        }
    }

    #acceptOrDeclineTrip
    public function acceptOrDeclineTrip(Request $request){
        $validator=\Validator::make($request->all(),[
            "trip_id"=>"required",
            "trip_status"=>"required|in:accept,not_accept"
        ]);
        if ($validator->fails()){
           return $this->apiResponse('',$validator->errors()->first(),400);
        }
        $delivery = $delivery = Admin::find($request->user()->id);
        if (empty($delivery))
        {
            return $this->apiResponse('','this delivery not found in system',404);
        }

        $trip_id = $request->trip_id;
        $trip_status = $request->trip_status;

        $allTrips= $this->getAllMyReadyTrips($delivery);
        $trip=$allTrips->find(['trip_id'=>$trip_id]);
        if ($trip->count()>0){
            $trip=Trip::find($trip_id);
            if (!$trip){
               return $this->apiResponse('','You Do Not Have Any Trips In Your Queue',404);
            }
            if ($trip->trip_status_id==5){
              return $this->apiResponse('','The Trip With That Id Already Canceled So Status Can Not Be Updated',201);
            }
            if ($trip_status=="accept"){
                $trip->update([
                    "driver_id"=>$delivery->id,
                    "trips_driver_status"=>2,
                    "trips_status_id"=>3
                ]);
                $trip->trip_driver_statuses()->attach(2);
                $trip->trip_main_statuses()->attach(3);
                $statementTransactions = \DB::table('statement_transactions')
                    ->where('order_id','=',$trip->order_id)
                    ->where('to_user_type','=','delivery')
                    ->where('from_user_type','=','user')->get();
                for ($i=0; $i<count($statementTransactions); $i++)
                {
                    $statementTransactions[$i]->update([
                        'to_id'=>$trip->driver_id,
                    ]);
                }
               /* $trip->trip_timing->update(
                    ['started_at'=>Carbon::now()]
                );*/
                $inQuyDrivers=$trip->drivers_in_dispatching;
                foreach ($inQuyDrivers as $driver){
                    $driver->pivot->update(["request_sent_status"=>2]);
                }
                return $this->apiResponse('Trip Is Now Yours','',200);
            }
            else{
                $inQuyDriver=$trip->drivers_in_dispatching->find($delivery->id);
                if ($inQuyDriver){
                    $inQuyDriver->pivot->update(["request_sent_status"=>2]);
                    $this->giveTripToNextDriver($trip,$inQuyDriver->pivot->rank+1);
                   return $this->apiResponse('Trip Declined','',200);
                }
                else {
                   return $this->apiResponse('','You Are Not In The Queue',404);
                }
            }


        }
        else {
            return $this->apiResponse('','You Are Not In The Queue',404);
        }
    }

    #getAllMyReadyTrips
    public function getAllMyReadyTrips($user){

        $myTrips= $user->load(['trips_in_queue'=>function($query){
            $query->where('request_sent_status',1);
        }]);
        return $myTrips->trips_in_queue;
    }

    #giveTripToNextDriver
    public function giveTripToNextDriver($trip,$rank){
        $next_driver=$trip->dispatcher->where('rank',$rank)->first();
        if ($next_driver){
            $next_driver->update(["request_sent_status"=>1,"request_sent_at"=>Carbon::now()]);
        }
    }

    #getDeliveryTrips
    public function getDeliveryTrips(Request $request)
    {
        try{
            $validator=\Validator::make($request->all(),[
                "lang"=>"required|in:ar,en"
            ]);
            if ($validator->fails()){
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $delivery = $delivery = Admin::find($request->user()->id);
            if (empty($delivery))
            {
                return $this->apiResponse('','this delivery not found in system',404);
            }
            $order = Trip::join('orders','orders.id','=','trips.order_id')
                ->where('trips.trips_status_id',3)
                ->where('trips.trips_driver_status',2)
                ->where('trips.driver_id',$delivery->id)
                ->select('orders.*','trips.id as tripId','trips.driver_id as driverId')->get();
            if ($order->isEmpty())
            {
                return $this->apiResponse('','not found trips',404);
            }
            for ($i=0; $i<count($order); $i++)
            {
                $details[] = [
                    'orderId'=>$order[$i]->id,
                    'tripId'=>$order[$i]->tripId,
                    'driverId'=>$order[$i]->driverId,
                    'restaurantId'=>$order[$i]->restaurant_id,
                    'restaurantName'=>Restaurant::find($order[$i]->restaurant_id)->translate($request->lang)->name,
                    'userId'=>$order[$i]->user_id,
                    'customerName'=>User::find($order[$i]->user_id)->firstName.' '.User::find($order[$i]->user_id)->lastName,
                    'customerPhone'=>User::find($order[$i]->user_id)->phone,
                    'customerAddress'=>User::find($order[$i]->user_id)->address,
                    'addressOrder'=>AddressOrder::where('order_id',$order[$i]->id)->value('address') == null ? 'not found' : AddressOrder::where('order_id',$order[$i]->id)->value('address'),
                    'latOrder'=>AddressOrder::where('order_id',$order[$i]->id)->value('lat') == null ? 'not found' : AddressOrder::where('order_id',$order[$i]->id)->value('lat'),
                    'lngOrder'=>AddressOrder::where('order_id',$order[$i]->id)->value('lng') ==null ? 'not found' : AddressOrder::where('order_id',$order[$i]->id)->value('lng'),
                    'amount'=>$order[$i]->amount,
                    'dateTime'=>$order[$i]->dateTime,
                    'amountDelivery'=>$order[$i]->amount_delivery,
                    'typeShift'=>$order[$i]->type_shift,
                    'paymentType'=>$order[$i]->payment_type,
                    'status'=>$order[$i]->status,
                ];
            }
            return $this->apiResponse($details,'',200);

        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #getDetailsOrderDelivery
    public function getDetailsOrderDelivery(Request $request)
    {
        try{

            $validator=\Validator::make($request->all(),[
                "lang"=>"required|in:ar,en",
                "order_id"=>"required|exists,orders,id"
            ]);
            if ($validator->fails()){
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $delivery = $delivery = Admin::find($request->user()->id);
            if (empty($delivery))
            {
                return $this->apiResponse('','this delivery not found in system',404);
            }

            $orderDetails = OrderDetails::where('order_id',$request->order_id)->get();
            if ($orderDetails->isEmpty())
            {
                return $this->apiResponse('','not found details this order id '.$request->order_id,404);
            }
            for ($i=0; $i<count($orderDetails); $i++)
            {
                $details[] =
                    [
                        'menuDetailsId'=>$orderDetails[$i]->menu_details_id,
                        'menuDetailsName'=>MenuDetails::find($orderDetails[$i]->menu_details_id)->translate($request->lang)->name,
                        'menuDetailsPrice'=>percentageOrder(MenuDetails::find($orderDetails[$i]->menu_details_id)->price),
                        'menuDetailsImage'=>MenuDetails::find($orderDetails[$i]->menu_details_id)->imagePath,
                    ];
            }
            return $this->apiResponse($details,'',200);
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }

    }

    #DoneDeliverOrder
    public function DoneDeliverOrder(Request $request)
    {
        try{

            $validator=\Validator::make($request->all(),[
                "trip_id"=>"required|exists:trips,id",
                "order_id"=>"required|exists:orders,id",
                "status"=>"required|in:1,0"
            ]);
            if ($validator->fails()){
                return $this->apiResponse('',$validator->errors()->first(),400);
            }
            $delivery = $delivery = Admin::find($request->user()->id);
            if (empty($delivery))
            {
                return $this->apiResponse('','this delivery not found in system',404);
            }
        }catch (\Exception$exception )
        {
            return $this->apiResponse(null,$exception->getMessage(),520);
        }
        $checkOrder = Order::where('id',$request->order_id)
            ->where('status','!=','pending')->first();
        if (empty($checkOrder))
        {
            if ($request->status == 1)
            {

                $trip = Trip::find($request->trip_id);
                $trip->update([
                    'trips_driver_status'=>3
                ]);
                $order = Order::find($request->order_id);
                $order->update([
                    'status'=>'complete'
                ]);
                if ($trip && $order)
                {
                    return $this->apiResponse('done delivery order success','',200);
                }
                return $this->apiResponse('','please try again',400);
            }
            else
            {
                $trip = Trip::find($request->trip_id);
                $trip->update([
                    'trips_status_id'=>5
                ]);
                $order = Order::find($request->order_id);
                $order->update([
                    'status'=>'cancel'
                ]);
                if ($trip && $order)
                {
                    return $this->apiResponse('this order is cancel and trip is cancel','',200);
                }
                return $this->apiResponse('','please try again',400);

            }
        }
        else
        {
            return $this->apiResponse('','this order is '.$checkOrder->status,400);
        }

    }
}
