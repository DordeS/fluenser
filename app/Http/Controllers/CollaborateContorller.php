<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\InfluencerInfo;

class CollaborateContorller extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index($influencer_id) {
        $user = new User();
        $accountInfo = $user->getAccountInfoByUserID(Auth::user()->id);
        
        $influencersInfo = new InfluencerInfo;
        $influencerInfo = $influencersInfo->where('influencer_id', $influencer_id)
                ->get();

        return view('collaborate', [
            'page' => 0,
            'accountInfo' => $accountInfo[0],
            'influencerInfo' => $influencerInfo[0],
        ]);
    }
}
