<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestInfo;
use App\Models\Requests;
use App\Models\User;
use App\Models\Deposits;
use Stripe\Stripe;
use Session;

class PaymentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function createDeposit($paymentMethod, $request_id) {
        $requestInfo = RequestInfo::where('request_id', '=', $request_id)->get();
        $requestInfo = $requestInfo[0];

        $request = Requests::find($request_id);
        $user_id = $request->send_id;
        $user = User::find($user_id);

        echo $user->id;

        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
        $paymentIntent = \Stripe\PaymentIntent::create([
            'payment_method' => $paymentMethod,
            'amount' => $requestInfo->amount * 100,
            'currency' => strtolower($requestInfo->unit),
            'application_fee_amount' => $requestInfo->amount * 3,
            'confirm' => true,
            'transfer_data' => [
                'destination' => $user->stripe_id
            ],
        ]);

        // $deposit = new Deposits;
        // $deposit->request_id = $request_id;
        // $deposit->client_secret = $paymentIntent->client_secret;
        // $deposit->statue = 0;
        // $deposit->save();

        return response()->json([
            'status' => 200,
        ]);
    }

    public function transferDeposit($paymentMethod, $request_id) {
        $requestInfo = RequestInfo::where('request_id', '=', $request_id)->get();
        $requestInfo = $requestInfo[0];

        $request = Requests::find($request_id);
        $user_id = $request->receive_id;
        $user = User::find($user_id);

        echo $user->id;

        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
        $paymentIntent = \Stripe\PaymentIntent::create([
            'payment_method' => $paymentMethod,
            'amount' => $requestInfo->amount * 100,
            'currency' => strtolower($requestInfo->unit),
            'application_fee_amount' => $requestInfo->amount * 15,
            'confirm' => true,
            'transfer_data' => [
                'destination' => $user->stripe_id
            ],
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }
}