@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 1200px;">
        <!-- Professor Details Card -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
            <!-- Card Header with Gradient Background -->
            <div class="bg-gradient-primary text-white px-4 py-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ __('trans.professor_details') }}</h4>
                        <p class="mb-0 opacity-75">{{ $professor->subject }} Professor</p>
                    </div>
                </div>
                <a href="{{ route('professors.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <div class="row">
                    <!-- Professor Avatar Column -->
                    <div class="col-md-4 p-4 text-center border-end bg-light bg-opacity-10">
                        <div class="d-flex flex-column align-items-center h-100">
                            <div class="mb-3 w-100">
                                <h5 class="text-dark fw-bold pt-4 mb-1">{{ $professor->name }}</h5>
                                <p class="text-muted small mb-3">{{ $professor->email }}</p>
                                @if ($professor->status == 1)
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-circle me-1 small" style="font-size: 8px;"></i>
                                        {{ __('trans.active') }}
                                    </span>
                                @else
                                    <span
                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-normal">
                                        <i class="fas fa-circle me-1 small" style="font-size: 8px;"></i>
                                        {{ __('trans.inactive') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Avatar with hover effect -->
                            <div class="avatar-wrapper mx-auto mt-auto position-relative">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#avatarModal" class="avatar-link">
                                    @if ($professor->hasMedia('professors_images'))
                                        <img src="{{ $professor->getFirstMediaUrl('professors_images') }}"
                                            class="avatar img-fluid rounded-circle shadow-sm" alt="Professor Avatar">
                                        <div class="avatar-overlay rounded-circle">
                                            <i class="fas fa-camera text-white"></i>
                                        </div>
                                    @else
                                        @php
                                            $logo = App\Models\Setting::where('name', 'logo')->first();
                                        @endphp
                                        <img src="{{ $logo?->getFirstMediaUrl('app_logo') ?? asset('images/default-avatar.png') }}"
                                            class="avatar img-fluid rounded-circle shadow-sm" alt="Professor Avatar">
                                        <div class="avatar-overlay rounded-circle">
                                            <i class="fas fa-camera text-white"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Professor Info Column -->
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-phone-alt me-2 text-primary"></i>{{ __('trans.phone') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">{{ $professor->phone }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-phone me-2 text-primary"></i>{{ __('trans.optional_phone') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">{{ $professor->optional_phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-birthday-cake me-2 text-primary"></i>{{ __('trans.birth_date') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">{{ $professor->birth_date?->format('d-m-Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-book me-2 text-primary"></i>{{ __('trans.subject') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">{{ $professor->subject }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stages Section -->
                <div class="mt-4 pt-3 border-top">
                    <h5 class="text-muted fw-semibold mb-3 d-flex align-items-center">
                        <i class="fas fa-graduation-cap me-2 text-primary"></i> {{ __('trans.stages') }}
                    </h5>
                    @if ($professor->stages && count($professor->stages))
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('trans.stage') }}</th>
                                        <th>{{ __('trans.day') }}</th>
                                        <th>{{ __('trans.from') }}</th>
                                        <th>{{ __('trans.to') }}</th>
                                    </tr>
                                </thead>
                                @php
                                    $daysMap = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                @endphp
                                <tbody>
                                    @foreach ($professor->stages as $stage)
                                        <tr>
                                            <td>{{ \App\Enums\StagesEnum::getStringValue($stage->stage) }}</td>
                                            <td>{{ $daysMap[$stage->day] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($stage->from)->format('h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($stage->to)->format('h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border d-inline-block">
                            <i class="fas fa-info-circle me-2 text-primary"></i> {{ __('trans.no_stages_assigned') }}
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end gap-3 py-3 px-4">
                    @can('professors_update')
                        <a href="{{ route('professors.edit', $professor->id) }}" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-edit me-2"></i> {{ __('trans.edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('professor_blacklists.create', ['professor_id' => $professor->id]) }}"
                       class="btn btn-danger rounded-pill px-4">
                        <i class="fas fa-ban me-2"></i> Add to Blacklist
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <div class="bg-gradient-primary text-white px-4 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            Students Blacklists related to this Professor
                        </h5>
                    </div>
                    @if ($professor->blacklists->isNotEmpty())
                        <div class="d-none d-md-block table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Reason</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($professor->blacklists as $blacklist)
                                    <tr>
                                        <td>{{ $blacklist->student->name ?? 'N/A' }}</td>
                                        <td>{{ $blacklist->reason }}</td>
                                        <td>{{ $blacklist->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-block d-md-none">
                            @foreach ($professor->blacklists as $blacklist)
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title mb-2">{{ $blacklist->student->name ?? 'N/A' }}</h5>
                                        <p class="card-text mb-1"><strong>Reason: </strong>{{ $blacklist->reason }}</p>
                                        <p class="card-text text-muted small">Created at: {{ $blacklist->created_at->format('Y-m-d') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No student blacklists related to this professor.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Avatar Modal -->
        <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">{{ __('trans.profile_picture') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <div class="mb-4">
                            <div class="avatar-preview mx-auto">
                                @if ($professor->hasMedia('professors_images'))
                                    <img src="{{ $professor->getFirstMediaUrl('professors_images') }}"
                                        class="img-fluid rounded shadow" id="zoomableImage"
                                        style="max-height: 500px; cursor: zoom-in;" alt="Professor Avatar">
                                @else
                                    @php
                                        $logo = App\Models\Setting::where('name', 'logo')->first();
                                    @endphp
                                    <img src="{{ $logo?->getFirstMediaUrl('app_logo') ?? asset('images/default-avatar.png') }}"
                                        class="img-fluid rounded shadow" style="max-height: 500px;"
                                        alt="Professor Avatar">
                                @endif
                            </div>
                        </div>

                        @can('professors_update')
                            <form action="{{ route('professors.profilePic', $professor->id) }}" method="POST"
                                enctype="multipart/form-data" class="px-3">
                                @csrf
                                @method('PUT')
                                <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                                    <div class="mb-3 flex-grow-1">
                                        <input type="file" name="image" id="avatarUpload" accept="image/*"
                                            class="form-control d-none" required>
                                        <label for="avatarUpload" class="btn btn-primary rounded-pill px-4 py-2 w-100">
                                            <i class="fas fa-cloud-upload-alt me-2"></i> {{ __('trans.choose_new_photo') }}
                                        </label>
                                        <div id="fileName" class="small text-muted mt-2"></div>
                                    </div>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 mb-3 mb-md-0"
                                        id="uploadBtn" disabled>
                                        <i class="fas fa-check me-2"></i> {{ __('trans.update_photo') }}
                                    </button>
                                    {{-- @if ($professor->hasMedia('professors_images'))
                                    <button type="button" class="btn btn-danger rounded-pill px-4 py-2"
                                        id="removeAvatarBtn">
                                        <i class="fas fa-trash-alt me-2"></i> {{ __('trans.remove_photo') }}
                                    </button> --}}
                                    {{-- @endif --}}
                                </div>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- Remove Avatar Form (hidden) -->
        {{-- @can('professors_update')
        <form id="removeAvatarForm" action="{{ route('professors.remove.avatar', $professor->id) }}" method="POST"
            class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endcan --}}

        <style>
            .bg-gradient-primary {
                background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
            }

            .info-card {
                transition: all 0.3s ease;
                height: 100%;
                background-color: rgba(248, 249, 250, 0.5);
            }

            .info-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                background-color: white;
            }

            .rounded-4 {
                border-radius: 1rem !important;
            }

            /* Avatar Styles */
            .avatar-wrapper {
                width: 200px;
                height: 200px;
                position: relative;
            }

            .avatar-link {
                display: block;
                position: relative;
            }

            .avatar {
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

            /* Modal Styles */
            .avatar-preview {
                max-width: 100%;
                overflow: hidden;
            }

            /* Zoom effect */
            .zoomed {
                cursor: zoom-out;
                transform: scale(1.5);
                transition: transform 0.3s ease;
            }

            /* Responsive adjustments */
            @media (max-width: 767.98px) {
                .avatar-wrapper {
                    width: 120px;
                    height: 120px;
                }

                .col-md-4.border-end {
                    border-right: none !important;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                }
            }
        </style>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Show selected file name
                    document.getElementById('avatarUpload')?.addEventListener('change', function(e) {
                        const fileName = document.getElementById('fileName');
                        const uploadBtn = document.getElementById('uploadBtn');

                        if (this.files.length > 0) {
                            fileName.textContent = this.files[0].name;
                            uploadBtn.disabled = false;

                            // Preview the selected image
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const preview = document.querySelector('.avatar-preview img');
                                if (preview) {
                                    preview.src = e.target.result;
                                }
                            }
                            reader.readAsDataURL(this.files[0]);
                        } else {
                            fileName.textContent = '';
                            uploadBtn.disabled = true;
                        }
                    });

                    // Zoom functionality
                    const zoomableImage = document.getElementById('zoomableImage');
                    if (zoomableImage) {
                        zoomableImage.addEventListener('click', function() {
                            this.classList.toggle('zoomed');
                        });
                    }

                    // Remove avatar button
                    const removeAvatarBtn = document.getElementById('removeAvatarBtn');
                    if (removeAvatarBtn) {
                        removeAvatarBtn.addEventListener('click', function() {
                            if (confirm('{{ __('trans.confirm_remove_avatar') }}')) {
                                document.getElementById('removeAvatarForm').submit();
                            }
                        });
                    }
                });
            </script>
        @endpush
    @endsection
