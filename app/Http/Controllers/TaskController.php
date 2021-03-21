<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Requests;
use App\Models\Influencers;
use App\Models\User;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $user = new User();
        $requests = new Requests();
        $user_id = Auth::user()->id;
        if($user->checkIfInfluencer($user_id)) {
            $tasks = $requests->getInfluencerTasksByID($user_id);
        } else {
            $tasks = $requests->getBrandTasksByID($user_id);
        }
        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);
        return view('task', [
            'page' => 3,
            'tasks' => $tasks,
            'accountInfo' => $accountInfo[0],
        ]);
    }

    public function search(Request $request) {
        $input = $request->all();
        $rule = [
            'name' => 'regex:/^[A-Za-z]+$/',
            'category' => 'regex:/^[a-zA-Z,]+$/',
        ];

        $message = [
            'name.regex' => 'You can enter only letters',
            'category.regex' => 'Errors',
        ];

        $validator = Validator::make($input, $rule, $message);

        if($validator->fails()) {
            return redirect('search')
                ->withErrors($validator)
                ->withInput($input);
        }

        $category = (isset($input['category'])) ? $input['category'] : 'Category';
        $name = (isset($input['name'])) ? $input['name'] : '';

        $categories = explode(',', $category);

        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);

        // Get categories
        $allCategories = Category::all();

        // Get countries
        $response = Http::get('https://restcountries.eu/rest/v2/all?fields=name');
        $countries = json_decode($response->body());

        // search influencers
        $influencers = new Influencers();
        $foundInfluencers = $influencers->findInfluencers($categories, $name);

        return view('search', [
            'page' => 4,
            'accountInfo' => $accountInfo[0],
            'categories' => $allCategories,
            'countries' => $countries,
            'influencers' => $foundInfluencers,
        ]);
    }
}