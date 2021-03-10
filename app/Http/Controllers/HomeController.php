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
        $account = Influencers::where('user_id', Auth::user()->id)->get();

        if(count($account) == 0) {
            $accountType = 'brand';
            $account = Brands::where('user_id', Auth::user()->id)->get();

            $accountInfo = BrandInfo::where('id', $account[0]->id)->get();
        } else {
            $accountType = 'influencer';
            $accountInfo = InfluencerInfo::where('id', $account[0]->id)->get();
        }
        $user = User::where('id', Auth::user()->id)->get();


        //echo $accountType, $accountInfo;

        return view('home', [
            'accountType' => $accountType,
            'accountInfo' => $accountInfo[0],
            'accountName' => $user[0]->name,
            'accountEmail' => $user[0]->email,
        ]);
    }
}
