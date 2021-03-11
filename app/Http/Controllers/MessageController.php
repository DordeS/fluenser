<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Resources\Inbox as InboxResource;
use App\Http\Resources\InboxCollection;
use App\Models\Inboxes;
use App\Models\InboxInfo;
use App\Models\Requests;
use App\Models\RequestInfo;
use App\Models\User;

class MessageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function inbox() {
        $user_id = Auth::user()->id;

        $inboxes = new Inboxes();
        $inboxesInfo = $inboxes->where('user1_id', $user_id)
                ->orWhere('user2_id', $user_id)
                ->get();
        if(count($inboxesInfo) > 0) {
            $i = 0;
            foreach($inboxesInfo as $inboxInfo){
                $contact_id = ($inboxInfo->user1_id == $user_id) ? $inboxInfo->user2_id : $inboxInfo->user1_id;
                $account = new User;
                $accountInfo = $account->getAccountInfoByUserID($contact_id);
                if($accountInfo != 'none'){
                    $inboxesInfo[$i]->accountInfo = $accountInfo;
                }
                $i ++;
            }
        }  
        
        return response()->json([
            'status'=> 200,
            'data' => $inboxesInfo,
        ]);

        // return view('inbox/message', [
        //     'data' => $inboxesInfo,
        // ]);
    }
}
