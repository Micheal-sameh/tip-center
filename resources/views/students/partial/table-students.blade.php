{{-- Desktop Table --}}
<div class="d-none d-md-block">
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">{{ __('Name') }}</th>
                        <th>{{ __('Stage') }}</th>
                        <th>{{ __('Contact') }}</th>
                        <th>{{ __('Parent') }}</th>
                        <th>{{ __('Note') }}</th>
                        <th class="text-end pe-4">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    {{-- <div class="avatar avatar-sm me-3">
                                        <span class="avatar-text bg-primary rounded-circle">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </span>
                                    </div> --}}
                                    <a href="{{ route('students.show', $student) }}"
                                        class="d-block text-dark text-hover-primary fw-semibold">
                                        <div>
                                            <h6 class="mb-0">
                                                {{ $student->name }}
                                                <i class="fas fa-arrow-right ms-2 text-muted small"></i>
                                            </h6>
                                            <small class="text-muted">code: {{ $student->code }}</small>
                                        </div>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ App\Enums\StagesEnum::getStringValue($student->stage) }}
                                </span>
                            </td>
                            <td>
                                @if ($student->phone)
                                    <a href="tel:{{ $student->phone }}" class="text-dark">
                                        <i class="fas fa-phone me-2"></i>{{ $student->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if ($student->parent_phone)
                                    <a href="tel:{{ $student->parent_phone }}" class="text-dark">
                                        <i class="fas fa-user-tie me-2"></i>{{ $student->parent_phone }}
                                        @if ($student->parent_phone_2)
                                            <br><i class="fas fa-user-tie me-2"></i>{{ $student->parent_phone_2 }}
                                        @endif
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-wrap text-break" style="max-width: 250px; white-space: normal;">
                                @if ($student->note)
                                    {{ $student->note }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('students.show', $student) }}"
                                        class="btn btn-sm btn-outline-info" title="{{ __('trans.view') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-secondary"
                                        title="{{ __('trans.Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('No students found') }}</h5>
                                    <p class="text-muted small">
                                        {{ __('Add your first student by clicking the button above') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="d-md-none">
    @forelse($students as $student)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-lg me-3">
                        {{-- <span class="avatar-text bg-primary rounded-circle">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </span> --}}
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $student->name }}</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ App\Enums\StagesEnum::getStringValue($student->stage) }}
                        </span>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center text-muted">
                            <i class="fas fa-phone me-2"></i>
                            <small>{{ $student->phone ?: 'N/A' }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center text-muted">
                            <i class="fas fa-user-tie me-2"></i>
                            <small>{{ $student->parent_phone ?: 'N/A' }}</small> <br>
                        </div>
                        @if ($student->parent_phone_2)
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-user-tie me-2"></i>
                                <small>{{ $student->parent_phone_2 ?: 'N/A' }}</small> <br>
                            </div>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center text-muted">
                            <small class="text-wrap text-break" style="word-break: break-word; white-space: normal;">
                                {{ $student->note ?: 'N/A' }}
                            </small>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> {{ __('View') }}
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            id="mobileDropdown{{ $student->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="mobileDropdown{{ $student->id }}">
                            <li>
                                <a class="dropdown-item" href="{{ route('students.edit', $student) }}">
                                    <i class="fas fa-edit me-2"></i>{{ __('Edit') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('No students found') }}</h5>
                <p class="text-muted small mb-0">{{ __('Add your first student by clicking the button above') }}
                </p>
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center pt-2">
    @if ($students->hasPages())
        <nav>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($students->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $students->previousPageUrl() }}" rel="prev">&laquo;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                    <li class="page-item {{ $students->currentPage() === $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Next Page Link --}}
                @if ($students->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $students->nextPageUrl() }}" rel="next">&raquo;</a>
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
