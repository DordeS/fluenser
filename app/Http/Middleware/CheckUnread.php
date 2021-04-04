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
        $unread = UserInbox::where('user_id', '=', Auth::user()->id)->where('isRead', '=', 0)->get();
        $count = count($unread);
        $unread = UserRequest::where('user_id', '=', Auth::user()->id)->where('isRead', '=', 0)->get();
        $unread->inbox = $count + count($unread);

        $unreadTask = UserTask::where('user_id', '=', Auth::user()->id)->where('isRead', '=', 0)->get();
        $unread->task = count($unreadTask);

        $request->attributes->add([
            'unread' => $unread,
        ]);

        return $next($request);
    }
}
