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

    public function index() {
        return view('message');
    }

    public function inbox() {
        $user_id = Auth::user()->id;

        $inboxes = new Inboxes();
        $inboxesInfo = $inboxes->where('user1_id', $user_id)
                ->orWhere('user2_id', $user_id)
                ->orderBy('updated_at')
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

                $inboxContent = InboxInfo::where('id', $inboxInfo->id)
                            ->orderBy('created_at')
                            ->limit(1)
                            ->get();
                $inboxesInfo[$i]->inboxContent = $inboxContent;
                $inboxesInfo[$i]->contactID = $contact_id;

                $i ++;
            }
        }
        
        return response()->json([
            'data' => $inboxesInfo,
            'user_id' => $user_id,
        ]);
    }

    public function requests() {
        $user_id = Auth::user()->id;

        $requests = new Requests();
        $requestsInfo = $requests->where('receive_id', $user_id)
                ->orderBy('updated_at')
                ->get();
        if(count($requestsInfo) > 0) {
            $i = 0;
            foreach($requestsInfo as $requestInfo){
                $contact_id = $requestInfo->send_id;
                $account = new User;
                $accountInfo = $account->getAccountInfoByUserID($contact_id);
                if($accountInfo != 'none'){
                    $requestsInfo[$i]->accountInfo = $accountInfo;
                }

                $temp = new RequestInfo();
                $requestContent = $temp->getRequestInfoByID($requestInfo->id);
                $requestsInfo[$i]->requestContent = $requestContent;
                $requestsInfo[$i]->contactID = $contact_id;

                $i ++;
            }
        }
        
        return response()->json([
            'data' => $requestsInfo,
            'user_id' => $user_id,
        ]);
    }

    public function chat($inbox_id) {
        $chat = new InboxInfo();
        $chatInfo = $chat->getChatInfo($inbox_id);
        
        return response()->json([
            'data' => $chatInfo
        ]);
    }
}
