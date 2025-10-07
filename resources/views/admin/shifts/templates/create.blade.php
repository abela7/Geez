@extends('layouts.admin')

@section('title', __('admin.shifts.templates.create_title'))

@section('content')
<div class="shift-template-create-page" x-data="templateCreateData()">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('admin.shifts.templates.create_title') }}</h1>
                <p class="page-description">{{ __('admin.shifts.templates.create_description') }}</p>
            </div>
            <div class="page-header-right">
                <div class="header-actions">
                    <a href="{{ route('admin.shifts.shifts.templates.index') }}" class="btn btn-ghost">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Templates
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="form-container">
        <form action="{{ route('admin.shifts.shifts.templates.store') }}" method="POST" class="template-form">
            @csrf
            
            <!-- Basic Information -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Template Information</h2>
                        <p class="section-description">Create a new shift template for your schedules</p>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Template Name</label>
                        <input type="text" id="name" name="name" class="form-input" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="type" class="form-label">Template Type</label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="standard" {{ old('type') == 'standard' ? 'selected' : '' }}>Standard</option>
                            <option value="holiday" {{ old('type') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                            <option value="seasonal" {{ old('type') == 'seasonal' ? 'selected' : '' }}>Seasonal</option>
                            <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('type')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="checkbox-text">Active Template</span>
                        <span class="checkbox-description">Only active templates can be applied to schedules</span>
                    </label>
                </div>
            </div>

            <!-- Template Preview -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Template Preview</h2>
                        <p class="section-description">This template will be created empty. You can add assignments after creation.</p>
                    </div>
                </div>
                
                <div class="preview-card">
                    <div class="preview-content">
                        <div class="preview-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="preview-text">
                            <h3 class="preview-title">Empty Template</h3>
                            <p class="preview-description">This template will be created without any assignments. You can add staff assignments after creation by editing the template.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.shifts.shifts.templates.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Template
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/shifts/templates.css') }}">
@endpush

@push('scripts')
<script>
function templateCreateData() {
    return {
        init() {
            // Initialize any needed functionality
        }
    };
}
</script>
@endpush
@endsection
