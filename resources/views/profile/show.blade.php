@extends('layouts.app')

@section('title', 'My Profile - LimaSync')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">My Profile</h1>
    </div>
</div>

<div class="row">
    <!-- Profile Picture Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-camera"></i> Profile Picture</h5>
            </div>
            <div class="card-body text-center">
                {{-- Avatar --}}
                <div class="mb-3">
                    @if($user->profile_picture_url)
                        <img src="{{ $user->profile_picture_url }}" 
                            alt="{{ $user->full_name }}"
                            class="rounded-circle img-thumbnail"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                            style="width: 150px; height: 150px; font-size: 3rem; font-weight: 600;">
                            {{ $user->initials }}
                        </div>
                    @endif
                </div>

                <h5>{{ $user->full_name ?? $user->name }}</h5>
                <p class="text-muted mb-2">
                    <span class="badge bg-secondary">{{ ucfirst($user->role->name ?? 'N/A') }}</span>
                </p>
                <p class="text-muted mb-3">
                    <small>{{ $user->email }}</small>
                </p>

                {{-- Upload Form --}}
                <form action="{{ route('profile.picture.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <input type="file" 
                               class="form-control @error('profile_picture') is-invalid @enderror" 
                               id="profile_picture" 
                               name="profile_picture" 
                               accept=".jpg,.jpeg,.png">
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">
                            JPG or PNG, max 2MB
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-upload"></i> Upload Picture
                    </button>
                </form>

                {{-- Remove Button --}}
                @if($user->profile_picture)
                    <form action="{{ route('profile.picture.remove') }}" method="POST" 
                          onsubmit="return confirm('Remove profile picture?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash"></i> Remove Picture
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Account Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-info-circle"></i> Account Info</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <small class="text-muted d-block">Username</small>
                        <strong>{{ $user->username }}</strong>
                    </li>
                    <li class="mb-2">
                        <small class="text-muted d-block">Role</small>
                        <strong>{{ ucfirst($user->role->name ?? 'N/A') }}</strong>
                    </li>
                    <li class="mb-2">
                        <small class="text-muted d-block">Account Created</small>
                        <strong>{{ $user->created_at->format('d M Y') }}</strong>
                    </li>
                    <li>
                        <small class="text-muted d-block">Last Login</small>
                        <strong>
                            @if($user->last_login)
                                {{ $user->last_login->diffForHumans() }}
                                <br>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ $user->last_login->format('d M Y H:i') }}
                                </small>
                            @else
                                Never
                            @endif
                        </strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-8">
        <!-- Personal Details -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-user-edit"></i> Personal Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="{{ old('full_name', $user->full_name) }}" 
                                   required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="e.g., 012-3456789">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" 
                                   class="form-control @error('department') is-invalid @enderror" 
                                   id="department" 
                                   name="department" 
                                   value="{{ old('department', $user->department) }}">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Max 500 characters</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-lock"></i> Change Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Min 8 characters</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview profile picture before upload
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate size (2MB = 2 * 1024 * 1024 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('File is too large. Maximum size is 2MB.');
                e.target.value = '';
                return;
            }
            
            // Validate type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Only JPG and PNG files are allowed.');
                e.target.value = '';
                return;
            }
        }
    });
</script>
@endpush
@endsection