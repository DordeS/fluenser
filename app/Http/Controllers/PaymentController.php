<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;
use Session;

class PaymentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('payment');
    }

    public function makePayment(Request $reqeust) {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create([
            "amount" => 120*100,
            'currency' => "usd",
            'source' => $reqeust->stripeToken,
            'description' => 'Make payment and chill'
        ]);

        Session::flash('success', 'Payment_successfully make.');

        return back();
    }
}
