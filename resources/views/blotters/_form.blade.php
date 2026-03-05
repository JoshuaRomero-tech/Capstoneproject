<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Complainant <span class="text-danger">*</span></label>
        <select name="complainant_id" class="form-select" required>
            <option value="">-- Select Complainant --</option>
            @foreach($residents as $resident)
                <option value="{{ $resident->id }}" {{ old('complainant_id', $blotter->complainant_id ?? '') == $resident->id ? 'selected' : '' }}>
                    {{ $resident->full_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Respondent <span class="text-danger">*</span></label>
        <select name="respondent_id" class="form-select" required>
            <option value="">-- Select Respondent --</option>
            @foreach($residents as $resident)
                <option value="{{ $resident->id }}" {{ old('respondent_id', $blotter->respondent_id ?? '') == $resident->id ? 'selected' : '' }}>
                    {{ $resident->full_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Incident Type <span class="text-danger">*</span></label>
        <select name="incident_type" class="form-select" required>
            <option value="">-- Select Type --</option>
            @foreach(['Physical Assault', 'Verbal Abuse', 'Theft', 'Property Damage', 'Noise Complaint', 'Trespassing', 'Domestic Violence', 'Others'] as $type)
                <option value="{{ $type }}" {{ old('incident_type', $blotter->incident_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Incident Date <span class="text-danger">*</span></label>
        <input type="date" name="incident_date" class="form-control" value="{{ old('incident_date', isset($blotter) ? $blotter->incident_date->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Incident Location <span class="text-danger">*</span></label>
        <input type="text" name="incident_location" class="form-control" value="{{ old('incident_location', $blotter->incident_location ?? '') }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Incident Details <span class="text-danger">*</span></label>
        <textarea name="incident_details" class="form-control" rows="4" required>{{ old('incident_details', $blotter->incident_details ?? '') }}</textarea>
    </div>

    @if(isset($editing))
    <div class="col-md-4">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach(['Pending','Ongoing','Resolved','Dismissed'] as $status)
                <option value="{{ $status }}" {{ old('status', $blotter->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        <label class="form-label">Action Taken</label>
        <textarea name="action_taken" class="form-control" rows="1">{{ old('action_taken', $blotter->action_taken ?? '') }}</textarea>
    </div>
    @endif

    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $blotter->remarks ?? '') }}</textarea>
    </div>
</div>
