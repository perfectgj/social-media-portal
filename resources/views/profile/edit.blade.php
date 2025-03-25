@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Profile</h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth"
                    value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-control">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" value="{{ old('location', $user->location) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Interests (comma-separated)</label>
                <input type="text" name="interests" value="{{ old('interests', implode(',', $user->interests ?? [])) }}"
                    class="form-control">
            </div>

            <!-- Checkbox to Show/Hide Password Fields -->
            <div class="mb-3">
                <input type="checkbox" id="change_password_checkbox">
                <label for="change_password_checkbox">Change Password</label>
            </div>

            <div id="password_fields" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control">
                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default.jpg') }}"
                    width="100" height="100" class="mt-2 rounded-circle">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <script>
        document.getElementById("change_password_checkbox").addEventListener("change", function() {
            let passwordFields = document.getElementById("password_fields");
            passwordFields.style.display = this.checked ? "block" : "none";
        });
    </script>
@endsection
