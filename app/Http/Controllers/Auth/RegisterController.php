<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Influencers;
use App\Models\InfluencerInfo;
use App\Models\Brands;
use App\Models\BrandInfo;
use App\Models\Profile;
use App\Models\Portfolio;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'name.required' => 'Please enter your name!',
            'email.required' => 'Please enter your email!',
            'password.confirmed' => 'Retype your password!',
            'accountType.required' => 'Please select the account type!',
            'agreement.required' => 'Do you agree with our terms and conditions?'
        ];

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accountType' => ['required'],
            'agreement' => ['required'],
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $token = Str::random(60);
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->api_token = hash('sha256', $token);
        $user->save();

        if($user->id != NULL) {
            if($data['accountType'] == 'influencer'){
                $influencer = new Influencers;
                $influencer->user_id = $user->id;
                $influencer->save();

                $influencerInfo = new InfluencerInfo;
                $influencerInfo->influencer_id = $influencer->id;
                $influencerInfo->save();
            }
            else {
                $brand = new Brands;
                $brand->user_id = $user->id;
                $brand->save();
                
                $brandInfo = new BrandInfo;
                $brandInfo->brand_id = $brand->id;
                $brandInfo->save();
            }
            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->introduction = "Hi";
            $profile->top_img = "default_top";
            $profile->round_img = "default_round";
            $profile->instagram = "";
            $profile->instagram_follows = "";
            $profile->youtube = '';
            $profile->youtube_follows = '';
            $profile->tiktok = '';
            $profile->tiktok_follows = '';
            $profile->save();

            $portfolio = new Portfolio;
            $portfolio->profile_id = $profile->id;
            $portfolio->slide_img = 'default_slide';
            $portfolio->save();

            return $user;
        }
    }
}