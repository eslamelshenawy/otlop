<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
class FirebaseController extends Controller
{
    public function index(){
//        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/raaleat-firebase-adminsdk-bpasi-86a8397ed1.json');
//        $firebase 		  = (new Factory)
//            ->withServiceAccount($serviceAccount)
//            ->withDatabaseUri('https://raaleat.firebaseio.com')
//            ->create();
//        $database 		= $firebase->getDatabase();
//        $newPost 		  = $database
//            ->getReference('blog/posts')
//            ->push(['title' => 'Post title','body' => 'This should probably be longer.']);
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/raaleat-firebase-adminsdk-bpasi-86a8397ed1.json');
        $firebase 		  = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://raaleat.firebaseio.com')
            ->create();
        $database 		= $firebase->getDatabase();
        $newPost 		  = $database
            ->getReference('wallets/share');
//            ->push(['status' => "pending",'user_token' => $users->user_token,'amount' => $money,'firstName' => $users->firstName,'phone' => $phone]);


        echo"<pre>";
        print_r($newPost->getvalue());
    }
}
?>