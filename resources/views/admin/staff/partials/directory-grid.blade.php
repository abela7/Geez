        @forelse ($staff as $member)
        <div class="employee-card">
            <div class="employee-card-header">
                <div class="employee-avatar flex items-center justify-center bg-card">
                    @if ($member->profile && $member->profile->photo_url)
                        <img src="{{ $member->profile->photo_url }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover rounded-full" />
                    @else
                    <i class="fas fa-user text-icons"></i>
                    @endif
                </div>
                <div class="employee-name">
                    <a href="{{ route('admin.staff.profile', $member) }}" class="text-primary hover:text-accent transition-colors">
                        {{ $member->full_name }}
                    </a>
                </div>
                <div class="employee-position">{{ $member->staffType?->display_name ?? __('staff.staff_type') }}</div>
            </div>
            <div class="employee-card-body">
                <div class="employee-info">
                    <div class="employee-info-row">
                        <i class="fas fa-phone employee-info-icon"></i>
                        <span>{{ $member->phone ?? '—' }}</span>
                    </div>
                    <div class="employee-info-row">
                        <i class="fas fa-envelope employee-info-icon"></i>
                        <span>{{ $member->email ?? '—' }}</span>
                    </div>
                    <div class="employee-info-row">
                        @php
                            $statusClass = $member->status === 'active' ? 'active' : ($member->status === 'inactive' ? 'inactive' : 'suspended');
                        @endphp
                        <span class="employee-status {{ $statusClass }}">
                            <i class="fas fa-circle text-[10px]"></i>
                            {{ __('staff.status_values.' . $member->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="text-secondary col-span-full">{{ __('staff.no_staff_found') }}</div>
        @endforelse
