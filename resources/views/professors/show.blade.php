@extends('layouts.sideBar')

@section('content')
    <div class="container py-5" style="width: 90%;">

        <!-- professor Details Card -->
        <div class="card border-0 shadow rounded-4 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-primary text-white px-4 py-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-professor-circle fa-2x me-3"></i>
                    <h4 class="mb-0 me-2">{{ __('trans.professor_details') }}</h4>
                </div>
                <a href="{{ route('professors.index') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('trans.back') }}
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body p-4">
                <div class="row">
                    <!-- professor Avatar -->
                    {{-- <div class="col-md-4 text-center mb-4">
                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mx-auto" style="width: 140px; height: 140px;">
                        <i class="fas fa-professor fa-3x text-secondary"></i>
                    </div> --}}
                    <h5 class="mt-3 text-primary fw-bold">{{ $professor->name }}</h5>
                </div>

                    <!-- professor Info -->
                    <div class="col-md-8">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th class="text-muted">{{ __('trans.phone') }}</th>
                                <td class="text-dark">{{ $professor->phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('trans.optional_phone') }}</th>
                                <td class="text-dark">{{ $professor->optional_phone }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th class="text-muted">{{ __('trans.birth_date') }}</th>
                                <td class="text-dark">{{ $professor->birth_date }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th class="text-muted">{{ __('trans.school') }}</th>
                                <td class="text-dark"> {{ $professor->school }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th class="text-muted">{{ __('trans.subject') }}</th>
                                <td class="text-dark"> {{ $professor->subject }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('trans.status') }}</th>
                                <td>
                                    @if ($professor->status)
                                        <span class="badge bg-success px-3 py-1">{{ __('trans.active') }}</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-1">{{ __('trans.inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end mt-4 gap-2">
                    @can('professors_update')
                        <a href="{{ route('professors.edit', $professor->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> {{ __('trans.edit') }}
                        </a>
                    @endcan
                    @can('professors_delete')
                        <form action="{{ route('professors.delete', $professor->id) }}" method="POST"
                            onsubmit="return confirm('{{ __('trans.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> {{ __('trans.delete') }}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

    </div>
@endsection
