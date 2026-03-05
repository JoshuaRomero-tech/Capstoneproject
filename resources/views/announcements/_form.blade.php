<div class="row">
    <div class="col-lg-8">
        <div class="mb-3">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $announcement->title ?? '') }}" required placeholder="Announcement title">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Content <span class="text-danger">*</span></label>
            <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                      rows="8" required placeholder="Write your announcement here...">{{ old('content', $announcement->content ?? '') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-lg-4">
        <div class="mb-3">
            <label class="form-label">Priority <span class="text-danger">*</span></label>
            <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                @foreach(['Normal', 'Important', 'Urgent'] as $priority)
                    <option value="{{ $priority }}" {{ old('priority', $announcement->priority ?? 'Normal') == $priority ? 'selected' : '' }}>
                        {{ $priority }}
                    </option>
                @endforeach
            </select>
            @error('priority')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Publish Date <span class="text-danger">*</span></label>
            <input type="date" name="publish_date" class="form-control @error('publish_date') is-invalid @enderror"
                   value="{{ old('publish_date', isset($announcement) ? $announcement->publish_date->format('Y-m-d') : date('Y-m-d')) }}" required>
            @error('publish_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror"
                   value="{{ old('expiry_date', isset($announcement) && $announcement->expiry_date ? $announcement->expiry_date->format('Y-m-d') : '') }}">
            @error('expiry_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Leave blank for no expiration.</div>
        </div>

        <div class="mb-3">
            <div class="form-check form-switch">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                       {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active (visible to public)</label>
            </div>
        </div>
    </div>
</div>
