<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserInbox;
use App\Models\UserRequest;
use App\Models\UserTask;

class CheckUnread
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $unread = UserInbox::where('user_id', '=', Auth::user()->id)
                ->get();
        $unread->inbox = count($unread);
        
        $unreadrequests = UserRequest::where('user_id', '=', Auth::user()->id)
                ->get();
        $unread->requests = count($unreadrequests);

        $unreadTask = UserTask::where('user_id', '=', Auth::user()->id)
                ->get();
        $unread->task = count($unreadTask);

        $request->attributes->add([
            'unread' => $unread,
        ]);

        return $next($request);
    }
}
