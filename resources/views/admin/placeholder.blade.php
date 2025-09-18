@extends('layouts.admin')

@section('content')
<div class="placeholder-page">
    <div class="placeholder-content">
        <div class="placeholder-icon">
            <i class="fas fa-tools"></i>
        </div>
        <h1 class="placeholder-title">{{ $module ?? 'Module' }}</h1>
        <p class="placeholder-description">
            {{ __('common.coming_soon_description', ['module' => $module ?? 'This module']) }}
        </p>
        <div class="placeholder-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-primary">
                <i class="fas fa-home"></i>
                {{ __('common.back_to_dashboard') }}
            </a>
            <a href="javascript:history.back()" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                {{ __('common.go_back') }}
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.placeholder-page {
    padding: var(--nav-item-padding);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
}

.placeholder-content {
    text-align: center;
    max-width: 500px;
    padding: 3rem 2rem;
    background: var(--color-surface-card);
    border: 1px solid var(--color-border-base);
    border-radius: var(--nav-item-radius);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.placeholder-icon {
    font-size: 4rem;
    color: var(--color-primary);
    margin-bottom: 1.5rem;
    opacity: 0.8;
}

.placeholder-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 1rem 0;
}

.placeholder-description {
    font-size: 1rem;
    color: var(--color-text-secondary);
    line-height: 1.6;
    margin: 0 0 2rem 0;
}

.placeholder-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: var(--nav-item-radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition-all);
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: var(--button-primary-bg);
    color: var(--button-primary-text);
    box-shadow: var(--button-primary-shadow);
}

.btn-primary:hover {
    background: var(--button-primary-hover-bg);
    box-shadow: var(--button-primary-hover-shadow);
    transform: var(--hover-scale);
}

.btn-secondary {
    background: var(--button-secondary-bg);
    color: var(--button-secondary-text);
    box-shadow: var(--button-secondary-shadow);
}

.btn-secondary:hover {
    background: var(--button-secondary-hover-bg);
    transform: var(--hover-scale);
}

/* Responsive Design */
@media (max-width: 768px) {
    .placeholder-content {
        padding: 2rem 1.5rem;
        margin: 1rem;
    }
    
    .placeholder-title {
        font-size: 1.5rem;
    }
    
    .placeholder-icon {
        font-size: 3rem;
    }
    
    .placeholder-actions {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endpush