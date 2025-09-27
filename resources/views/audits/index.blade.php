@extends('layouts.sideBar')

@section('content')
    <div class="container py-4">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-history me-2"></i>Audit Logs</h4>
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('audits.index') }}" class="row g-2 mb-4">
            <div class="col-md-2">
                <select name="table" class="form-select">
                    <option value="">All Tables</option>
                    <option value="sessions" {{ request('table') == 'sessions' ? 'selected' : '' }}>Sessions</option>
                    <option value="session_extras" {{ request('table') == 'session_extras' ? 'selected' : '' }}>Session Extras</option>
                    <option value="session_students" {{ request('table') == 'session_students' ? 'selected' : '' }}>Session Students</option>
                    <option value="charges" {{ request('table') == 'charges' ? 'selected' : '' }}>Charges</option>
                    <option value="professors" {{ request('table') == 'professors' ? 'selected' : '' }}>Professors</option>
                    <option value="students" {{ request('table') == 'students' ? 'selected' : '' }}>Students</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="user" class="form-control" placeholder="User ID"
                    value="{{ request('user') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}"
                    placeholder="From date">
            </div>
            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To date">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('audits.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
        </form>

        {{-- Desktop Table --}}
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Table</th>
                            <th>Record ID</th>
                            <th>User</th>
                            <th>Old Data</th>
                            <th>New Data</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($audits as $index => $audit)
                            <tr>
                                <td>{{ $audits->firstItem() + $index }}</td>
                                <td>{{ ucfirst($audit->table_name) }}</td>
                                <td>{{ $audit->record_id }}</td>
                                <td>{{ $audit->user ? $audit->user->name : 'System' }}</td>
                                <td>
                                    @if($audit->old_data)
                                        <details>
                                            <summary>View Old Data</summary>
                                            <pre>{{ json_encode($audit->old_data, JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($audit->new_data)
                                        <details>
                                            <summary>View New Data</summary>
                                            <pre>{{ json_encode($audit->new_data, JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $audit->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No audit logs available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Cards --}}
        <div class="d-md-none">
            @forelse($audits as $audit)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-2">{{ ucfirst($audit->table_name) }} #{{ $audit->record_id }}</h5>
                        <p class="mb-1"><strong>User:</strong> {{ $audit->user ? $audit->user->name : 'System' }}</p>
                        <p class="mb-1"><strong>Updated At:</strong> {{ $audit->created_at->format('M d, Y H:i') }}</p>
                        @if($audit->old_data)
                            <details class="mb-2">
                                <summary>Old Data</summary>
                                <pre>{{ json_encode($audit->old_data, JSON_PRETTY_PRINT) }}</pre>
                            </details>
                        @endif
                        @if($audit->new_data)
                            <details class="mb-2">
                                <summary>New Data</summary>
                                <pre>{{ json_encode($audit->new_data, JSON_PRETTY_PRINT) }}</pre>
                            </details>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No audit logs available.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $audits->links() }}
        </div>
    </div>
@endsection
