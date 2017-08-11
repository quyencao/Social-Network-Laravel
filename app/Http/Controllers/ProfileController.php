<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index($slug) {
        $user = User::with('profile')->where('slug', $slug)->first();
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

        if($request->hasFile('avatar')) {
            Auth::user()->update([
                'avatar' => $request->avatar->store('public/avatars')
            ]);
        }

        return redirect()->route('profile', ['slug' => Auth::user()->slug])->with('success', 'Profile updated successfully!!!');
    }
}
