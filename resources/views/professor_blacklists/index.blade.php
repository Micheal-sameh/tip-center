@extends('layouts.sideBar')

@section('content')
<div class="container">
    <h1>Professor Blacklist</h1>

    <form method="GET" action="{{ route('professor_blacklists.index') }}" class="mb-4">
        <div class="row">
            <div class="col">
                <input type="text" name="professor_name" class="form-control" placeholder="Professor Name" value="{{ request('professor_name') }}">
            </div>
            <div class="col">
                <input type="text" name="student_name" class="form-control" placeholder="Student Name" value="{{ request('student_name') }}">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('professor_blacklists.index') }}" class="btn btn-secondary">Clear</a>
                <a href="{{ route('professor_blacklists.create') }}" class="btn btn-success">Add New</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Professor</th>
                <th>Student</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($blacklists as $blacklist)
                <tr>
                    <td>{{ $blacklist->id }}</td>
                    <td>{{ $blacklist->professor->name ?? '' }}</td>
                    <td>{{ $blacklist->student->name ?? '' }}</td>
                    <td>{{ $blacklist->reason }}</td>
                    <td>
                        <form action="{{ route('professor_blacklists.destroy', $blacklist) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="password" class="password-input">
                            <button type="button" class="btn btn-danger btn-sm btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $blacklists->appends(request()->query())->links() }}
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const form = btn.closest('form.delete-form');

                Swal.fire({
                    title: 'Confirm Password',
                    input: 'password',
                    inputLabel: 'Please enter your password to confirm',
                    inputPlaceholder: 'Password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    showLoaderOnConfirm: true,
                    preConfirm: (password) => {
                        if (!password) {
                            Swal.showValidationMessage('Password is required');
                            return false;
                        }
                        return password;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.querySelector('.password-input').value = result.value;
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
