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
                                <th>Student Name</th>
                                <th>Code</th>
                                <th>Phone</th>
                                <th>Phone (P)</th>
                                <th>Attending Time</th>
                                <th>Paid</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($session->sessionStudents as $student)
                                <tr class="{{ $student->to_pay > 0 ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-bold"><a href="{{ route('students.show', $student->student_id) }}">
                                            {{ $student->student->name }} </a></td>
                                    <td>{{ $student->student->code }}</td>
                                    <td>{{ $student->student->phone }}</td>
                                    <td>{{ $student->student->parent_phone }}</td>
                                    <td>{{ $student->created_at->format('h:i:A') }}</td>
                                    <td>{{ $student->center_price + $student->professor_price + $student->prinatables + $student->materials }}
                                    </td>
                                    <td>
                                        <form action="{{ route('attendances.delete', $student->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"> <i class="fas fa-trash"> </i> </button>
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
                        <div class="card mb-3 {{ $student->to_pay > 0 ? 'border-warning bg-warning bg-opacity-10' : '' }}">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">{{ $loop->iteration }}. <a
                                        href="{{ route('students.show', $student->student_id) }}">{{ $student->student->name }}</a>
                                </h6>
                                <p class="mb-1"><strong>code:</strong> {{ $student->student->code }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $student->student->phone }}</p>
                                <p class="mb-1"><strong>Phone (P):</strong> {{ $student->student->parent_phone }}</p>
                                <p class="mb-1"><strong>Attending :</strong> {{ $student->created_at->format('h:i:A') }}
                                </p>
                                <p class="mb-1"><strong>Paid:</strong>
                                    {{ $student->center_price + $student->professor_price + $student->prinatables + $student->materials }}
                                </p>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
