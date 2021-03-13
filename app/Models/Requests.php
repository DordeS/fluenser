<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Requests extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'send_id',
        'receive_id',
    ];

    public function getInfluencerTasksByID($user_id) {
        $tasks = DB::table('requests')
                ->where('requests.receive_id', '=', $user_id)
                ->join('request_info', 'requests.id', '=', 'request_info.request_id')
                ->where('request_info.accepted', '=', 1)
                ->get();
        return $tasks;
    }

    public function getBrandTasksByID($user_id) {
        $tasks = DB::table('requests')
                ->where('send_id', '=', $user_id)
                ->join('request_info', 'requests.id', '=', 'request_info.request_id')
                ->orderBy('accepted')
                ->get();
        return $tasks;
    }
}
