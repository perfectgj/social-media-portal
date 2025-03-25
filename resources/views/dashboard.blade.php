@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-center">Welcome, {{ Auth::user()->full_name }}</h2>

        <div class="row">
            <!-- Friends List -->
            <div class="col-md-6">
                <h4>Your Friends</h4>
                <ul class="list-group">
                    @php
                        $friendList = [];
                    @endphp

                    @foreach ($friends as $friend)
                        @php
                            $friendUser = $friend->user_id == Auth::id() ? $friend->friend : $friend->user;
                        @endphp

                        @if (!in_array($friendUser->id, $friendList))
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <img src="{{ $friendUser->profile_picture ? asset('storage/' . $friendUser->profile_picture) : asset('images/default.jpg') }}"
                                    width="40" height="40" class="rounded-circle">
                                {{ $friendUser->full_name }}
                                <form action="{{ route('remove.friend', $friendUser->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Remove Friend</button>
                                </form>
                            </li>

                            @php
                                $friendList[] = $friendUser->id; // Prevent duplicate entry
                            @endphp
                        @endif
                    @endforeach
                </ul>

                <!-- Pagination with Bootstrap Styling -->
                <div class="mt-3 d-flex justify-content-center">
                    {{ $friends->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- Add Friends Section -->
            <div class="col-md-6">
                <h4>Add Friends</h4>
                <input type="text" id="search-box" class="form-control" placeholder="Search friends...">
                <ul id="search-results" class="list-group mt-2"></ul>
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;

        document.getElementById('search-box').addEventListener('input', function() {
            clearTimeout(searchTimeout); // Clear previous timeout

            let query = this.value;
            let results = document.getElementById("search-results");

            if (query.length < 3) {
                results.innerHTML = "";
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/search-users?search=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        results.innerHTML = '';

                        if (data.users.length === 0) {
                            results.innerHTML =
                                '<li class="list-group-item text-center text-danger">No results found</li>';
                            return;
                        }

                        data.users.forEach(user => {
                            let buttonText = user.friend_status ?? 'Add Friend';
                            let buttonClass = "btn-primary";

                            if (user.friend_status === "Request Sent") {
                                buttonClass = "btn-warning";
                            } else if (user.friend_status === "Already Friends") {
                                buttonClass = "btn-success";
                            } else if (user.friend_status === "Add Friend") {
                                buttonClass = "btn-primary";
                            }

                            results.innerHTML += `
                                <li class="list-group-item d-flex justify-content-between">
                                    <img src="/storage/${user.profile_picture ?? 'default.jpg'}" width="30" height="30" class="rounded-circle">
                                    ${user.full_name}
                                    <a href="/add-friend/${user.id}" class="btn btn-sm ${buttonClass}">${buttonText}</a>
                                </li>
                            `;
                        });
                    });
            }, 500);
        });
    </script>
@endsection
