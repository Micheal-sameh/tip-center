@if ($online_sessions->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4>No sessions found</h4>
            <p class="text-muted">Create your first session by clicking the button above</p>
        </div>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($online_sessions as $key => $session)
            <div class="col">
                @php
                    switch ($session->status) {
                        case \App\Enums\SessionStatus::WARNING:
                            $btnClass = 'danger'; // red
                            break;
                        case \App\Enums\SessionStatus::PENDING:
                            $btnClass = 'secondary'; // grey
                            break;
                        case \App\Enums\SessionStatus::ACTIVE:
                            $btnClass = 'success'; // green
                            break;
                        case \App\Enums\SessionStatus::FINISHED:
                            $btnClass = 'primary'; // blue
                            break;
                        default:
                            $btnClass = 'light'; // fallback
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
                            {{ App\Enums\StagesEnum::getStringValue($session->stage) }}</h6>

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
                                <a class="fw-bold" href='{{ route('sessions.students', $session->id) }}'>Students:</a>
                                <span class="fw-bold">{{ $session->session_students_count }} </span>
                            </div>
                            @if ($session->room)
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold"
                                        >Room:</a>
                                    <span class="fw-bold">{{ $session->room }} </span>
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
                    </div>

                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">

                            @if ($session->status != App\Enums\SessionStatus::FINISHED)
                                <button type="button"class="btn btn-sm btn-{{ $btnClass }} status-toggle"
                                    data-id="{{ $session->id }}">
                                    {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                                </button>
                            @else
                                <span
                                    class="badge bg-{{ $session->status === App\Enums\SessionStatus::WARNING ? 'warning' : 'secondary' }}">
                                    <i
                                        class="fas fa-{{ $session->status === App\Enums\SessionStatus::WARNING ? 'clock' : 'times-circle' }} me-1"></i>
                                    {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                                </span>
                            @endif
                            <form action="{{ route('sessions.active', $session->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success" data-id="{{ $session->id }}">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Activate
                                </button>
                            </form>

                            <div class="btn-group">
                                <a href="{{ route('sessions.show', $session->id) }}"
                                    class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('sessions_update')
                                    <a href="{{ route('sessions.edit', $session->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
        @if ($online_sessions->hasPages())
            <nav>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($online_sessions->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $online_sessions->previousPageUrl() }}" rel="prev">&laquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($online_sessions->getUrlRange(1, $online_sessions->lastPage()) as $page => $url)
                        <li class="page-item {{ $online_sessions->currentPage() === $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($online_sessions->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $online_sessions->nextPageUrl() }}" rel="next">&raquo;</a>
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

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusChangeModal" tabindex="-1" aria-labelledby="statusChangeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusChangeModalLabel">Change Session Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusChangeForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="markers" class="form-label">Markers</label>
                            <input type="number" class="form-control" id="markers" name="markers" min="0"
                                placeholder="Enter number of markers used">
                        </div>

                        <div class="mb-3">
                            <label for="copies" class="form-label">Copies</label>
                            <input type="number" class="form-control" id="copies" name="copies" min="0"
                                placeholder="Enter number of copies used">
                        </div>

                        <div class="mb-3">
                            <label for="cafeteria" class="form-label">Cafeteria</label>
                            <input type="number" class="form-control" id="cafeteria" name="cafeteria"
                                min="0" placeholder="Enter cafeteria expenses">
                        </div>

                        <div class="mb-3">
                            <label for="other" class="form-label">Other Expenses</label>
                            <input type="number" class="form-control" id="other" name="other" min="0"
                                placeholder="Enter other expenses">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional notes"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">End Session</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
