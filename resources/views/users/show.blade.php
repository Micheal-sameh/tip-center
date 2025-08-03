@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 900px;">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <!-- Card Header -->
            <div class="card-header bg-primary text-white px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle fa-lg me-3"></i>
                        <h4 class="mb-0">{{ __('trans.user_details') }}</h4>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
                    </a>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- User Avatar Column -->
                    <div class="col-md-4 p-4 text-center border-end bg-light">
                        <div class="d-flex flex-column align-items-center h-100">
                            <!-- Avatar with hover effect -->
                            <div class="avatar-wrapper mx-auto mb-3 position-relative">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#avatarModal" class="avatar-link">
                                    @if ($user->hasMedia('profile_pic'))
                                        <img src="{{ $user->getFirstMediaUrl('profile_pic') }}"
                                            class="avatar img-fluid rounded-circle shadow-sm" alt="User Avatar">
                                        <div class="avatar-overlay rounded-circle">
                                            <i class="fas fa-camera text-white"></i>
                                        </div>
                                    @else
                                        @php
                                            $logo = App\Models\Setting::where('name', 'logo')->first();
                                        @endphp
                                        <img src="{{ $logo?->getFirstMediaUrl('app_logo') }}"
                                            class="avatar img-fluid rounded-circle shadow-sm" alt="User Avatar">
                                        <div class="avatar-overlay rounded-circle">
                                            <i class="fas fa-camera text-white"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <div class="mt-auto w-100">
                                <h5 class="text-dark fw-bold mb-1">{{ $user->name }}</h5>
                                <p class="text-muted small mb-3">{{ $user->email }}</p>
                                @if ($user->status == 1)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-circle me-1 small"></i> {{ __('trans.active') }}
                                    </span>
                                @else
                                    <span
                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-normal">
                                        <i class="fas fa-circle me-1 small"></i> {{ __('trans.inactive') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- User Info Column -->
                    <div class="col-md-8 p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th width="35%" class="text-muted ps-0 fw-normal">{{ __('trans.phone') }}</th>
                                        <td class="fw-medium">{{ $user->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted ps-0 fw-normal">{{ __('trans.birth_date') }}</th>
                                        <td class="fw-medium">
                                            {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->translatedFormat('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted ps-0 fw-normal">{{ __('trans.role') }}</th>
                                        <td>
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                                                {{ $user->roles->first()?->name ?? __('trans.no_role') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted ps-0 fw-normal">{{ __('trans.joined') }}</th>
                                        <td class="fw-medium">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted ps-0 fw-normal">{{ __('trans.last_login') }}</th>
                                        <td class="fw-medium">
                                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : __('trans.never_logged_in') }}
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
                                    <i class="fas fa-edit me-2"></i> {{ __('trans.edit') }}
                                </a>
                            @endcan
                            @can('users_delete')
                                <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger px-4 rounded-pill"
                                        onclick="return confirm('{{ __('trans.delete_confirm') }}')">
                                        <i class="fas fa-trash me-2"></i> {{ __('trans.delete') }}
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar Modal -->
    <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold">{{ __('trans.profile_picture') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="avatar-preview mx-auto" style="width: 180px; height: 180px;">
                            @if ($user->hasMedia('profile_pic'))
                                <img src="{{ $user->getFirstMediaUrl('profile_pic') }}"
                                    class="img-fluid rounded-circle shadow" alt="User Avatar">
                            @else
                                @php
                                    $logo = App\Models\Setting::where('name', 'logo')->first();
                                @endphp
                                <img src="{{ $logo?->getFirstMediaUrl('app_logo') }}"
                                    class="avatar img-fluid rounded-circle shadow-sm" alt="User Avatar">
                                <div class="avatar-overlay rounded-circle">
                                    <i class="fas fa-camera text-white"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    @can('users_update')
                        <form action="{{ route('users.pic_upload', $user->id) }}" method="POST" enctype="multipart/form-data"
                            class="px-3">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <input type="file" name="image" id="avatarUpload" accept="image/*"
                                    class="form-control d-none" required>
                                <label for="avatarUpload" class="btn btn-primary rounded-pill px-4 py-2">
                                    <i class="fas fa-cloud-upload-alt me-2"></i> {{ __('trans.choose_photo') }}
                                </label>
                                <div id="fileName" class="small text-muted mt-2"></div>
                            </div>
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2" id="uploadBtn" disabled>
                                <i class="fas fa-check me-2"></i> {{ __('trans.upload') }}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Avatar Styles */
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

        .avatar-default {
            font-size: 3.5rem;
        }

        /* Table Styles */
        .table-borderless tbody tr {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table-borderless tbody tr:last-child {
            border-bottom: none;
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .col-md-4.border-end {
                border-right: none !important;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                padding-bottom: 2rem;
            }

            .avatar-wrapper {
                width: 120px;
                height: 120px;
            }

            .card-header h4 {
                font-size: 1.25rem;
            }
        }

        /* Modal Styles */
        .avatar-preview {
            border: 1px dashed #dee2e6;
            border-radius: 50%;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show selected file name
            document.getElementById('avatarUpload').addEventListener('change', function(e) {
                const fileName = document.getElementById('fileName');
                const uploadBtn = document.getElementById('uploadBtn');

                if (this.files.length > 0) {
                    fileName.textContent = this.files[0].name;
                    uploadBtn.disabled = false;
                } else {
                    fileName.textContent = '';
                    uploadBtn.disabled = true;
                }
            });
        });
    </script>
@endsection
