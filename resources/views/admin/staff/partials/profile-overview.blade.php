<div class="profile-overview-grid">
    <!-- Personal Information -->
    <div class="profile-section">
        <div class="profile-info-section">
            <h3 class="profile-section-title">{{ __('staff.personal_info') }}</h3>
            <div class="profile-info-card">
                <div class="profile-info-row">
                    <span class="profile-info-label">{{ __('staff.full_name') }}:</span>
                    <span class="profile-info-value">{{ $staff->full_name }}</span>
                </div>
                <div class="profile-info-row">
                    <span class="profile-info-label">{{ __('staff.username') }}:</span>
                    <span class="profile-info-value">{{ $staff->username }}</span>
                </div>
                @if ($staff->profile && $staff->profile->date_of_birth)
                <div class="profile-info-row">
                    <span class="profile-info-label">{{ __('staff.date_of_birth') }}:</span>
                    <span class="profile-info-value">{{ $staff->profile->date_of_birth->format('M d, Y') }} ({{ $staff->profile->date_of_birth->age }} years)</span>
                </div>
                @endif
                @if ($staff->profile && $staff->profile->address)
                <div class="profile-info-row">
                    <span class="profile-info-label">{{ __('common.address') }}:</span>
                    <span class="profile-info-value">{{ $staff->profile->address }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div>
            <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.contact_info') }}</h3>
            <div class="bg-background border border-main rounded-lg p-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.email') }}:</span>
                    <span class="text-primary font-medium">{{ $staff->email ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.phone') }}:</span>
                    <span class="text-primary font-medium">{{ $staff->phone ?? '—' }}</span>
                </div>
            </div>
        </div>

        <!-- Emergency Contacts -->
        @if ($staff->profile && $staff->profile->emergency_contacts)
        <div>
            <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.emergency_contacts') }}</h3>
            <div class="bg-background border border-main rounded-lg p-4 space-y-3">
                @foreach ($staff->profile->emergency_contacts as $contact)
                <div class="border-b border-main pb-3 last:border-b-0 last:pb-0">
                    <div class="flex justify-between">
                        <span class="text-secondary">{{ __('common.name') }}:</span>
                        <span class="text-primary font-medium">{{ $contact['name'] ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-secondary">{{ __('staff.relationship') }}:</span>
                        <span class="text-primary font-medium">{{ $contact['relationship'] ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-secondary">{{ __('common.phone') }}:</span>
                        <span class="text-primary font-medium">{{ $contact['phone'] ?? '—' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Employment Information -->
    <div class="space-y-6">
        <div>
            <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.employment_info') }}</h3>
            <div class="bg-background border border-main rounded-lg p-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.staff_type') }}:</span>
                    <span class="text-primary font-medium">{{ $staff->staffType?->display_name ?? '—' }}</span>
                </div>
                @if ($staff->profile && $staff->profile->employee_id)
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.employee_id') }}:</span>
                    <span class="text-primary font-medium">{{ $staff->profile->employee_id }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.hire_date') }}:</span>
                    <span class="text-primary font-medium">{{ $staff->hire_date ? $staff->hire_date->format('M d, Y') : '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.status') }}:</span>
                    <span class="text-primary font-medium">{{ __('staff.status_values.' . $staff->status) }}</span>
                </div>
                @if ($staff->profile && $staff->profile->hourly_rate)
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.hourly_rate') }}:</span>
                    <span class="text-primary font-medium">${{ number_format($staff->profile->hourly_rate, 2) }}/hr</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Account Information -->
        <div>
            <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.account_info') }}</h3>
            <div class="bg-background border border-main rounded-lg p-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.last_login') }}:</span>
                    <span class="text-primary font-medium">
                        @if ($staff->last_login_at)
                            {{ $staff->last_login_at->format('M d, Y H:i') }}
                        @else
                            {{ __('staff.never_logged_in') }}
                        @endif
                    </span>
                </div>
                @if ($staff->last_login_ip)
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.last_login_ip') }}:</span>
                    <span class="text-primary font-medium">{{ $staff->last_login_ip }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-secondary">{{ __('staff.member_since') }}:</span>
                    <span class="text-primary font-medium">{{ $staff->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if ($staff->profile && $staff->profile->notes)
        <div>
            <h3 class="text-lg font-semibold text-primary mb-4">{{ __('common.notes') }}</h3>
            <div class="bg-background border border-main rounded-lg p-4">
                <p class="text-primary">{{ $staff->profile->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
