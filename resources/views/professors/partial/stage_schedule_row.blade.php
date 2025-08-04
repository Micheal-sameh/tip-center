<div class="stage-row row g-2">
    <div class="col-md-4">
        <label class="form-label">Stage</label>
        <select name="stage_schedules[{{ $index }}][stage]" class="form-select" required>
            <option value="">Choose...</option>
            @foreach (App\Enums\StagesEnum::all() as $stage)
                <option value="{{ $stage['value'] }}"
                    {{ (old("stage_schedules.$index.stage") ?? ($data['stage'] ?? '')) == $stage['value'] ? 'selected' : '' }}>
                    {{ $stage['name'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Day</label>
        <select name="stage_schedules[{{ $index }}][day]" class="form-select" required>
            <option value="">Choose...</option>
            @foreach ([
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ] as $dayValue => $dayName)
                <option value="{{ $dayValue }}"
                    {{ (old("stage_schedules.$index.day") ?? ($data['day'] ?? '')) == $dayValue ? 'selected' : '' }}>
                    {{ $dayName }}
                </option>
            @endforeach
        </select>
    </div>


    <div class="col-md-2">
        <label class="form-label">From</label>
        <input type="time" name="stage_schedules[{{ $index }}][from]" class="form-control"
            value="{{ old("stage_schedules.$index.from") ?? ($data['from'] ?? '') }}" required>
    </div>

    <div class="col-md-2">
        <label class="form-label">To</label>
        <input type="time" name="stage_schedules[{{ $index }}][to]" class="form-control"
            value="{{ old("stage_schedules.$index.to") ?? ($data['to'] ?? '') }}" required>
    </div>

    <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-sm btn-danger remove-stage-row">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>
