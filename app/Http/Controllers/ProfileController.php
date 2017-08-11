<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    public function index($slug) {
        $user = User::where('slug', $slug)->first();
        return view('profiles.profile', compact('user'));
    }
}
