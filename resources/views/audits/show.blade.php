@extends('layouts.sideBar')

@section('content')
    <div class="container-fluid py-3" style="max-width:960px">

        <div class="tc-page-header mb-4">
            <div>
                <h1 class="tc-page-title"><i class="fas fa-eye me-2 text-brand"></i>Audit Details</h1>
                <p class="text-muted" style="font-size:.85rem;margin:0;">
                    {{ ucfirst($audit->table_name) }} &middot; #{{ $audit->record_id }} &middot; {{ $audit->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <a href="{{ route('audits.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        {{-- Audit Info Card --}}
        <div class="tc-table-wrap mb-4" style="padding:1.25rem;">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="tc-stat-label">Table</div>
                    <div class="fw-600">{{ ucfirst($audit->table_name) }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="tc-stat-label">Record ID</div>
                    <div class="fw-600"><code>#{{ $audit->record_id }}</code></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="tc-stat-label">User</div>
                    <div class="fw-600">{{ $audit->user?->name ?? 'System' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="tc-stat-label">Event</div>
                    @php $ev = $audit->event ?? 'update'; $evBadge = match($ev){ 'created'=>'tc-badge-success','deleted'=>'tc-badge-danger',default=>'tc-badge-warning' }; @endphp
                    <span class="tc-badge {{ $evBadge }}">{{ ucfirst($ev) }}</span>
                </div>
        {{-- Data Comparison --}}
        <div class="tc-table-wrap">
            <div class="tc-data-bar"><span><i class="fas fa-exchange-alt me-2"></i>Data Changes</span></div>
                @php
                    $allKeys = array_unique(array_merge(array_keys($audit->old_data ?? []), array_keys($audit->new_data ?? [])));
                @endphp
                @if(count($allKeys) > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allKeys as $key)
                                    @php
                                        $oldValue = $audit->old_data[$key] ?? null;
                                        $newValue = $audit->new_data[$key] ?? null;
                                        $changed = $oldValue !== $newValue;
                                        $status = '';
                                        if ($oldValue === null && $newValue !== null) {
                                            $status = 'Added';
                                        } elseif ($oldValue !== null && $newValue === null) {
                                            $status = 'Removed';
                                        } elseif ($changed) {
                                            $status = 'Changed';
                                        } else {
                                            $status = 'Unchanged';
                                        }

                                        // Display logic for IDs
                                        $oldDisplay = $oldValue;
                                        $newDisplay = $newValue;
                                        if ($audit->table_name == 'sessions' && $key == 'professor_id') {
                                            $profOld = \App\Models\Professor::find($oldValue);
                                            $oldDisplay = $profOld ? $profOld->name : $oldValue;
                                            $profNew = \App\Models\Professor::find($newValue);
                                            $newDisplay = $profNew ? $profNew->name : $newValue;
                                        } elseif (in_array($audit->table_name, ['session_students', 'students']) && $key == 'student_id') {
                                            $studOld = \App\Models\Student::find($oldValue);
                                            $oldDisplay = $studOld ? $studOld->name : $oldValue;
                                            $studNew = \App\Models\Student::find($newValue);
                                            $newDisplay = $studNew ? $studNew->name : $newValue;
                                        } elseif (in_array($audit->table_name, ['sessions', 'professor_stages']) && $key == 'stage') {
                                            try {
                                                $oldDisplay = \App\Enums\StagesEnum::getStringValue($oldValue);
                                            } catch (\Exception $e) {
                                                $oldDisplay = $oldValue;
                                            }
                                            try {
                                                $newDisplay = \App\Enums\StagesEnum::getStringValue($newValue);
                                            } catch (\Exception $e) {
                                                $newDisplay = $newValue;
                                            }
                                        }
                                        if (is_array($oldDisplay)) $oldDisplay = 0;
                                        if (is_array($newDisplay)) $newDisplay = 0;
                                        // if (is_array($newDisplay)) $newDisplay = json_encode($newDisplay);
                                    @endphp
                                    <tr class="{{ $changed ? 'audit-row-update' : '' }}">
                                        <td><code>{{ $key }}</code></td>
                                        <td class="text-muted">{{ $oldDisplay }}</td>
                                        <td class="fw-600">{{ $newDisplay }}</td>
                                        <td>
                                            <span class="tc-badge {{ $status == 'Changed' ? 'tc-badge-warning' : ($status == 'Added' ? 'tc-badge-success' : ($status == 'Removed' ? 'tc-badge-danger' : 'tc-badge-neutral')) }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted p-3">No data changes recorded.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
