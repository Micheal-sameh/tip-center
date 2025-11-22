@extends('layouts.sideBar')

@section('content')
<div class="container">
    <h1>Add to Professor Blacklist</h1>
    <form action="{{ route('professor_blacklists.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="professor_id" class="form-label">Professor</label>
            <select name="professor_id" id="professor_id" class="form-select" required>
                <option value="" disabled selected>Select a Professor</option>
                @foreach($professors as $professor)
                    <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>
                        {{ $professor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select name="student_id" id="student_id" class="form-select" required>
                <option value="" disabled selected>Select a Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                        {{ $student->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea name="reason" id="reason" class="form-control" rows="3" required>{{ old('reason') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add to Blacklist</button>
        <a href="{{ route('professor_blacklists.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
