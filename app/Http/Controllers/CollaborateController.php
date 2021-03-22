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
use Illuminate\Support\Facades\Validator;

class CollaborateController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index($user_id) {
        $user = new User();
        $accountInfo = $user->getAccountInfoByUserID(Auth::user()->id);
        
        $influencerInfo = $user->getAccountInfoByUserID($user_id);

        return view('collaborate', [
            'page' => 4,
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
        ];

        $message = [
            'title.required' => 'You have to enter the title of your project!',
            'title.max' => 'Your title is too long',
            'detail.regex' => 'You can enter only letters and numbers!',
            'detail.required' => 'You have to enter the project details',
            'price.required' => 'You have to enter the budget!'
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


        return redirect('request');
    }
}
