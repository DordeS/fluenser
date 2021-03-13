<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InboxInfo extends Model
{
    use HasFactory;

    protected $table = 'inbox_info';

    protected $fillable = [
        'content',
        'upload',
    ];

    public function getChatInfo($inboxID) {
    
        $chatInfo = DB::table('inbox_info')
                ->where('inbox_id', '=', $inboxID)
                ->orderBy('created_at')
                ->get();

        $contactID = ($chatInfo[0]->send_id == Auth::user()->id)?       $chatInfo[0]->receive_id :
                $chatInfo[0]->send_id;
        
        $sendInfo = DB::table('users')
                ->where('id', '=', $contactID)
                ->select('name')
                ->get();
    
                
        $sendInfo[0]->chatInfo = $chatInfo;
        $sendInfo[0]->userID = Auth::user()->id;
        
        return $sendInfo[0];
    }
}