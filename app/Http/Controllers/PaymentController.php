<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RequestInfo;
use App\Models\Requests;
use App\Models\User;
use App\Models\Deposits;
use App\Models\Inboxes;
use App\Models\InboxInfo;
use App\Models\WalletAction;
use App\Models\WalletUser;
use App\Models\Wallet;
use App\Models\UserTask;
use Stripe\Stripe;
use Pusher;
use Session;

class PaymentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function createDeposit($paymentMethod, $request_id) {
        $requestInfo = RequestInfo::where('request_id', '=', $request_id)->get();
        $requestInfo = $requestInfo[0];
        $requestInfo->status = 2;
        $requestInfo->save();

        $request = Requests::find($request_id);
        $user_id = $request->send_id;
        $user = User::find($user_id);

        $user1_id = $request->receive_id;
        $chat = new Inboxes;
        $chat->user1_id = $user1_id;
        $chat->user2_id = $user_id;
        $chat->request_id = $request_id;
        $chat->save();

        $pusher = new Pusher\Pusher('da7cd3b12e18c9e2e461', '566ee6622fcab95b7709', '1168466', array('cluster' => 'eu'));

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'newInbox',
            'request' => $request,
        ]);

        $chatInfo = new InboxInfo;
        $chatInfo->inbox_id = $chat->id;
        $chatInfo->send_id = $user_id;
        $chatInfo->receive_id = $user1_id;
        $chatInfo->content = "Hi";
        $chatInfo->upload = 'none';
        $chatInfo->save();

        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
        $paymentIntent = \Stripe\PaymentIntent::create([
            'payment_method' => $paymentMethod,
            'amount' => $requestInfo->amount * 100,
            'currency' => strtolower($requestInfo->unit),
            'confirm' => true
        ]);


        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'request_status',
            'request_id' => $request_id,
            'status' => 2,
        ]);

        $walletUser = WalletUser::where('user_id', '=', Auth::user()->id)->get();
        $wallet_id = $walletUser[0]->wallet_id;

        $walletAction = new WalletAction;
        $walletAction->wallet_id = $wallet_id;
        $walletAction->amount = $requestInfo->amount;
        $walletAction->action = 'create deposit';
        $walletAction->currency = $requestInfo->unit;
        $walletAction->aaa = '-';
        $walletAction->save();

        $wallet = Wallet::find($wallet_id);
        switch ($requestInfo->unit) {
            case 'usd':
                $wallet->usd_balance -= $requestInfo->amount; 
                break;
            case 'gbp':
                $wallet->gbp_balance -= $requestInfo->amount;
                break;
            case 'eur':
                $wallet->eur_balance -= $requestInfo->amount;
                break;
            default:
                break;
        }
        $wallet->save();

        $userTask = new UserTask;
        $userTask->task_id = $request_id;
        $userTask->user_id = $request->receive_id;
        $userTask->isRead = 0;
        $userTask->save();

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'newTask',
            'request' => $request,
        ]);


        return response()->json([
            'status' => 200,
        ]);
    }

    public function releaseDeposit($request_id) {
        $requestInfo = RequestInfo::where('request_id', '=', $request_id)->get();
        $requestInfo = $requestInfo[0];

        $request = Requests::find($request_id);
        $user_id = $request->receive_id;
        $user = User::find($user_id);

        \Stripe\Stripe::setApiKey(env("STRIPE_SECRET"));
        $paymentIntent = \Stripe\Transfer::create([
            'amount' => $requestInfo->amount * 85,
            'currency' => strtolower($requestInfo->unit),
            'destination' => $user->stripe_id
        ]);

        $requestInfo = RequestInfo::where('request_id', '=', $request_id)->get();
        $requestInfo = $requestInfo[0];
        $requestInfo->status = 3;
        $requestInfo->save();

        $pusher = new Pusher\Pusher('da7cd3b12e18c9e2e461', '566ee6622fcab95b7709', '1168466', array('cluster' => 'eu'));

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'request_status',
            'request_id' => $request_id,
            'status' => 3,
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    public function balance(Request $request) {
        $walletUser = WalletUser::where('user_id', '=', Auth::user()->id)->get();
        $wallet_id = $walletUser[0]->wallet_id;

        $walletAction = WalletAction::where('wallet_id', '=', $wallet_id)->get();

        $wallet = Wallet::find($wallet_id);
        
        return view('wallet', [
            'page' => 5,
            'unread' => $request->get('unread'),
            'wallet' => $wallet,
            'walletActions' => $walletAction,
        ]);
    }
}