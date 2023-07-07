<?php

namespace App\Http\Controllers;

use App\Traits\ApiRespone;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Stripe;

class PaymentController extends Controller
{
    use ApiRespone;
    public function checkout(Request $req){
        // return $req->all();
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // Stripe\Charge::create([
        //     'amount'=>1000,
        //     'currency' => 'EGP',
        //     'source' => $req->stripe->token,
        //     'description'=>'Test'
        // ]);
        // Session::flash('success','payment has been successfully');
        return $this->success('payment has been successfully done');
    }
}
