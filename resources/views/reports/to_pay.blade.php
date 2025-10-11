@extends('layouts.sideBar')

@section('content')
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Students To Pay Report</h5>
            </div>

            <div class="card-body">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="stage" class="form-label">Stage</label>
                        <select name="stage" id="stage" class="form-select">
                            <option value="">All Stages</option>
                            @foreach (App\Enums\StagesEnum::all() as $stage)
                                <option value="{{ $stage['value'] }}"
                                    {{ request('stage') == $stage['value'] ? 'selected' : '' }}>
                                    {{ $stage['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="professor_id" class="form-label">Professor</label>
                        <select name="professor_id" id="professor_id" class="form-select">
                            <option value="">All Professors</option>
                            @foreach (\App\Models\Professor::all() as $professor)
                                <option value="{{ $professor->id }}"
                                    {{ request('professor_id') == $professor->id ? 'selected' : '' }}>
                                    {{ $professor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="name" class="form-label">Student Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ request('name') }}" placeholder="Search by name">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('reports.to_pay') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Stage</th>
                                <th>Phone</th>
                                <th>Session Date</th>
                                <th>Professor</th>
                                <th class="text-end">To Pay (Prof)</th>
                                <th class="text-end">To Pay Center</th>
                                <th class="text-end">To Pay Materials</th>
                                <th class="text-end">To Pay Print</th>
                                <th class="text-end">Total To Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($toPays as $toPay)
                                @php
                                    $total =
                                        $toPay->to_pay +
                                        $toPay->to_pay_center +
                                        $toPay->to_pay_materials +
                                        $toPay->to_pay_print;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a
                                            href="{{ route('students.show', $toPay->student_id) }}">{{ $toPay->student->name }}</a>
                                    </td>
                                    <td>{{ App\Enums\StagesEnum::getStringValue($toPay->student->stage) }}</td>
                                    <td>{{ $toPay->student->phone }}</td>
                                    <td>{{ $toPay->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $toPay->session->professor->name }}</td>
                                    <td class="text-end">{{ number_format($toPay->to_pay, 2) }}</td>
                                    <td class="text-end">{{ number_format($toPay->to_pay_center, 2) }}</td>
                                    <td class="text-end">{{ number_format($toPay->to_pay_materials, 2) }}</td>
                                    <td class="text-end">{{ number_format($toPay->to_pay_print, 2) }}</td>
                                    <td class="text-end fw-bold text-danger">{{ number_format($total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($toPays->hasPages())
                    <div class="d-flex justify-content-center pt-2">
                        @if ($toPays->hasPages())
                            <nav>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($toPays->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">&laquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $toPays->previousPageUrl() }}"
                                                rel="prev">&laquo;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $start = max(1, $toPays->currentPage() - 2);
                                        $end = min($toPays->lastPage(), $toPays->currentPage() + 2);
                                    @endphp

                                    {{-- Show first page + dots if needed --}}
                                    @if ($start > 1)
                                        <li class="page-item"><a class="page-link" href="{{ $toPays->url(1) }}">1</a>
                                        </li>
                                        @if ($start > 2)
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        @endif
                                    @endif

                                    {{-- Main page loop --}}
                                    @foreach ($toPays->getUrlRange($start, $end) as $page => $url)
                                        <li class="page-item {{ $toPays->currentPage() === $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    {{-- Show last page + dots if needed --}}
                                    @if ($end < $toPays->lastPage())
                                        @if ($end < $toPays->lastPage() - 1)
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        @endif
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $toPays->url($toPays->lastPage()) }}">{{ $toPays->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($toPays->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $toPays->nextPageUrl() }}"
                                                rel="next">&raquo;</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">&raquo;</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif

                    </div>

                @endif
            </div>
        </div>
    </div>
@endsection
