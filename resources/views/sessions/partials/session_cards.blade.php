@if ($sessions->isEmpty())
    <div class="tc-empty">
        <div class="tc-empty-icon"><i class="fas fa-calendar-times"></i></div>
        <div class="tc-empty-title">No sessions found</div>
        <div class="tc-empty-desc">Create your first session by clicking the "New Session" button above.</div>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
        @foreach ($sessions as $key => $session)
            <div class="col">
                @php
                    $statusColors = [
                        \App\Enums\SessionStatus::WARNING  => ['border'=>'#EF4444','bg'=>'#FEE2E2','text'=>'#991B1B'],
                        \App\Enums\SessionStatus::PENDING  => ['border'=>'#94A3B8','bg'=>'#F1F5F9','text'=>'#475569'],
                        \App\Enums\SessionStatus::ACTIVE   => ['border'=>'#10B981','bg'=>'#D1FAE5','text'=>'#065F46'],
                        \App\Enums\SessionStatus::FINISHED => ['border'=>'#2563EB','bg'=>'#DBEAFE','text'=>'#1E40AF'],
                    ];
                    $sc = $statusColors[$session->status] ?? ['border'=>'#E2E8F0','bg'=>'#F8FAFC','text'=>'#64748B'];
                @endphp

                <div class="session-card" style="border-left: 3px solid {{ $sc['border'] }}">
                    <div class="session-card-header">
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:#1E293B;">{{ $session->professor->name }}</div>
                            <div style="font-size:.78rem;color:#64748B;margin-top:2px;">{{ App\Enums\StagesEnum::getStringValue($session->stage) }}</div>
                        </div>
                        <span class="badge" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }};border-radius:20px;font-size:.72rem;font-weight:700;padding:4px 10px;">
                            {{ App\Enums\SessionStatus::getStringValue($session->status) }}
                        </span>
                    </div>

                    <div class="session-card-body">
                        <div class="session-card-row">
                            <span class="session-card-label">Professor Price</span>
                            <span class="session-card-value">{{ number_format($session->professor_price, 2) }} {{ config('app.currency', 'EGP') }}</span>
                        </div>
                        <div class="session-card-row">
                            <span class="session-card-label">Center Price</span>
                            <span class="session-card-value">{{ number_format($session->center_price, 2) }} {{ config('app.currency', 'EGP') }}</span>
                        </div>
                        @if ($session->printables)
                        <div class="session-card-row">
                            <span class="session-card-label">Student Papers</span>
                            <span class="session-card-value">{{ number_format($session->printables, 2) }} {{ config('app.currency', 'EGP') }}</span>
                        </div>
                        @endif
                        @if ($session->materials)
                        <div class="session-card-row">
                            <span class="session-card-label">Materials</span>
                            <span class="session-card-value">{{ number_format($session->materials, 2) }} {{ config('app.currency', 'EGP') }}</span>
                        </div>
                        @endif
                        <div class="session-card-row">
                            <a href="{{ route('sessions.students', $session->id) }}" class="session-card-label text-brand">Students</a>
                            <span class="session-card-value">{{ $session->attended_count }}</span>
                        </div>
                        @if ($session->room)
                        <div class="session-card-row">
                            <span class="session-card-label">Room</span>
                            <span class="session-card-value">{{ $session->room }}</span>
                        </div>
                        @endif
                        @if ($session->type)
                        <div class="session-card-row">
                            <span class="session-card-label">Session Type</span>
                            <span class="session-card-value">{{ App\Enums\SessionType::getStringValue($session->type) }}</span>
                        </div>
                        @endif
                        @if ($session->start_at && $session->end_at)
                        <div class="session-card-row">
                            <span class="session-card-label"><i class="fas fa-clock me-1"></i>Time</span>
                            <span class="session-card-value">{{ \Carbon\Carbon::parse($session->start_at)->format('h:i A') }} – {{ \Carbon\Carbon::parse($session->end_at)->format('h:i A') }}</span>
                        </div>
                        @endif
                    </div>
                    <div style="padding:10px 16px;display:flex;gap:8px;border-top:1px solid var(--tc-border);">
                        <a href="{{ route('sessions.extras-form', $session->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus-circle me-1"></i>Extras
                        </a>
                        <a href="{{ route('sessions.online-form', $session->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-video me-1"></i>Online
                        </a>
                    </div>

                    <div style="padding:10px 16px 14px;display:flex;gap:8px;flex-wrap:wrap;align-items:center;border-top:1px solid var(--tc-border);">
                        @if ($session->status != App\Enums\SessionStatus::FINISHED)
                            <form action="{{ route('sessions.close', $session->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-primary close-session-btn">
                                    <i class="fas fa-lock me-1"></i>Close
                                </button>
                            </form>
                        @endif

                        @if ($session->status == App\Enums\SessionStatus::PENDING || auth()->user()->hasAnyRole(['admin', 'manager']))
                            <form action="{{ route('sessions.active', $session->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-play me-1"></i>Activate</button>
                            </form>
                        @endif

                        <div style="margin-left:auto;display:flex;gap:6px;">
                            <a href="{{ route('sessions.show', $session->id) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('sessions_update')
                                @if (auth()->user()->hasAnyRole(['admin', 'manager']))
                                    <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            @endcan
                            @can('sessions_delete')
                                @if ($session->attended_count == 0 && $session->onlines_count == 0)
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-session-btn" title="Delete"
                                        data-session-id="{{ $session->id }}" data-professor-name="{{ $session->professor->name }}"
                                        data-stage="{{ App\Enums\StagesEnum::getStringValue($session->stage) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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
                            <label class="form-label">Prof Papers</label>
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

    <script>
        $(document).ready(function() {
            $(document).off('click', '.close-session-btn').on('click', '.close-session-btn', function(e) {
                e.preventDefault();
                const button = $(this);
                const form = button.closest('form');

                Swal.fire({
                    title: 'Close Session',
                    text: 'Are you sure you want to close this session?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
