@extends('layouts.sideBar')

@section('content')
    <div class="container py-4" style="max-width: 1200px;">
        <!-- Student Details Card -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
            <!-- Card Header with Gradient Background -->
            <div class="bg-gradient-primary text-white px-4 py-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ __('trans.student_details') }}</h4>
                        <p class="mb-0 opacity-75">{{ $student->code }}</p>
                    </div>
                </div>
                <a href="{{ route('students.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <div class="row">
                    <!-- Student Avatar Column -->
                    <div class="col-md-4 p-4 text-center border-end bg-light bg-opacity-10">
                        <div class="d-flex flex-column align-items-center h-100">
                            <!-- Avatar with hover effect -->
                            <div class="avatar-wrapper mx-auto mb-3 position-relative">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#avatarModal" class="avatar-link">
                                    @if ($student->hasMedia('students_images'))
                                        <img src="{{ $student->getFirstMediaUrl('students_images') }}"
                                            class="avatar img-fluid rounded-circle shadow-sm" alt="Student Avatar">
                                        <div class="avatar-overlay rounded-circle">
                                            <i class="fas fa-camera text-white"></i>
                                        </div>
                                    @else
                                        <div class="avatar-default rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                                            style="width: 160px; height: 160px;">
                                            <i class="fas fa-user-graduate fa-4x"></i>
                                        </div>
                                        <div class="avatar-overlay rounded-circle">
                                            <i class="fas fa-camera text-white"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>

                            <div class="mt-auto w-100">
                                <h3 class="text-dark fw-bold pt-3 mb-1">{{ $student->name }}</h3>
                                <h5 class="text-primary fw-bold">{{ $student->code }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Student Info Column -->
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-id-card me-2 text-primary"></i>{{ __('trans.code') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">{{ $student->code }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-phone me-2 text-primary"></i>{{ __('trans.phone') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">{{ $student->phone ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-users me-2 text-primary"></i>{{ __('trans.parent_phone') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">{{ $student->parent_phone }}</p>
                                    @if ($student->parent_phone_2)
                                        <p class="mb-0 fw-bold text-dark mt-2">{{ $student->parent_phone_2 }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-graduation-cap me-2 text-primary"></i>{{ __('trans.stage') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">
                                        {{ App\Enums\StagesEnum::getStringValue($student->stage) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 bg-light bg-opacity-10 border border-light">
                                    <h6 class="text-muted mb-2 d-flex align-items-center">
                                        <i class="fas fa-sticky-note me-2 text-primary"></i>{{ __('trans.note') }}
                                    </h6>
                                    <p class="mb-0 fw-bold text-dark">{{ $student->note ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Student Special Cases -->


            <div class="card-body p-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <div class="bg-gradient-primary text-white px-4 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ __('trans.student_special_cases') }}
                        </h5>
                        <a href="{{ route('student-special-cases.create', ['student_id' => $student->id]) }}"
                            class="btn btn-light btn-sm rounded-pill">
                            <i class="fas fa-plus me-1"></i> {{ __('trans.add_special_case') }}
                        </a>
                    </div>
                    @if ($student->specialCases->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('trans.professor') }}</th>
                                        <th>{{ __('trans.professor_price') }}</th>
                                        <th>{{ __('trans.center_price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student->specialCases as $case)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $case->name }}</span>
                                            </td>
                                            <td>
                                                <a href="#" class="editable" data-type="professor_price"
                                                    data-case-id="{{ $case->pivot->id }}"
                                                    data-value="{{ $case->pivot->professor_price }}">
                                                    {{ $case->pivot->professor_price ?? '-' }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" class="editable" data-type="center_price"
                                                    data-case-id="{{ $case->pivot->id }}"
                                                    data-value="{{ $case->pivot->center_price }}">
                                                    {{ $case->pivot->center_price ?? '-' }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">{{ __('trans.no_special_cases') }}</p>
                    @endif
                </div>
            </div>
            <!-- Student To Pay -->
            <div class="card-body p-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <div class="bg-gradient-primary text-white px-4 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-money-bill-wave me-2"></i> {{ __('trans.to_pay') }}
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('trans.name') }}</th>
                                    <th>{{ __('trans.professor') }}</th>
                                    <th>{{ __('trans.center') }}</th>
                                    <th>{{ __('trans.print') }}</th>
                                    <th>{{ __('trans.material') }}</th>
                                    <th>{{ __('trans.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($toPays as $pay)
                                    <tr class="to-pay-row" data-id="{{ $pay->id }}" data-to_pay="{{ $pay->to_pay }}"
                                        data-to_pay_center="{{ $pay->to_pay_center }}"
                                        data-to_pay_print="{{ $pay->to_pay_print }}"
                                        data-to_pay_materials="{{ $pay->to_pay_materials }}">
                                        <td>
                                            <span class="fw-bold">
                                                {{ $pay->session->professor->name }}
                                            </span>
                                        </td>
                                        <td><span class="fw-bold">{{ number_format($pay->to_pay ?? 0, 2) }}</span></td>
                                        <td><span class="fw-bold">{{ number_format($pay->to_pay_center ?? 0, 2) }}</span>
                                        </td>
                                        <td><span class="fw-bold">{{ number_format($pay->to_pay_print ?? 0, 2) }}</span>
                                        </td>
                                        <td><span
                                                class="fw-bold">{{ number_format($pay->to_pay_materials ?? 0, 2) }}</span>
                                        </td>
                                        <td><span class="fw-bold">{{ $pay->created_at->format('d-m-Y') }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- Edit To Pay Modal -->
            <div class="modal fade" id="editToPayModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content rounded-3 shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('trans.edit_to_pay') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="editToPayForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <input type="hidden" name="id" id="toPayId">

                                <div class="mb-3">
                                    <label class="form-label">{{ __('trans.professor') }}</label>
                                    <input type="number" step="1" class="form-control" name="to_pay"
                                        id="toPay">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('trans.center') }}</label>
                                    <input type="number" step="1" class="form-control" name="to_pay_center"
                                        id="toPayCenter">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('trans.print') }}</label>
                                    <input type="number" step="1" class="form-control" name="to_pay_print"
                                        id="toPayPrint">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('trans.material') }}</label>
                                    <input type="number" step="1" class="form-control" name="to_pay_materials"
                                        id="toPayMaterials">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">{{ __('trans.cancel') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('trans.save_changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end gap-3 py-3 px-4">
                @can('students_update')
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-edit me-2"></i>
                    </a>
                @endcan
                {{-- @can('students_delete')
                    <form action="{{ route('students.delete', $student->id) }}" method="POST"
                        onsubmit="return confirm('{{ __('trans.delete_confirm') }}')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger rounded-pill px-4">
                            <i class="fas fa-trash-alt me-2"></i>
                        </button>
                    </form>
                @endcan --}}
                <a href="{{ route('reports.student', ['search' => $student->code]) }}"
                    class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-chart-line me-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Special Case Modal -->
    <div class="modal fade" id="editSpecialCaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('trans.edit_special_case') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSpecialCaseForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="case_id" id="case_id">
                        <input type="hidden" name="field" id="field">

                        <div class="mb-3">
                            <label id="fieldLabel" class="form-label"></label>
                            <input type="text" class="form-control" name="value" id="fieldValue">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('trans.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('trans.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            width: 160px;
            height: 160px;
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

        /* Modal Styles */


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
                            } else {
                                const previewDiv = document.querySelector('.avatar-preview');
                                previewDiv.innerHTML =
                                    `<img src="${e.target.result}" class="img-fluid rounded shadow" id="zoomableImage" style="max-height: 500px; cursor: zoom-in;" alt="Student Avatar">`;
                            }
                        }
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        fileName.textContent = '';
                        uploadBtn.disabled = true;
                    }
                });

                // Zoom functionality
                document.addEventListener('click', function(e) {
                    if (e.target.id === 'zoomableImage') {
                        e.target.classList.toggle('zoomed');
                    }
                });

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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editModal = new bootstrap.Modal(document.getElementById('editSpecialCaseModal'));

                document.querySelectorAll('.editable').forEach(el => {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();

                        let field = this.dataset.type;
                        let value = this.dataset.value || '';
                        let caseId = this.dataset.caseId;

                        if (!caseId) {
                            console.error("caseId missing on editable link!");
                            return;
                        }

                        document.getElementById('field').value = field;
                        document.getElementById('fieldValue').value = value;
                        document.getElementById('case_id').value = caseId;

                        let label = '';
                        if (field === 'professor_price') label = "{{ __('trans.professor_price') }}";
                        if (field === 'center_price') label = "{{ __('trans.center_price') }}";

                        document.getElementById('fieldLabel').textContent = label;

                        document.getElementById('editSpecialCaseForm').action =
                            `/student-special-cases/${caseId}`;

                        editModal.show();
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toPayModal = new bootstrap.Modal(document.getElementById('editToPayModal'));

                document.querySelectorAll('.to-pay-row').forEach(row => {
                    row.addEventListener('click', function() {
                        let id = this.dataset.id;
                        let toPay = this.dataset.to_pay;
                        let toPayCenter = this.dataset.to_pay_center;
                        let toPayPrint = this.dataset.to_pay_print;
                        let toPayMaterials = this.dataset.to_pay_materials;

                        // Fill modal inputs
                        document.getElementById('toPayId').value = id;
                        document.getElementById('toPay').value = toPay;
                        document.getElementById('toPayCenter').value = toPayCenter;
                        document.getElementById('toPayPrint').value = toPayPrint;
                        document.getElementById('toPayMaterials').value = toPayMaterials;

                        // Set form action
                        document.getElementById('editToPayForm').action = `/session-students/${id}/update-pay`;

                        toPayModal.show();
                    });
                });
            });
        </script>
    @endpush
@endsection
