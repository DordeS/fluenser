<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Requests;
use App\Models\User;

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
}