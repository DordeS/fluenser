<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\InfluencerInfo;
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

    public function upload(Request $request)
    {
        $folderPath = public_path('img/task-image/');

        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $filename = uniqid() . '.jpg';
        $file = $folderPath . $filename;

        file_put_contents($file, $image_base64);

        return response()->json([
            'success'=>'success',
            'data' => $filename]
        );
    }

    public function saveRequest(Request $request) {
        $input = $request->all();

        $rule = [
            'title' => 'required|max:255',
            'detail' => 'required|regex:/^[A-Za-z0-9.,() ]+$/',
            'price' => 'required|regex:/^[0-9]+$/',
            'images' => 'required|mimes:jpg|max:20480'
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
            return redirect('collaborate')
                    ->withErrors($validator)
                    ->withInput();
        }


    }
}
