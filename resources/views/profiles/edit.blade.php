@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit your profile</div>

                    <div class="panel-body">
                        <form action="{{ route('profile.update') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input class="form-control" id="location" type="text" name="location" value="{{ $profile->location }}" placeholder="Ha Noi..." required>
                            </div>
                            <div class="form-group">
                                <label for="about">About me</label>
                                <textarea class="form-control" name="about" id="about" cols="30" rows="10" placeholder="Say something about you" required>{{ $profile->about }}</textarea>
                            </div>
                            <div class="form-group">
                                <p class="text-center">
                                    <button class="btn btn-primary btn-lg" type="submit">Save your info</button>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
