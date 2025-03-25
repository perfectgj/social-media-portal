<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Social Media Portal</a>

            @auth
                <div class="dropdown">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="notificationDropdown"
                        data-bs-toggle="dropdown">
                        Notifications <span class="badge bg-danger" id="notificationCount">0</span>
                    </button>
                    <ul class="dropdown-menu" id="notificationList"></ul>
                </div>
            @endauth

            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login.show') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register.show') }}">Register</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Logout</a></li>
                @endguest
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var dropdownTrigger = document.getElementById("notificationDropdown");
            var dropdown = new bootstrap.Dropdown(dropdownTrigger);

            fetch("{{ route('notifications') }}")
                .then(response => response.json())
                .then(data => {
                    let notificationDropdown = document.getElementById("notificationList");
                    let notificationCount = document.getElementById("notificationCount");

                    notificationDropdown.innerHTML = "";
                    notificationCount.innerText = data.length;

                    if (data.length === 0) {
                        notificationDropdown.innerHTML = '<li class="dropdown-item">No new notifications</li>';
                    } else {
                        data.forEach(notification => {
                            let listItem = document.createElement("li");
                            listItem.classList.add("dropdown-item");

                            listItem.innerHTML = `
                                <span><strong>${notification.sender.full_name}</strong> sent you a friend request.</span>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-success accept-btn" data-id="${notification.id}">Accept</button>
                                    <button class="btn btn-sm btn-danger reject-btn" data-id="${notification.id}">Reject</button>
                                </div>
                            `;

                            notificationDropdown.appendChild(listItem);
                        });

                        // Attach event listeners to buttons AFTER the elements are added to the DOM
                        document.querySelectorAll(".accept-btn").forEach(button => {
                            button.addEventListener("click", function() {
                                respondNotification(this.getAttribute("data-id"), "accepted");
                            });
                        });

                        document.querySelectorAll(".reject-btn").forEach(button => {
                            button.addEventListener("click", function() {
                                respondNotification(this.getAttribute("data-id"), "rejected");
                            });
                        });
                    }
                });
        });

        function respondNotification(id, status) {
            fetch(`/notifications/respond/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
    </script>


</body>

</html>
