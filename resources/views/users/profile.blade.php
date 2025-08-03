@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 900px;">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <!-- Card Header -->
            <div class="card-header bg-primary text-white px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle fa-lg me-3"></i>
                        <h4 class="mb-0">User Details</h4>
                    </div>
                    {{-- <a href="{{ route('') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a> --}}
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs px-4 pt-3" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                            type="button" role="tab">
                            <i class="fas fa-user me-2"></i> Profile
                        </button>
                    </li>
                    @if (auth()->user()->id == $user->id || auth()->user()->hasRole('admin'))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password"
                                type="button" role="tab">
                                <i class="fas fa-lock me-2"></i> Password
                            </button>
                        </li>
                    @endif
                </ul>

                <!-- Tab Content -->
                <div class="tab-content p-4">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="row g-0">
                            <!-- User Avatar Column -->
                            <div class="col-md-4 p-4 text-center border-end bg-light">
                                <div class="d-flex flex-column align-items-center h-100">
                                    <!-- Avatar -->
                                    <div class="avatar-wrapper mx-auto mb-3 position-relative">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#avatarModal"
                                            class="avatar-link">
                                            @if ($user->hasMedia('profile_pic'))
                                                <img src="{{ $user->getFirstMediaUrl('profile_pic') }}"
                                                    class="avatar img-fluid rounded-circle shadow-sm" alt="User Avatar">
                                                <div class="avatar-overlay rounded-circle">
                                                    <i class="fas fa-camera text-white"></i>
                                                </div>
                                            @else
                                                <div
                                                    class="avatar-default d-flex align-items-center justify-content-center bg-light rounded-circle shadow-sm">
                                                    <i class="fas fa-user text-primary fa-3x"></i>
                                                </div>
                                                <div class="avatar-overlay rounded-circle">
                                                    <i class="fas fa-camera text-white"></i>
                                                </div>
                                            @endif
                                        </a>
                                    </div>

                                    <div class="mt-auto w-100">
                                        <h5 class="text-dark fw-bold mb-1">{{ $user->name }}</h5>
                                        <p class="text-muted small mb-3">{{ $user->email }}</p>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                            <i class="fas fa-circle me-1 small"></i> Active
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- User Details Column -->
                            <div class="col-md-8 p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="35%" class="text-muted ps-0 fw-normal">Phone</th>
                                                <td class="fw-medium">{{ $user->phone ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted ps-0 fw-normal">Birth Date</th>
                                                <td class="fw-medium">
                                                    {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d M Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted ps-0 fw-normal">Role</th>
                                                <td>
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                                                        {{ $user->roles->first()?->name ?? 'No Role' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted ps-0 fw-normal">Joined</th>
                                                <td class="fw-medium">{{ $user->created_at->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted ps-0 fw-normal">Last Login</th>
                                                <td class="fw-medium">
                                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never logged in' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                                    @can('users_update')
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="btn btn-outline-primary px-4 rounded-pill">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a>
                                    @endcan
                                    @can('users_delete')
                                        <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger px-4 rounded-pill"
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Update Tab -->
                    @if (auth()->user()->id == $user->id || auth()->user()->hasRole('admin'))
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card border-0 shadow-none">
                                        <div class="card-body">
                                            <h3 class="h5 mb-4 text-center">Change Password</h3>

                                            <form method="POST" action="{{ route('users.updatePassword', $user->id) }}">
                                                @csrf
                                                @method('PUT')

                                                @if (auth()->user()->id == $user->id)
                                                    <div class="mb-3">
                                                        <label for="current_password" class="form-label">Current
                                                            Password</label>
                                                        <div class="input-group">
                                                            <input type="password"
                                                                class="form-control @error('current_password') is-invalid @enderror"
                                                                id="current_password" name="current_password" required>
                                                            <button class="btn btn-outline-secondary toggle-password"
                                                                type="button">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            @error('current_password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="mb-3">
                                                    <label for="new_password" class="form-label">New Password</label>
                                                    <div class="input-group">
                                                        <input type="password"
                                                            class="form-control @error('new_password') is-invalid @enderror"
                                                            id="new_password" name="new_password" required>
                                                        <button class="btn btn-outline-secondary toggle-password"
                                                            type="button">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        @error('new_password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-text">Minimum 8 characters</div>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="new_password_confirmation" class="form-label">Confirm
                                                        Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control"
                                                            id="new_password_confirmation"
                                                            name="new_password_confirmation" required>
                                                        <button class="btn btn-outline-secondary toggle-password"
                                                            type="button">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-save me-2"></i> Update Password
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar Modal -->
    <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold">Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="avatar-preview mx-auto" style="width: 180px; height: 180px;">
                            @if ($user->hasMedia('profile_pic'))
                                <img src="{{ $user->getFirstMediaUrl('profile_pic') }}"
                                    class="img-fluid rounded-circle shadow" alt="Current Profile Picture">
                            @else
                                <div
                                    class="avatar-default d-flex align-items-center justify-content-center bg-light rounded-circle h-100">
                                    <i class="fas fa-user text-primary fa-3x"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    @can('users_update')
                        <form action="{{ route('users.pic_upload', $user->id) }}" method="POST"
                            enctype="multipart/form-data" class="px-3">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <input type="file" name="image" id="avatarUpload" accept="image/*"
                                    class="form-control d-none" required>
                                <label for="avatarUpload" class="btn btn-primary rounded-pill px-4 py-2">
                                    <i class="fas fa-cloud-upload-alt me-2"></i> Choose Photo
                                </label>
                                <div id="fileName" class="small text-muted mt-2"></div>
                            </div>
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2" id="uploadBtn" disabled>
                                <i class="fas fa-check me-2"></i> Upload
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-wrapper {
            width: 140px;
            height: 140px;
            position: relative;
        }

        .avatar-link {
            display: block;
            position: relative;
        }

        .avatar,
        .avatar-default {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 4px solid rgba(var(--bs-primary-rgb), 0.1);
            transition: all 0.3s ease;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .avatar-link:hover .avatar-overlay {
            opacity: 1;
        }

        .table-borderless tbody tr {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table-borderless tbody tr:last-child {
            border-bottom: none;
        }

        @media (max-width: 767.98px) {
            .col-md-4.border-end {
                border-right: none !important;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                padding-bottom: 2rem;
            }
        }

        .avatar-preview {
            border: 1px dashed #dee2e6;
            border-radius: 50%;
            overflow: hidden;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            border-bottom: 3px solid var(--bs-primary);
        }

        .toggle-password {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Avatar upload
            document.getElementById('avatarUpload')?.addEventListener('change', function(e) {
                const fileName = document.getElementById('fileName');
                const uploadBtn = document.getElementById('uploadBtn');
                const preview = document.querySelector('.avatar-preview');

                if (this.files.length > 0) {
                    fileName.textContent = this.files[0].name;
                    uploadBtn.disabled = false;

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.innerHTML =
                            `<img src="${event.target.result}" class="img-fluid rounded-circle shadow" alt="Preview">`;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Password toggle
            document.querySelectorAll('.toggle-password').forEach(function(button) {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
                    }
                });
            });

            // Switch to password tab if errors
            @if ($errors->has('current_password') || $errors->has('new_password'))
                const passwordTab = new bootstrap.Tab(document.getElementById('password-tab'));
                passwordTab.show();
            @endif
        });
    </script>
@endsection
