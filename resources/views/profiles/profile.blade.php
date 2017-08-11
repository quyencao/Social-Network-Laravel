@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align: center">
                    {{ $user->name }}'s Profile
                </div>
                <div class="panel-body" style="text-align: center">
                    <img src="{{ Storage::url($user->avatar) }}" width="150px" height="150px" style="border-radius: 50%" alt="">
                    <p class="text-center">
                        @if(Auth::id() == $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-lg btn-info">Edit your profile</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection