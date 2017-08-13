<?php

namespace App\Http\Controllers;

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
        // Send notification, broadcast ...
        return Auth::user()->add_friend($id);
    }
}
