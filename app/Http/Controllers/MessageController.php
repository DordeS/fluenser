<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Pusher;
use Illuminate\Http\Request;
use App\Http\Resources\Inbox as InboxResource;
use App\Http\Resources\InboxCollection;
use App\Models\Inboxes;
use App\Models\InboxInfo;
use App\Models\Requests;
use App\Models\RequestInfo;
use App\Models\RequestChat;
use App\Models\RequestImg;
use App\Models\User;
use App\Models\UserInbox;
use App\Models\UserRequest;
use App\Models\UserTask;

class MessageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);

        $unread = $request->get('unread');

        return view('message', [
            'page' => 2,
            'unread' => $unread,
            'accountInfo' => $accountInfo[0]
        ]);
    }

    public function inbox() {
        $user_id = Auth::user()->id;

        $user = new User();
        $accountInfo = $user->getAccountInfoByUserID($user_id);

        $inboxes = new Inboxes();
        $inboxesInfo = $inboxes->where('user1_id', $user_id)
                ->orWhere('user2_id', $user_id)
                ->orderBy('updated_at', 'desc')
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

                $inboxContent = InboxInfo::where('inbox_id', $inboxInfo->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(1)
                            ->get();
                $inboxesInfo[$i]->inboxContent = $inboxContent;
                $inboxesInfo[$i]->contactID = $contact_id;

                $userInbox = UserInbox::where("inbox_id", '=', $inboxInfo->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->get();

                if(count($userInbox) > 0) {
                    $inboxesInfo[$i]->unread = true;
                } else {
                    $inboxesInfo[$i]->unread = false;
                }

                $i ++;
            }
        }
        
        return response()->json([
            'accountInfo' => $accountInfo[0],
            'data' => $inboxesInfo,
            'user_id' => $user_id,
        ]);
    }

    public function requests() {
        $user_id = Auth::user()->id;
        $account = new User();
        $influencerInfo = $account->getAccountInfoByUserID($user_id);

        $requests = new Requests();
        $requestsInfo = $requests->where('receive_id', $user_id)
                ->orWhere('send_id', $user_id)
                ->orderBy('updated_at', 'desc')
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

                $userRequest = UserRequest::where('request_id', '=', $requestInfo->id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->get();
                if(count($userRequest) > 0)
                    $requestsInfo[$i]->unread = true;
                else
                    $requestsInfo[$i]->unread = false;

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
        $inboxes = new Inboxes;
        $inbox = $inboxes->where('id', $inbox_id)->get();
        $contactID = ($inbox[0]->user1_id == Auth::user()->id) ? $inbox[0]->user2_id : $inbox[0]->user1_id;
        $users = new User();
        $contactInfo = $users->getAccountInfoByUserID($contactID);

        return response()->json([
            'data' => $chatInfo,
            'contactInfo' => $contactInfo,
            'contactID' => $contactID,
        ]);
    }

    public function receiveMessage($inbox_id, $message) {
        $inboxes = new Inboxes;
        $inbox = $inboxes->find($inbox_id);
        $receive_id = ($inbox->user1_id == Auth::user()->id) ? $inbox->user2_id : $inbox->user1_id;
        $inbox->user1_id = Auth::user()->id;
        $inbox->user2_id = $receive_id;
        $inbox->save();

        $inboxInfo = new InboxInfo;
        $inboxInfo->inbox_id = $inbox_id;
        $inboxInfo->send_id = Auth::user()->id;
        $inboxInfo->receive_id = $receive_id;
        $inboxInfo->content =  str_replace('‏‏‎ ‎', '?', $message);
        $inboxInfo->upload = 'none';
        $inboxInfo->save();

        $pusher = new Pusher\Pusher('da7cd3b12e18c9e2e461', '566ee6622fcab95b7709', '1168466', array('cluster' => 'eu'));

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'chat',
            'inboxInfo' => $inboxInfo
        ]);

        $userInbox = UserInbox::where('inbox_id', '=', $inbox_id)
                ->where('user_id', '=', Auth::user()->id)
                ->get();
        if(count($userInbox) == 0) {
            $userInbox = new UserInbox;
            $userInbox->inbox_id = $inbox_id;
            $userInbox->user_id = $receive_id;
            $userInbox->isRead = 0;
            $userInbox->save();
        }

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'newInboxChat',
            'inboxInfo' => $inboxInfo,
        ]);

        return response()->json([
            'data' => true,
        ]);
    }

    public function requestDetaliShow($request_id) {
        $requests = new Requests;
        $request = $requests->find($request_id);
        $send_id = ($request->send_id == Auth::user()->id)?$request->receive_id:$request->send_id;

        $user = new User();
        $contactInfo = $user->getAccountInfoByUserID($send_id)[0];
        $accountInfo = $user->getAccountInfoByUserID(Auth::user()->id)[0];

        $requestInfos = new RequestInfo();
        $requestInfo = $requestInfos->getRequestInfoByID($request_id);

        $requestChat = new RequestChat();
        $requestChats = $requestChat->getRequestChatInfo($request_id, $send_id, Auth::user()->id);

        // echo $accountInfo.'<br>'.$contactInfo;

        return response()->json([
            'accountInfo' => $accountInfo,
            'contactInfo' => $contactInfo,
            'requestInfo' => $requestInfo,
            'requestChats' => $requestChats,
        ]);
    }

    public function checkInbox($user1_id, $user2_id) {
        $inboxes = new Inboxes();
        $inbox = $inboxes->checkInbox($user1_id, $user2_id);

        return response()->json([
            'inbox_id' => $inbox->id,
        ]);
    }

    public function updateRequest($request_id, $price, $unit) {
        $request = RequestInfo::where('request_id', '=', $request_id)->get();

        $request = RequestInfo::find($request[0]->id);
        $request->amount = $price;
        $request->unit = $unit;
        $request->save();

        return response()->json([
            'status' => 200,
        ]);
    }

    public function saveRequestChat($request_id, $send_id, $receive_id, $message) {
        $requestChat = new RequestChat;
        $requestChat->request_id = $request_id;
        $requestChat->send_id = $send_id;
        $requestChat->receive_id = $receive_id;
        $requestChat->content = str_replace('‏‏‎ ‎', '?', $message);
        $requestChat->upload = 'none';
        $requestChat->save();

        echo $requestChat;

        $pusher = new Pusher\Pusher('da7cd3b12e18c9e2e461', '566ee6622fcab95b7709', '1168466', array('cluster' => 'eu'));

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'requestChat',
            'requestChat' => $requestChat,
        ]);

        $userRequest = UserRequest::where('request_id', '=', $request_id)
                ->where('user_id', '=', $receive_id)
                ->get();
        if(count($userRequest) == 0) {
            $userRequest = new UserRequest;
            $userRequest->request_id = $request_id;
            $userRequest->user_id = $receive_id;
            $userRequest->isRead = 0;
            $userRequest->save();
        }

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'newRequestChat',
            'requestChat' => $requestChat,
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    public function readItem($item, $id) {
        switch ($item) {
            case 'request':
                $item = UserRequest::where('request_id', '=', $id);
                break;

            case 'inbox':
                $item = UserInbox::where('inbox_id', '=', $id);
                break;

            case 'tast':
                $item = UserTask::where('task_id', '=', $id);
                break;
            
            default:
                break;
        }

        $item = $item->where('user_id', '=', Auth::user()->id)->get();
        if(count($item) > 0) $item[0]->delete();
    
        return response()->json([
            'status' => 200,
        ]);
    }
}