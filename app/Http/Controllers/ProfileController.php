<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Review;
use App\Models\Profile;
use App\Models\Portfolio;
use App\Models\Category;
use App\Models\Partnership;
use App\Models\Influencers;
use App\Models\InfluencerInfo;

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

    public function editProfile($username) {
        $userInfo = User::where('username', '=', $username)->get();
        $user_id = $userInfo[0]->id;
        $user = new User();
        $influencerInfo = $user->getAccountInfoByUserID($user_id);
        
        $accountInfo = $user->getAccountInfoByUserID(Auth::user()->id);

        $reviews = Review::where('influencer_id', $influencerInfo[0]->influencer_id)->get();

        $profile = Profile::where('user_id', $user_id)->get();

        $portfolios = Portfolio::where('profile_id', $profile[0]->id)->get();

        $category = new Category();
        $categories = $category->getCategories($influencerInfo[0]->id);
        if(count($categories) == 0) $categories = Category::all();

        $partnerships = Partnership::where('influencer_id', $influencerInfo[0]->influencer_id)->get();

        return view('editProfile', [
            'page' => 5,
            'accountInfo' => $accountInfo[0],
            'influencerInfo' => $influencerInfo[0],
            'profile' => $profile[0],
            'portfolios' => $portfolios,
            'reviews' => $reviews,
            'categories' => $categories,
            'partnerships' => $partnerships,
        ]);
    }

    public function updateProfile(Request $request, $user_id) {
        $input = $request->all();
        
        $folderPath = public_path('img/profile-image/');
        // update profile
        $profile = Profile::where('user_id', $user_id)->get();
        $profile[0]->introduction = $input['introduction'];

        // //update top image 
        if($input['top-image'] != ''){
            $image_parts = explode(";base64,", $input['top-image']);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filename = uniqid();
            $file = $folderPath . $filename . '.jpg';
            file_put_contents($file, $image_base64);
            $profile[0]->top_img = $filename;
        }

        // //update round image
        if($input['round-image'] != '') {
            $image_parts = explode(";base64,", $input['round-image']);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filename = uniqid();
            $file = $folderPath . $filename . '.jpg';
            file_put_contents($file, $image_base64);
            $profile[0]->round_img = $filename;
        }
        
        // // update social links
        if(isset($input['instagram']) && $input['instagram']) {
            if($input['instagram-link'] != '')
                $profile[0]->instagram = $input['instagram-link'];
            if($input['instagram-follow'] != '')
                $profile[0]->instagram_follows = $input['instagram-follow'];
        }
        if(isset($input['youtube']) && $input['youtube']) {
            if($input['youtube-link'] != '')
                $profile[0]->youtube = $input['youtube-link'];
            if($input['youtube-follow'] != '')
                $profile[0]->youtube_follows = $input['youtube-follow'];
        }
        if(isset($input['tiktok']) && $input['tiktok']) {
            if($input['tiktok-link'] != '')
                $profile[0]->tiktok = $input['tiktok-link'];
            if($input['tiktok-follow'] != '')
                $profile[0]->tiktok_follows = $input['tiktok-follow'];
        }
        $profile[0]->save();


        // update user info
        $user = User::find($user_id);
        if($input['name'] != '') $user->name = $input['name'];
        if($input['username'] != '') $user->username = $input['username'];
        $user->save();

        // update account
        $user = new User();
        if($user->checkIfInfluencer($user_id)) {
            $influencer = Influencers::where('user_id', '=', $user_id)->get();
            $influencer_id = $influencer[0]->id;
            $influencerInfo = InfluencerInfo::where('influencer_id', '=', $influencer_id)->get();
            $influencerInfo = $influencerInfo[0];
            if($input['location'] != '') {
                $influencerInfo->country = explode(',', $input['location'])[1];
                $influencerInfo->state = explode(',', $input['location'])[0];
            }
            if($input['round-image'] != '') {
                $influencerInfo->avatar = $profile[0]->round_img;
            }
            $influencerInfo->save();
        } else {
            $brand = Brands::where('user_id', '=', $user_id);
            $brand_id = $brand->id;
            $brandInfo = BrandInfo::where('brand_id', '=', $brand_id)->get();
            $brandInfo = $brandInfo[0];
            if($input['location'] != '') {
                $brandInfo->country = explode(', ', $input['location'])[1];
                $brandInfo->state = explode(', ', $input['location'])[0];
            }
            if($input['round-image'] != '') {
                $brandInfo->avatar = $profile[0]->round_img;
            }
            $brandInfo->save();
        }

        // update portfolios
        $profile_id = $profile[0]->id;
        // //delete all portfolios
        $portfolios = Portfolio::where('profile_id', '=', $profile_id)->get();
        foreach ($portfolios as $portfolio) {
            $file = $folderPath . $portfolio->slide_img . '.jpg';
            unlink($file);
            $portfolio->delete();
        }
        // // save portfolios
        if($input['portfolio-image'] != '') {
            $influencer = Influencers::where('user_id', '=', $user_id)->get();
            $influencer_id = $influencer[0]->id;
            $images = json_decode($input['portfolio-image']);
            foreach ($images as $image) {
                $image_parts = explode(";base64,", $image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $filename = uniqid();
                $file = $folderPath . $filename . '.jpg';
                file_put_contents($file, $image_base64);
                    $portfolio = new Portfolio;
                $portfolio->influencer_id = $influencer_id;
                $portfolio->partnership_img = $filename;
                $portfolio->save();
            }
        }

        // update partnerships
        $user = new User();
        if($user->checkIfInfluencer($user_id)) {
            $influencer = Influencers::where('user_id', '=', $user_id)->get();
            $influencer_id = $influencer[0]->id;
            $partnerships = Partnership::where('influencer_id', '=', $influencer_id);
            $folderPath = public_path('img/partnership-image/');
            foreach ($partnerships as $partnership) {
                $file = $folderPath . $partnership->partnership_img . '.jpg';
                unlink($file);
                $partnership->delete();
            }
            if($input['partnership-image'] != '') {
                $images = json_decode($input['partnership-image']);
                foreach ($images as $image) {
                    $image_parts = explode(";base64,", $image);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $filename = uniqid();
                    $file = $folderPath . $filename . '.jpg';
                    file_put_contents($file, $image_base64);
                    $partnership = new Partnership;
                    $partnership->profile_id = $profile_id;
                    $partnership->partnership_img = $filename;
                    $partnership->save();
                }
            }
        }

        // update category
        $user = new User();
        if(isset($input['category']) && $input['category']) {
            $categories = $input['category'];
            $user->updateCategory($user_id, $categories);
        }

        $user = User::find($user_id);
        return redirect()->route('profile', [
            'username' => $user->username
        ]);
    }
}