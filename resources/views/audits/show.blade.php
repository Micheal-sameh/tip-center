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
            <h4><i class="fas fa-eye me-2"></i>Audit Details</h4>
            <a href="{{ route('audits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Audits
            </a>
        </div>

        {{-- Audit Info --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Audit Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Table:</strong> {{ ucfirst($audit->table_name) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Record ID:</strong> {{ $audit->record_id }}
                    </div>
                    <div class="col-md-3">
                        <strong>User:</strong> {{ $audit->user ? $audit->user->name : 'System' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Updated At:</strong> {{ $audit->created_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Comparison --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Changes</h5>
            </div>
            <div class="card-body">
                @php
                    $allKeys = array_unique(array_merge(array_keys($audit->old_data ?? []), array_keys($audit->new_data ?? [])));
                @endphp
                @if(count($allKeys) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                    <tr class="{{ $changed ? 'table-warning' : '' }}">
                                        <td>{{ $key }}</td>
                                        <td>{{ $oldDisplay }}</td>
                                        <td>{{ $newDisplay }}</td>
                                        <td>
                                            <span class="badge {{ $status == 'Changed' ? 'bg-warning' : ($status == 'Added' ? 'bg-success' : ($status == 'Removed' ? 'bg-danger' : 'bg-secondary')) }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No data changes recorded.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
