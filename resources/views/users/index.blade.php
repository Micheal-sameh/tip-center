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
        @include('users.partial.table-users', ['users' => $users])
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the status toggles
        initializeStatusToggles();

        // Set up event delegation for dynamically loaded content
        document.getElementById('users-table').addEventListener('click', function(e) {
            handleToggleEvent(e);
        });

        // Also handle touch events for mobile
        document.getElementById('users-table').addEventListener('touchstart', function(e) {
            handleToggleEvent(e);
        });
    });

    function handleToggleEvent(e) {
        const toggleBtn = e.target.closest('.status-toggle');
        if (toggleBtn) {
            e.preventDefault();
            const userId = toggleBtn.dataset.userId;
            toggleStatus(userId);
        }
    }

    function toggleStatus(userId) {
        fetch(`/users/${userId}/change-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Failed to update status');
            return fetch(`{{ route('users.table') }}`);
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('users-table').innerHTML = html;
            initializeStatusToggles();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
        });
    }

    function initializeStatusToggles() {
        document.querySelectorAll('.status-toggle').forEach(button => {
            // Make sure we don't duplicate event listeners
            button.style.cursor = 'pointer';
            button.style.minWidth = '44px';
            button.style.minHeight = '44px';
        });
    }
</script>

<style>
    /* Ensure the toggle buttons are touch-friendly on mobile */
    .status-toggle {
        min-width: 44px;
        min-height: 44px;
        padding: 10px;
        cursor: pointer;
        border: none;
        background: transparent;
    }

    /* Responsive table adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .status-toggle {
            padding: 15px; /* Larger touch target on mobile */
        }
    }
</style>
@endsection