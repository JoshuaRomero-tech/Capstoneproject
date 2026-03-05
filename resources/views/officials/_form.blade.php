<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Resident <span class="text-danger">*</span></label>
        <select name="resident_id" class="form-select" required>
            <option value="">-- Select Resident --</option>
            @foreach($residents as $resident)
                <option value="{{ $resident->id }}" {{ old('resident_id', $official->resident_id ?? '') == $resident->id ? 'selected' : '' }}>
                    {{ $resident->full_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Position <span class="text-danger">*</span></label>
        <select name="position" class="form-select" required>
            <option value="">-- Select Position --</option>
            @foreach(['Barangay Captain','Barangay Kagawad','SK Chairperson','SK Kagawad','Barangay Secretary','Barangay Treasurer'] as $pos)
                <option value="{{ $pos }}" {{ old('position', $official->position ?? '') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Committee</label>
        <input type="text" name="committee" class="form-control" value="{{ old('committee', $official->committee ?? '') }}" placeholder="e.g., Peace & Order">
    </div>
    <div class="col-md-3">
        <label class="form-label">Term Start <span class="text-danger">*</span></label>
        <input type="date" name="term_start" class="form-control" value="{{ old('term_start', isset($official) ? $official->term_start->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Term End <span class="text-danger">*</span></label>
        <input type="date" name="term_end" class="form-control" value="{{ old('term_end', isset($official) ? $official->term_end->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            <option value="Active" {{ old('status', $official->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status', $official->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>
