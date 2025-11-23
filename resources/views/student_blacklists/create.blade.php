@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="max-width: 700px;">
    <div class="card shadow rounded-4 p-4">
        <h3 class="mb-4">Add to Student Blacklist</h3>

        <form action="{{ route('student_blacklists.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select class="form-select @error('student_id') is-invalid @enderror" name="student_id" id="student_id" required>
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}"
                            {{ (old('student_id', request()->query('student_id')) == $student->id) ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
                @error('student_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason</label>
                <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                @error('reason')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-danger rounded-pill px-5">Add to Blacklist</button>
        </form>
    </div>
</div>
@endsection
