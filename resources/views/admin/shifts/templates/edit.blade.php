@extends('layouts.admin')

@section('title', __('admin.shifts.templates.edit_title'))

@section('content')
<div class="shift-template-edit-page" x-data="templateEditData({{ json_encode($template) }})">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-header-left">
                <h1 class="page-title">{{ __('admin.shifts.templates.edit_title') }}</h1>
                <p class="page-description">{{ __('admin.shifts.templates.edit_description') }}</p>
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

    <!-- Edit Form -->
    <div class="form-container">
        <form action="{{ route('admin.shifts.shifts.templates.update', $template->id) }}" method="POST" class="template-form">
            @csrf
            @method('PUT')
            
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
                        <p class="section-description">Update template details and settings</p>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Template Name</label>
                        <input type="text" id="name" name="name" class="form-input" 
                               value="{{ old('name', $template->name) }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="type" class="form-label">Template Type</label>
                        <select id="type" name="type" class="form-select" required>
                            @foreach($templateTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $template->type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-textarea" rows="3">{{ old('description', $template->description) }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                        <span class="checkbox-text">Active Template</span>
                        <span class="checkbox-description">Only active templates can be applied to schedules</span>
                    </label>
                </div>
            </div>

            <!-- Template Assignments -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="section-content">
                        <h2 class="section-title">Template Assignments</h2>
                        <p class="section-description">Manage staff assignments for each day of the week</p>
                    </div>
                </div>
                
                <div class="assignments-grid">
                    @foreach($daysOfWeek as $dayNumber => $dayName)
                        <div class="day-assignments">
                            <h3 class="day-title">{{ $dayName }}</h3>
                            <div class="assignments-list">
                                @php
                                    $dayAssignments = $template->assignments->where('day_of_week', $dayNumber);
                                @endphp
                                
                                @if($dayAssignments->count() > 0)
                                    @foreach($dayAssignments as $assignment)
                                        <div class="assignment-item">
                                            <div class="assignment-info">
                                                <div class="staff-name">{{ $assignment->staff?->full_name ?? 'Unassigned' }}</div>
                                                <div class="shift-name">{{ $assignment->shift?->name ?? 'Unknown Shift' }}</div>
                                            </div>
                                            <div class="assignment-status">
                                                <span class="status-badge status-{{ $assignment->status }}">
                                                    {{ ucfirst($assignment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state">
                                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <p class="empty-text">No assignments for {{ $dayName }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.shifts.shifts.templates.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Template
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
function templateEditData(template) {
    return {
        template: template,
        
        init() {
            // Initialize any needed functionality
        }
    };
}
</script>
@endpush
@endsection
