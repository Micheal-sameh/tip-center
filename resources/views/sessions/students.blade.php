@extends('layouts.sideBar')

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Professor:</strong> {{ $session->professor->name }}</div>
                    <div class="col-md-3"><strong>Stage:</strong>
                        {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</div>
                    <div class="col-md-3"><strong>Date:</strong>
                        {{ $session->created_at->format('d-m-Y') }}</div>
                </div>

                {{-- Students Table --}}
                <h5 class="mt-4 mb-3">Students Attendance</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Phone</th>
                                <th>Parent Phone</th>
                                <th>Attend at</th>
                                <th>Center</th>
                                <th>Professor</th>
                                <th>Student Paper</th>
                                <th>Prof Books</th>
                                <th>Attended By</th>
                                <th>Updated By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($session->sessionStudents as $student)
                                @php
                                    $rowClass = '';
                                    if (!$student->is_attend) {
                                        $rowClass = 'table-danger';
                                    } elseif (
                                        $student->student &&
                                        $student->student->specialCases->contains('id', $session->professor_id)
                                    ) {
                                        $rowClass = 'table-info';
                                    } elseif (
                                        $student->to_pay +
                                            $student->to_pay_center +
                                            $student->to_pay_print +
                                            $student->to_pay_materials >
                                        0
                                    ) {
                                        $rowClass = 'table-warning';
                                    }
                                @endphp

                                <tr class="student-row {{ $rowClass }}" data-id="{{ $student->id }}"
                                    data-center="{{ $student->center_price }}"
                                    data-professor="{{ $student->professor_price }}" data-to_pay="{{ $student->to_pay }}"
                                    data-to_pay_center="{{ $student->to_pay_center }}"
                                    data-to_pay_print="{{ $student->to_pay_print }}"
                                    data-to_pay_materials="{{ $student->to_pay_materials }}"
                                    data-materials="{{ $student->materials }}"
                                    data-printables="{{ $student->printables }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->student?->name }}</td>
                                    <td>{{ $student->student?->code }}</td>
                                    <td>{{ $student->student?->phone }}</td>
                                    <td>{{ $student->student?->parent_phone }}</td>
                                    <td>{{ $student->is_attend ? $student->created_at->format('h:i A') : 'Absent' }}</td>
                                    <td>{{ $student->center_price }}</td>
                                    <td>{{ $student->professor_price }}</td>
                                    <td>{{ $student->printables }}</td>
                                    <td>{{ $student->materials }}</td>
                                    <td>{{ $student->createdBy?->name }}</td>
                                    <td>{{ $student->updatedBy?->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            {{-- Show button --}}
                                            <a href="{{ route('students.show', $student->student?->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Delete button --}}
                                            <button type="button" class="btn btn-danger btn-sm delete-attendance-btn"
                                                data-attendance-id="{{ $student->id }}"
                                                data-student-name="{{ $student->student?->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Onlines Table --}}
                @if (!$session->onlines->isEmpty())
                    <h5 class="mt-5 mb-3">Online Payments</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Materials</th>
                                    <th>Stage</th>
                                    <th>Professor</th>
                                    <th>Center</th>
                                    <th>Paid At</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($session->onlines as $online)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $online->name }}</td>
                                        <td>{{ $online->materials }}</td>
                                        <td>{{ App\Enums\StagesEnum::getStringValue($online->stage) }}</td>
                                        <td>{{ $online->professor }}</td>
                                        <td>{{ $online->center }}</td>
                                        <td>{{ $online->created_at->format('h:i A') }}</td>
                                        <td>{{ $online->createdBy?->name }}</td>
                                        <td>{{ $online->updatedBy?->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm delete-online-btn"
                                                data-online-id="{{ $online->id }}"
                                                data-online-name="{{ $online->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($session->onlines->isEmpty())
                                    <tr>
                                        <td colspan="10" class="text-center">No online payments found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Delete Confirmation Forms -->
    <form id="deleteAttendanceForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="deleteOnlineForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- Students Update Modal --}}
    <div class="modal fade" id="updatePricesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="updatePricesForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Prices</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="studentId">
                        <div class="mb-3">
                            <label class="form-label">Center Price</label>
                            <input type="number" class="form-control" name="center_price" id="centerPrice">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Professor Price</label>
                            <input type="number" class="form-control" name="professor_price" id="professorPrice">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Materials</label>
                            <input type="number" class="form-control" name="materials" id="materials">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Student Papers</label>
                            <input type="number" class="form-control" name="printables" id="printables">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">To Pay (Prof)</label>
                            <input type="number" class="form-control" name="to_pay" id="to_pay">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">To Pay (Center)</label>
                            <input type="number" class="form-control" name="to_pay_center" id="to_pay_center">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">To Pay (Print)</label>
                            <input type="number" class="form-control" name="to_pay_print" id="to_pay_print">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">To Pay (Materials)</label>
                            <input type="number" class="form-control" name="to_pay_materials" id="to_pay_materials">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.student-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('form')) return;

                let id = this.dataset.id;
                document.getElementById('studentId').value = id;
                document.getElementById('centerPrice').value = this.dataset.center;
                document.getElementById('professorPrice').value = this.dataset.professor;
                document.getElementById('materials').value = this.dataset.materials;
                document.getElementById('printables').value = this.dataset.printables;
                document.getElementById('to_pay').value = this.dataset.to_pay;
                document.getElementById('to_pay_print').value = this.dataset.to_pay_print;
                document.getElementById('to_pay_materials').value = this.dataset.to_pay_materials;
                document.getElementById('to_pay_center').value = this.dataset.to_pay_center;

                document.getElementById('updatePricesForm').action = `/session-students/${id}`;

                new bootstrap.Modal(document.getElementById('updatePricesModal')).show();
            });
        });

        // Delete attendance handler
        $(document).ready(function() {
            $(document).off('click', '.delete-attendance-btn').on('click', '.delete-attendance-btn', function() {
                const button = $(this);
                const attendanceId = button.data('attendance-id');
                const studentName = button.data('student-name');

                Swal.fire({
                    title: 'Delete Attendance',
                    text: `Delete ${studentName} attendance?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#deleteAttendanceForm');
                        form.attr('action', `/session-students/${attendanceId}`);
                        form.submit();
                    }
                });
            });

            // Delete online handler
            $(document).off('click', '.delete-online-btn').on('click', '.delete-online-btn', function() {
                const button = $(this);
                const onlineId = button.data('online-id');
                const onlineName = button.data('online-name');

                Swal.fire({
                    title: 'Delete Online Payment',
                    text: `Delete online payment for ${onlineName}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('#deleteOnlineForm');
                        form.attr('action', `/online/${onlineId}`);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection

@push('styles')
    <style>
        .table-pink {
            background-color: #f8d7f0 !important;
        }
    </style>
@endpush
