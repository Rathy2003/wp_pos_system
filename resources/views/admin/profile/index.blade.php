@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Profile</h1>
            <p class="text-muted">Update your profile information</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">User Profile</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3 text-center">
                    <div id="imagePreview" class="mb-2"></div>
                    
                        <div id="profilePreview" class="mb-2">
                            @if($user->profile)
                                <img id="profile-image" src="{{ asset('profile/'.$user->profile) }}" alt="Current Profile Picture" class="img-thumbnail rounded-circle" style="width: 156px; height: 156px; object-fit: cover; cursor: pointer;" onclick="document.getElementById('profile_picture').click();">
                            @else
                                <img id="profile-image" src="{{ asset('no-image.png') }}" alt="Current Profile Picture" class="img-thumbnail rounded-circle" style="width: 156px; height: 156px; object-fit: cover; cursor: pointer;" onclick="document.getElementById('profile_picture').click();">
                            @endif
                        </div>
               
                    <button type="button" id="profilePreview" class="btn btn-outline-primary" onclick="document.getElementById('profile_picture').click();">
                        Change Profile
                    </button>
                    
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*" style="display: none;">
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('profile_picture').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                document.querySelector("#profile-image").src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection 