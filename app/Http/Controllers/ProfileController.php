<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Review;
use App\Models\Profile;
use App\Models\Portfolio;
use App\Models\Category;
use App\Models\Partnership;

class ProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index($username) {
        $userInfo = User::where('username', '=', $username)->get();
        $user_id = $userInfo[0]->id;
        $user = new User();
        $influencerInfo = $user->getAccountInfoByUserID($user_id);
        
        $accountInfo = $user->getAccountInfoByUserID(Auth::user()->id);

        $reviews = Review::where('influencer_id', $influencerInfo[0]->id)->get();

        $profile = Profile::where('user_id', $user_id)->get();

        $portfolios = Portfolio::where('profile_id', $profile[0]->id)->get();

        $category = new Category();
        $categories = $category->getCategories($influencerInfo[0]->id);

        $partnerships = Partnership::where('influencer_id', $influencerInfo[0]->id)->get();

        return view('profile', [
            'page' => 4,
            'accountInfo' => $accountInfo[0],
            'influencerInfo' => $influencerInfo[0],
            'profile' => $profile[0],
            'portfolios' => $portfolios,
            'reviews' => $reviews,
            'categories' => $categories,
            'partnerships' => $partnerships,
        ]);
    }
}
