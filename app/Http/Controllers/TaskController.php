<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Requests;
use App\Models\Influencers;
use App\Models\User;
use App\Models\Category;
use App\Models\Review;
use App\Models\Countries;
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
        $rule = [];

        $message = [];

        $validator = Validator::make($input, $rule, $message);

        if($validator->fails()) {
            return redirect('search')
                ->withErrors($validator)
                ->withInput($input);
        }

        $category = (isset($input['category'])) ? $input['category'] : 'Any';
        $location = (isset($input['country'])) ? $input['country'] : 'Any';
        $name = (isset($input['name'])) ? $input['name'] : '';
        $keyword = (isset($input['keyword'])) ? $input['keyword'] : '';
        $perpage = (isset($input['perpage'])) ? $input['perpage'] : 10;

        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);

        // Get categories
        $allCategories = Category::all();

        // Get countries
        $countries = Countries::all();

        // search influencers

        $influencers = new Influencers();
        $foundInfluencers = $influencers->findInfluencers($category, $location, $name, $keyword, $perpage);

        return view('search', [
            'selectedCategory' => $category,
            'selectedLocation' => $location,
            'selectedName' => $name,
            'selectedKeyword' => $keyword,
            'page' => 4,
            'accountInfo' => $accountInfo[0],
            'categories' => $allCategories,
            'countries' => $countries,
            'influencers' => $foundInfluencers,
        ]);
    }
}