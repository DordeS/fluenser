<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencers;
use App\Models\Brands;
use App\Models\User;
use App\Models\InfluencerInfo;
use App\Models\BrandInfo;
use App\Models\Profile;
use App\Models\UserInbox;
use App\Models\UserRequest;
use App\Models\UserTask;
use Stripe\Stripe;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $page = 1;
        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);

        $profile = new Profile();
        $portfolios = $profile->getPortfolios(Auth::user()->id);

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $account_info = $stripe->accounts->retrieve(
            Auth::user()->stripe_id,
            []
        );

        if($account_info->charges_enabled && $account_info->payouts_enabled) {
            $account_links = '';
        } else {
            $account_links = $stripe->accountLinks->create([
                'account' => Auth::user()->stripe_id,
                'refresh_url' => route('home'),
                'return_url' => route('home'),
                'type' => 'account_onboarding'
            ]);
        }

        $unread = $request->get('unread');

        return view('home', [
            'unread' => $unread,
            'portfolios' => $portfolios,
            'accountType' => $accountInfo[0]->accountType,
            'accountInfo' => $accountInfo[0],
            'page' => $page,
            'account_link' => $account_links,
        ]);
    }

    public function dashboard()
    {
        $page = 5;
        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);

        // echo $accountInfo;

        return view('dashboard', [
            'accountType' => $accountInfo[0]->accountType,
            'accountInfo' => $accountInfo[0],
            'page' => $page,
        ]);
    }
}