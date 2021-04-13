<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfluencerInfo;
use App\Models\Partnership;

class WelcomeController extends Controller
{
    public function index()
    {
        $influencerInfo = new InfluencerInfo();
        $influencerInfo = $influencerInfo->getFeaturedInfluencers();

        $partnerships = Partnership::orderBy('created_at')
                ->take(4)
                ->get();

        return view('welcome', [
            'featuredInfluencers' => $influencerInfo,
            'partnerships' => $partnerships,
        ]);
    }    
}