<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\InfluencerInfo;
use App\Models\Requests;
use App\Models\RequestInfo;
use App\Models\RequestImg;
use App\Models\Review;
use App\Models\BrandInfo;
use App\Models\UserRequest;
use Illuminate\Support\Facades\Validator;

class CollaborateController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request, $user_id) {
        $user = new User();
        $accountInfo = $user->getAccountInfoByUserID(Auth::user()->id);
        
        $influencerInfo = $user->getAccountInfoByUserID($user_id);

        return view('collaborate', [
            'page' => 4,
            'unread' => $request->get('unread'),
            'accountInfo' => $accountInfo[0],
            'influencerInfo' => $influencerInfo[0],
        ]);
    }

    public function saveRequest(Request $request) {
        $input = $request->all();

        $rule = [
            'title' => 'required|max:255',
            'detail' => 'required',
            'price' => 'required',
            'image' => 'required',
        ];

        $message = [
            'title.required' => 'You have to enter the title of your project!',
            'title.max' => 'Your title is too long',
            'detail.regex' => 'You can enter only letters and numbers!',
            'detail.required' => 'You have to enter the project details',
            'price.required' => 'You have to enter the budget.',
            'image.required' => 'You have to upload images for your brand.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if($validator->fails()) {
            return redirect()->route('collaborate', ['user_id'=> $input['influencer_id']])
                    ->withErrors($validator)
                    ->withInput($input);
        }

        $request = new Requests;
        $request->send_id = $input['brand_id'];
        $request->receive_id = $input['influencer_id'];
        $request->save();

        $request_info = new RequestInfo;
        $request_info->request_id = $request->id;
        $request_info->title = $input['title'];
        $request_info->content = $input['detail'];
        $request_info->amount = $input['price'];
        $request_info->unit = $input['currency'];
        $request_info->brand = 'unknown';
        $request_info->status = 1;
        $request_info->accepted = 0;
        $request_info->sr_review = 0;
        $request_info->rs_review = 0;
        $request_info->save();

        $folderPath = public_path('img/task-image/');
        $images = json_decode($input['images']);
        $requestImages = [];
        foreach ($images as $image) {
            $image_parts = explode(";base64,", $image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filename = uniqid();
            $file = $folderPath . $filename . '.jpg';
            file_put_contents($file, $image_base64);
    
            $request_img = new RequestImg;
            $request_img->request_id = $request->id;
            $request_img->image = $filename;
            $request_img->save();
            array_push($requestImages, $request_img);
        }

        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID($input['brand_id']);

        $request->accountInfo = $accountInfo;
        $request->requestContent = $request_info;
        $request->requestContent->images = $requestImages;

        $pusher = new Pusher\Pusher('da7cd3b12e18c9e2e461', '566ee6622fcab95b7709', '1168466', array('cluster' => 'eu'));

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'influencer_id' => $input['influencer_id'],
            'request' => $request,
            'trigger' => 'request',
        ]);

        $receiveUserRequest = new UserRequest;
        $receiveUserRequest->request_id = $request->id;
        $receiveUserRequest->user_id = $request->receive_id;
        $receiveUserRequest->isRead = 0;
        $receiveUserRequest->save();

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'newRequest',
            'request' => $request,
        ]);

        return redirect('request');
    }

    public function leaveReview(Request $request, $request_id){
        $requests = Requests::find($request_id);
        
        $requestInfo = RequestInfo::where('request_id', '=', $request_id)->get();

        $user_id = (Auth::user()->id == $requests->send_id) ? $requests->receive_id : $requests->send_id;
        $user = new User();
        $accountInfo = $user->getAccountInfoByUserID($user_id);

        return view('review', [
            'page' => 0,
            'unread' => $request->get('unread'),
            "accountInfo" => $accountInfo[0],
            'requestInfo' => $requestInfo[0],
        ]);
    }

    public function submitReview(Request $request, $request_id) {
        $input = $request->all();

        $request = Requests::find($request_id);
        $user_id = (Auth::user()->id == $request->send_id) ? $request->receive_id : $request->send_id;

        $review = new Review;
        $review->user_id = $user_id;
        $review->request_id = $request_id;
        $review->review = $input['comment'];
        $review->star = $input['rating'];
        $review->save();
        
        $user = new User();
        $accountInfo = $user->getAccountInfoByUserID($user_id);

        $requestInfo = RequestInfo::where('request_id', '=', $request_id)->get();
        if($accountInfo[0]->accountType == 'brand') {
            $requestInfo[0]->rs_review = 1;
        } else {
            $requestInfo[0]->sr_review = 1;
        }
        $requestInfo[0]->save();

        $reviews = Review::where('user_id', '=', $user_id)->get();
        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review->star;
        }
        $totalRating = $totalRating/count($reviews);

        if($accountInfo[0]->accountType == 'brand') {
            $brandInfo = BrandInfo::where('brand_id', '=', $accountInfo[0]->brand_id)->get();
            $brandInfo[0]->rating = $totalRating;
            $brandInfo[0]->reviews = count($reviews);
        }
        if($accountInfo[0]->accountType == 'influencer') {
            $influencerInfo = InfluencerInfo::where('influencer_id', '=', $accountInfo[0]->influencer_id)->get();
            $influencerInfo[0]->rating = $totalRating;
            $influencerInfo[0]->reviews = count($reviews);
        }

        

        return redirect()->route('home');
    }
}
