<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencers;
use App\Models\Brands;
use App\Models\User;
use App\Models\InfluencerInfo;
use App\Models\BrandInfo;

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
    public function index()
    {
        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);

        echo $accountInfo;

        // if($accountInfo == 'none') {
        //     return view('home');
        // } else {
        //     return view('home', [
        //         'accountType' => $accountInfo[0]->accountType,
        //         'accountInfo' => $accountInfo[0],
        //     ]);
        // }
    }
}
