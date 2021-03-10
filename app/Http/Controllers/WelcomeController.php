<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfluencerInfo;

class WelcomeController extends Controller
{
    public function index()
    {
        $influencerInfo = InfluencerInfo::orderBy('arg_rate')
                            ->take(4)
                            ->get();
        return view('welcome', [
            'featuredInfluencers' => $influencerInfo,
        ]);
    }    
}
