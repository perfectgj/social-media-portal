@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Friends</h2>
    <ul class="list-group">
        @foreach ($friends as $friend)
            <li class="list-group-item">
                {{ $friend->user_id == Auth::id() ? $friend->friend->full_name : $friend->user->full_name }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
