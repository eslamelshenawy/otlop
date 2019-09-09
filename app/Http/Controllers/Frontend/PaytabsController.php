<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CityTranslation;
use Paytabs;
use Session;
use App;

class PaytabsController extends Controller
{
    /**
     * Request payment
     *
     * @return response
     */
    public function paymentRequest() {
        $data = Session::get('data');
        $city=CityTranslation::where('city_id',$data[0]->city_id)->get();
        if(App::getLocale()=="ar"){
           $name_ar= $city[0]->name;
        }else{
            $name_en= $city[1]->name;

        }

        $pt = Paytabs::getInstance("eslamelshenawy9316@gmail.com","8bSp2NXgF91MERoz3IklthAiQ38HIJ9QU1fWPdduneh16FB9oWvSd6b49jMR2VIWXYTNc6izlzBrfcBE96wmcHrm0ee3aWZ7UTV7");
        $result = $pt->create_pay_page(array(
            "merchant_email" => "eslamelshenawy9316@gmail.com",
            'secret_key' => "8bSp2NXgF91MERoz3IklthAiQ38HIJ9QU1fWPdduneh16FB9oWvSd6b49jMR2VIWXYTNc6izlzBrfcBE96wmcHrm0ee3aWZ7UTV7",
            'title' => "John Doe",
            'cc_first_name' =>  $data[0]->firstName,
            'cc_last_name' =>  $data[0]->lastName,
            'email' => $data[0]->email,
            'cc_phone_number' => "973",
            'phone_number' => $data[0]->phone ,
            'billing_address' => $data[0]->address ? $data[0]->address: " cairo ",
            'city' => App::getLocale()=="ar" ? $city[0]->name :$city[1]->name ,
            'state' => "Capital",
            'postal_code' => "97300",
            'country' => "BHR",
            'address_shipping' => "Juffair, Manama, Bahrain",
            'city_shipping' => "Manama",
            'state_shipping' => "Capital",
            'postal_code_shipping' => "97300",
            'country_shipping' => "SAU",
            "products_per_title"=> "COMPANY NAME",
            'currency' => "SAR",
            "unit_price"=> $data[1]->amount,
            'quantity' => "1",
            'other_charges' => "0",
            'amount' => $data[1]->amount,
            'discount'=>"0",
            "msg_lang" => "english",
            "reference_no" => '1',
            "site_url" => "https://raaleat.com",                                       // INSERT HERE SITE URL
            'return_url' => "https://raaleat.com/payment/paytabs/response",      // INSERT HERE RETURN URL RESPONSE
            "cms_with_version" => "API USING PHP"
        ));

        if($result->response_code == 4012){
            return redirect($result->payment_url);
        }
        return $result->result;
    }

    /**
     * Payment response
     *
     * @return response
     */
    public function paymentResponse(Request $request) {
        $pt = Paytabs::getInstance("eslamelshenawy9316@gmail.com","8bSp2NXgF91MERoz3IklthAiQ38HIJ9QU1fWPdduneh16FB9oWvSd6b49jMR2VIWXYTNc6izlzBrfcBE96wmcHrm0ee3aWZ7UTV7");
        $result = $pt->verify_payment($request->payment_reference);
        if($result->response_code == 100){

//            return 'payment done';
            return redirect('success');


        }
        return $result->result;
    }

//
//    public function success(){
//        return view('frontend.checkout.success');
//
//    }
}
