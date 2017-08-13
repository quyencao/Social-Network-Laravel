<?php

namespace App\Http\Controllers;

use App\Notifications\NewFriendRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipsController extends Controller
{
    public function check($id) {
        if(Auth::user()->is_friends_with($id) === 1) {
            return response()->json(['status' => 'friend']);
        }

        if(Auth::user()->has_pending_friend_request_sent_to($id) === 1) {
            return response()->json(['status' => 'waiting']);
        }

        if(Auth::user()->has_pending_requests_from($id) == 1) {
            return response()->json(['status' => 'pending']);
        }

        return response()->json(['status' => 0]);
    }

    public function add($id) {
        $res = Auth::user()->add_friend($id);

        // Send notification to user receive that request
        User::find($id)->notify(new NewFriendRequest(Auth::user()));

        return $res;
    }

    public function accept($id) {
        return Auth::user()->accept_friend($id);
    }
}
