@extends('layouts.sideBar')

@section('content')
<div class="container" style="width: 90%;">
    @can('users_create')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>{{ __('trans.users') }}</h4>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                {{ __('trans.create_user') }}
            </a>
        </div>
    @endcan

    <!-- Users Table -->
    <div id="users-table">
        @include('professors.partial.table-users', ['professors' => $professors])
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleStatus(userId) {
        fetch(`/users/${userId}/change-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to update status');
            return fetch(`{{ route('users.table') }}`);
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('users-table').innerHTML = html;
        })
        .catch(error => console.error(error));
    }
</script>
@endsection
