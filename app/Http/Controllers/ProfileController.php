<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index($slug) {
        $user = User::where('slug', $slug)->first();
        return view('profiles.profile', compact('user'));
    }

    public function edit() {
        $profile = Auth::user()->profile;
        return view('profiles.edit', compact('profile'));
    }

    public function update(Request $request) {
        $this->validate($request, [
           'location' => 'required',
           'about' => 'required|max:255'
        ]);

        Auth::user()->profile->update([
            'location' => $request->location,
            'about' => $request->about
        ]);

        return redirect()->route('profile', ['slug' => Auth::user()->slug])->with('Profile updated successfully!!!');
    }
}
