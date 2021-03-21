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
        $page = 1;
        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);

        // echo $accountInfo;

        return view('home', [
            'accountType' => $accountInfo[0]->accountType,
            'accountInfo' => $accountInfo[0],
            'page' => $page,
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
