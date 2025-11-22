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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const professorSelect = document.getElementById('professor_id');
        const studentSelect = document.getElementById('student_id');

        async function loadStudents(professorId) {
            if (!professorId) {
                studentSelect.innerHTML = '<option value="" disabled selected>Select a Student</option>';
                return;
            }
            try {
                const response = await axios.get(`/professor_blacklists/get-students-by-professor/${professorId}`);
                const students = response.data;

                let options = '<option value="" disabled selected>Select a Student</option>';
                students.forEach(student => {
                    options += `<option value="${student.id}">${student.name}</option>`;
                });
                studentSelect.innerHTML = options;

                // If old student_id exists, select it
                const oldStudentId = "{{ old('student_id') }}";
                if (oldStudentId) {
                    studentSelect.value = oldStudentId;
                }
            } catch (error) {
                console.error('Error loading students:', error);
                studentSelect.innerHTML = '<option value="" disabled selected>Error loading students</option>';
            }
        }

        // Initial load if professor is pre-selected
        if (professorSelect.value) {
            loadStudents(professorSelect.value);
        }

        professorSelect.addEventListener('change', function () {
            loadStudents(this.value);
        });
    });
</script>
@endpush
