<h6 class="text-muted mb-3"><i class="bi bi-person me-1"></i> Personal Information</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <label class="form-label">First Name <span class="text-danger">*</span></label>
        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $resident->first_name ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Middle Name</label>
        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $resident->middle_name ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Last Name <span class="text-danger">*</span></label>
        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $resident->last_name ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Suffix</label>
        <input type="text" name="suffix" class="form-control" placeholder="Jr., Sr., III" value="{{ old('suffix', $resident->suffix ?? '') }}">
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', isset($resident) ? $resident->date_of_birth->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Place of Birth</label>
        <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth', $resident->place_of_birth ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Gender <span class="text-danger">*</span></label>
        <select name="gender" class="form-select" required>
            <option value="">Select</option>
            <option value="Male" {{ old('gender', $resident->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $resident->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Civil Status <span class="text-danger">*</span></label>
        <select name="civil_status" class="form-select" required>
            @foreach(['Single','Married','Widowed','Separated','Divorced'] as $status)
                <option value="{{ $status }}" {{ old('civil_status', $resident->civil_status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <label class="form-label">Nationality <span class="text-danger">*</span></label>
        <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $resident->nationality ?? 'Filipino') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Religion</label>
        <input type="text" name="religion" class="form-control" value="{{ old('religion', $resident->religion ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Contact Number</label>
        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $resident->contact_number ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $resident->email ?? '') }}">
    </div>
</div>

<h6 class="text-muted mb-3"><i class="bi bi-geo-alt me-1"></i> Address & Household</h6>
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <label class="form-label">Address <span class="text-danger">*</span></label>
        <textarea name="address" class="form-control" rows="2" required>{{ old('address', $resident->address ?? '') }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">Household</label>
        <select name="household_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($households as $household)
                <option value="{{ $household->id }}" {{ old('household_id', $resident->household_id ?? '') == $household->id ? 'selected' : '' }}>
                    {{ $household->household_no }} - {{ Str::limit($household->address, 30) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<h6 class="text-muted mb-3"><i class="bi bi-briefcase me-1"></i> Other Information</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Occupation</label>
        <input type="text" name="occupation" class="form-control" value="{{ old('occupation', $resident->occupation ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Educational Attainment</label>
        <select name="educational_attainment" class="form-select">
            <option value="">Select</option>
            @foreach(['Elementary','High School','Senior High School','Vocational','College','Post-Graduate'] as $edu)
                <option value="{{ $edu }}" {{ old('educational_attainment', $resident->educational_attainment ?? '') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Voter Status <span class="text-danger">*</span></label>
        <select name="voter_status" class="form-select" required>
            <option value="Not Registered" {{ old('voter_status', $resident->voter_status ?? '') == 'Not Registered' ? 'selected' : '' }}>Not Registered</option>
            <option value="Registered" {{ old('voter_status', $resident->voter_status ?? '') == 'Registered' ? 'selected' : '' }}>Registered</option>
        </select>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">PhilHealth No.</label>
        <input type="text" name="philhealth_no" class="form-control" value="{{ old('philhealth_no', $resident->philhealth_no ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">SSS No.</label>
        <input type="text" name="sss_no" class="form-control" value="{{ old('sss_no', $resident->sss_no ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">TIN No.</label>
        <input type="text" name="tin_no" class="form-control" value="{{ old('tin_no', $resident->tin_no ?? '') }}">
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="form-check mt-4">
            <input type="hidden" name="is_pwd" value="0">
            <input type="checkbox" name="is_pwd" value="1" class="form-check-input" {{ old('is_pwd', $resident->is_pwd ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">PWD</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-check mt-4">
            <input type="hidden" name="is_solo_parent" value="0">
            <input type="checkbox" name="is_solo_parent" value="1" class="form-check-input" {{ old('is_solo_parent', $resident->is_solo_parent ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Solo Parent</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-check mt-4">
            <input type="hidden" name="is_senior_citizen" value="0">
            <input type="checkbox" name="is_senior_citizen" value="1" class="form-check-input" {{ old('is_senior_citizen', $resident->is_senior_citizen ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Senior Citizen</label>
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*">
    </div>
    @if(isset($editing))
    <div class="col-md-3">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            @foreach(['Active','Inactive','Deceased'] as $s)
                <option value="{{ $s }}" {{ old('status', $resident->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    @endif
</div>
