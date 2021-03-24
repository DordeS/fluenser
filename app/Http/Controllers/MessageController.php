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

class MessageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $account = new User();
        $accountInfo = $account->getAccountInfoByUserID(Auth::user()->id);
        return view('message', [
            'page' => 2,
            'accountInfo' => $accountInfo[0]
            ]);
    }

    public function inbox() {
        $user_id = Auth::user()->id;

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
        $inboxInfo->content =  $message;
        $inboxInfo->upload = 'none';
        $inboxInfo->save();

        $pusher = new Pusher\Pusher('da7cd3b12e18c9e2e461', '566ee6622fcab95b7709', '1168466', array('cluster' => 'eu'));

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'chat',
            'inboxInfo' => $inboxInfo
        ]);

        return response()->json([
            'data' => true,
        ]);
    }

    public function requestDetaliShow($request_id) {
        $requests = new Requests;
        $request = $requests->find($request_id);
        $send_id = $request->send_id;

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
        $requestChat->content = $message;
        $requestChat->upload = 'none';
        $requestChat->save();

        $pusher = new Pusher\Pusher('da7cd3b12e18c9e2e461', '566ee6622fcab95b7709', '1168466', array('cluster' => 'eu'));

        $pusher->trigger('fluenser-channel', 'fluenser-event', [
            'trigger' => 'requestChat',
            'requestChat' => $requestChat,
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    public function acceptRequest($request_id) {
        $request = RequestInfo::where('request_id', '=', $request_id)->get();

        $request = RequestInfo::find($request[0]->id);
        $request->accepted = 1;
        $request->save();

        return response()->json([
            'status' => 200,
        ]);

    }

    public function declineRequest($request_id) {
        echo $request_id;
        // delete form the request_info table
        $requestInfo = RequestInfo::where('request_id', '=', $request_id)->get();
        if(count($requestInfo) > 0) $requestInfo[0]->delete();
        
        // delete form the request_images table
        $requestImages = RequestImg::where('request_id', '=', $request_id)->get();
        if(count($requestImages) > 0) {
            foreach ($requestImages as $requestImage) {
                $requestImage->delete();
            }
        }

        // delete from the request_chat table
        $requestChats = RequestChat::where('request_id', '=', $request_id)->get();
        if(count($requestChats) > 0) {
            foreach ($requestChats as $requestChat) {
                $requestChat->delete();
            }
        }
       
        // delete from the request table
        $request = Requests::find($request_id);
        $request->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}