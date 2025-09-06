@if ($sessions->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4>No sessions found</h4>
            <p class="text-muted">Create your first session by clicking the button above</p>
        </div>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($sessions as $key => $session)
            <div class="col">
                @php
                    switch ($session->status) {
                        case \App\Enums\SessionStatus::WARNING:
                            $btnClass = 'danger';
                            break;
                        case \App\Enums\SessionStatus::PENDING:
                            $btnClass = 'secondary';
                            break;
                        case \App\Enums\SessionStatus::ACTIVE:
                            $btnClass = 'success';
                            break;
                        case \App\Enums\SessionStatus::FINISHED:
                            $btnClass = 'primary';
                            break;
                        default:
                            $btnClass = 'light';
                    }
                @endphp

                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-{{ $btnClass }} text-white py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="fw-bold">#{{ $key + 1 }}</small>
                            <span class="badge bg-white text-dark status-badge">
                                {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $session->professor->name }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            {{ App\Enums\StagesEnum::getStringValue($session->stage) }}
                        </h6>

                        <div class="my-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Professor Price:</span>
                                <span class="fw-bold">{{ number_format($session->professor_price, 2) }}
                                    {{ config('app.currency', 'EGP') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Center Price:</span>
                                <span class="fw-bold">{{ number_format($session->center_price, 2) }}
                                    {{ config('app.currency', 'EGP') }}</span>
                            </div>
                            @if ($session->printables)
                                <div class="d-flex justify-content-between">
                                    <span>Printables:</span>
                                    <span class="fw-bold">{{ number_format($session->printables, 2) }}
                                        {{ config('app.currency', 'EGP') }}</span>
                                </div>
                            @endif
                            @if ($session->materials)
                                <div class="d-flex justify-content-between">
                                    <span>Materials:</span>
                                    <span class="fw-bold">{{ number_format($session->materials, 2) }}
                                        {{ config('app.currency', 'EGP') }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <a class="fw-bold" href="{{ route('sessions.students', $session->id) }}">Students:</a>
                                <span class="fw-bold">{{ $session->attended_count }}</span>
                            </div>
                            @if ($session->room)
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Room:</span>
                                    <span class="fw-bold">{{ $session->room }}</span>
                                </div>
                            @endif
                            @if ($session->type)
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Session Type:</span>
                                    <span
                                        class="fw-bold">{{ App\Enums\SessionType::getStringValue($session->type) }}</span>
                                </div>
                            @endif
                        </div>

                        @if ($session->start_at && $session->end_at)
                            <div class="d-flex align-items-center text-muted mt-3">
                                <i class="fas fa-clock me-2"></i>
                                <small>
                                    {{ \Carbon\Carbon::parse($session->start_at)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($session->end_at)->format('h:i A') }}
                                </small>
                            </div>
                        @endif

                        {{-- Extras Button (data-* always present/safe) --}}
                        <a href="{{route('sessions.extras-form', $session->id)}}" type="button" class="btn btn-sm btn-primary mt-2" >
                            Extras
                        </a>

                    </div>

                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            @if ($session->status != App\Enums\SessionStatus::FINISHED)
                                <form action="{{ route('sessions.close', $session->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        onclick="return confirm('Are you sure you want to close this session?');">
                                        Close
                                    </button>
                                </form>
                            @else
                                <span
                                    class="badge bg-{{ $session->status === App\Enums\SessionStatus::WARNING ? 'warning' : 'secondary' }} me-1">
                                    <i
                                        class="fas fa-{{ $session->status === App\Enums\SessionStatus::WARNING ? 'clock' : 'times-circle' }} me-1"></i>
                                    {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                                </span>
                            @endif

                            @if (
                                $session->status == App\Enums\SessionStatus::PENDING ||
                                    auth()->user()->hasAnyRole(['admin', 'manager']))
                                <form action="{{ route('sessions.active', $session->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success me-1">Activate</button>
                                </form>
                            @endif

                            <div class="btn-group">
                                <a href="{{ route('sessions.show', $session->id) }}"
                                    class="btn btn-sm btn-outline-info me-1" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('sessions_update')
                                    @if (auth()->user()->hasAnyRole(['admin', 'manager']))
                                        <a href="{{ route('sessions.edit', $session->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        @if ($sessions->hasPages())
            <nav>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($sessions->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $sessions->previousPageUrl() }}" rel="prev">&laquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                        <li class="page-item {{ $sessions->currentPage() === $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($sessions->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $sessions->nextPageUrl() }}" rel="next">&raquo;</a>
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

    {{-- Modal --}}
    <div class="modal fade" id="statusChangeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Session Extras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="statusChangeForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Markers</label>
                            <input type="number" class="form-control" id="markers" name="markers" placeholder="">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Copies</label>
                            <input type="number" class="form-control" id="copies" name="copies"
                                placeholder="Enter copies">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cafeteria</label>
                            <input type="number" class="form-control" id="cafeteria" name="cafeteria"
                                placeholder="Enter cafeteria">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Other Expenses</label>
                            <input type="number" class="form-control" id="other" name="other"
                                placeholder="Enter other expenses">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" placeholder="Enter notes"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Save Extras</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('statusChangeModal');

            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                // Parse JSON string (fix escaped quotes)
                const raw = button.getAttribute('data-session');
                const session = JSON.parse(raw.replace(/&quot;/g, '"'));

                const form = modal.querySelector('#statusChangeForm');
                form.action = `/sessions/${session.id}/extras`;

                // ✅ Set placeholders only (light gray text)
                form.querySelector('#markers').placeholder = session.session_extra?.markers ?? '0';
                form.querySelector('#copies').placeholder = session.session_extra?.copies ?? '0';
                form.querySelector('#cafeteria').placeholder = session.session_extra?.cafeteria ?? '0';
                form.querySelector('#other').placeholder = session.session_extra?.other ?? '0';
                form.querySelector('#notes').placeholder = session.session_extra?.notes ?? '';
            });

        });
    </script>
@endpush
