@extends('layouts.sideBar')

@section('content')
<div class="container py-4" style="max-width: 700px;">
    <div class="card shadow rounded-4 p-4">
        <h3 class="mb-4">Add to Professor Blacklist</h3>

        <form action="{{ route('professor_blacklists.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="professor_id" class="form-label">Professor</label>
                <select class="form-select @error('professor_id') is-invalid @enderror" name="professor_id" id="professor_id" required>
                    <option value="">Select Professor</option>
                    @foreach($professors as $professor)
                        <option value="{{ $professor->id }}"
                            {{ (old('professor_id') == $professor->id || request()->get('professor_id') == $professor->id) ? 'selected' : '' }}>
                            {{ $professor->name }}
                        </option>
                    @endforeach
                </select>
                @error('professor_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select class="form-select @error('student_id') is-invalid @enderror" name="student_id" id="student_id" required>
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ (old('student_id') == $student->id || request()->get('student_id') == $student->id) ? 'selected' : '' }}>
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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#professor_id').change(function() {
            var professorId = $(this).val();
            var isStudentPreselected = "{{ old('student_id') ?? request()->get('student_id') }}";
            if (isStudentPreselected) {
                // If a student is preselected, do not fetch students dynamically
                return;
            }

            if (professorId) {
                $.ajax({
                    url: "{{ url('professor_blacklists/get-students-by-professor') }}/" + professorId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(students) {
                        var studentSelect = $('#student_id');
                        studentSelect.empty();
                        studentSelect.append('<option value="">Select Student</option>');

                        $.each(students, function(index, student) {
                            studentSelect.append(
                                $('<option></option>').val(student.id).text(student.name)
                            );
                        });

                        var selectedStudentId = "{{ old('student_id') ?? request()->get('student_id') }}";
                        if (selectedStudentId) {
                            studentSelect.val(selectedStudentId);
                        }
                    }
                });
            } else {
                $('#student_id').empty().append('<option value="">Select Student</option>');
            }
        });
    });
</script>
@endpush
