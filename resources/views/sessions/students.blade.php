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

                <h5 class="mt-4 mb-3">Students Attendance</h5>

                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Phone</th>
                                <th>PP</th>
                                <th>Attend at</th>
                                <th>C</th>
                                <th>P</th>
                                <th>Print</th>
                                <th>PL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($session->sessionStudents as $student)
                                <tr class="student-row {{ $student->to_pay > 0 ? 'table-warning' : '' }}"
                                    data-id="{{ $student->id }}" data-center="{{ $student->center_price }}"
                                    data-professor="{{ $student->professor_price }}"
                                    data-materials="{{ $student->materials }}" data-printables="{{ $student->printables }}">
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- <td class="fw-bold"><a href="{{ route('students.show', $student->student_id) }}">
                                            {{ $student->student?->name }} </a></td> --}}
                                    <td>{{ $student->student?->name }}</td>
                                    <td>{{ $student->student?->code }}</td>
                                    <td>{{ $student->student?->phone }}</td>
                                    <td>{{ $student->student?->parent_phone }}</td>
                                    <td>{{ $student->created_at->format('h:i:A') }}</td>
                                    {{-- <td>{{ $student->center_price + $student->professor_price + $student->printables + $student->materials }} --}}
                                    {{-- </td> --}}
                                    <td>{{ $student->center_price }}</td>
                                    <td>{{ $student->professor_price }}</td>
                                    <td>{{ $student->printables }}</td>
                                    <td>{{ $student->materials }}</td>
                                    <td>
                                        <form action="{{ route('attendances.delete', $student->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="d-md-none">
                    @foreach ($session->sessionStudents as $student)
                        <div class="card mb-3 student-row {{ $student->to_pay > 0 ? 'border-warning bg-warning bg-opacity-10' : '' }}"
                            data-id="{{ $student->id }}" data-center="{{ $student->center_price }}"
                            data-professor="{{ $student->professor_price }}" data-materials="{{ $student->materials }}"
                            data-printables="{{ $student->printables }}">
                            <div class="card-body">
                                {{-- <h6 class="fw-bold mb-1">{{ $loop->iteration }}. <a
                                        href="{{ route('students.show', $student->student_id) }}">{{ $student->student?->name }}</a>
                                </h6> --}}
                                <h6 class="fw-bold mb-1">{{ $loop->iteration }}. {{ $student->student?->name }}
                                </h6>
                                <p class="mb-1"><strong>Code:</strong> {{ $student->student?->code }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $student->student?->phone }}</p>
                                <p class="mb-1"><strong>Phone (P):</strong> {{ $student->student?->parent_phone }}</p>
                                <p class="mb-1"><strong>Attending:</strong> {{ $student->created_at->format('h:i:A') }}
                                </p>
                                <p class="mb-1"><strong>Paid:</strong>
                                    {{ $student->center_price + $student->professor_price + $student->printables + $student->materials }}
                                </p>
                                <form action="{{ route('attendances.delete', $student->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"> </i> </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Update Prices Modal -->
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
                            <label class="form-label">Printables</label>
                            <input type="number" class="form-control" name="printables" id="printables">
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

    <script>
        document.querySelectorAll('.student-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('form')) return; // don’t trigger when clicking delete

                let id = this.dataset.id;
                let center = this.dataset.center;
                let professor = this.dataset.professor;
                let materials = this.dataset.materials;
                let printables = this.dataset.printables;

                document.getElementById('studentId').value = id;
                document.getElementById('centerPrice').value = center;
                document.getElementById('professorPrice').value = professor;
                document.getElementById('materials').value = materials;
                document.getElementById('printables').value = printables;

                document.getElementById('updatePricesForm').action = `/session-students/${id}`;

                new bootstrap.Modal(document.getElementById('updatePricesModal')).show();
            });
        });
    </script>
@endsection
