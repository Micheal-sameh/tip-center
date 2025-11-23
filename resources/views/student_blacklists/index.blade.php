@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <div class="card shadow rounded-4 p-4">
        <h3 class="mb-4 d-flex justify-content-between align-items-center">
            Center Blacklist
            <a href="{{ route('student_blacklists.create') }}" class="btn btn-danger rounded-pill px-4">Add to Blacklist</a>
        </h3>

        @if($blacklists->count() > 0)
        <div class="d-none d-md-block">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Reason</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blacklists as $blacklist)
                    <tr>
                        <td>{{ $blacklist->student->name }}</td>
                        <td>{{ $blacklist->reason }}</td>
                        <td>{{ $blacklist->created_at->format('Y-m-d') }}</td>
                        <td>
                            <form action="{{ route('student_blacklists.destroy', $blacklist->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blacklist entry?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-md-none">
            @foreach($blacklists as $blacklist)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $blacklist->student->name }}</h5>
                    <p class="card-text"><strong>Reason:</strong> {{ $blacklist->reason }}</p>
                    <p class="card-text"><small class="text-muted">Created at: {{ $blacklist->created_at->format('Y-m-d') }}</small></p>
                    <form action="{{ route('student_blacklists.destroy', $blacklist->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blacklist entry?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{ $blacklists->links() }}

        @else
        <p>No blacklisted students found.</p>
        @endif
    </div>
</div>
@endsection
