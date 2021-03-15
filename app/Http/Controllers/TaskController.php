<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Requests;
use App\Models\Influencers;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Request;

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
        $input = $request::all();
        $category = (isset($input['category'])) ? $input['category'] : 'Category';
        $country = (isset($input['country'])) ? $input['country'] : 'Location';
        $name = (isset($input['name'])) ? $input['name'] : '';
        $keyword = (isset($input['keyword'])) ? $input['keyword'] : '';

        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);

        // Get categories
        $categories = Category::all();

        // get countries
        $response = Http::get('https://restcountries.eu/rest/v2/all?fields=name');
        $countries = $response->body();
        $countries = json_decode($countries);

        // search influencers
        $influencers = new Influencers();
        $foundInfluencers = $influencers->findInfluencers($category, $country, $name, $keyword);
        
        return view('search', [
            'page' => 4,
            'accountInfo' => $accountInfo[0],
            'categories' => $categories,
            'countries' => $countries,
            'influencers' => $foundInfluencers,
        ]);
    }
}