        @forelse ($staff as $member)
        <div class="bg-card border border-main rounded-lg p-4 flex items-center justify-between">
            <div class="flex items-center gap-md min-w-0">
                <div class="w-10 h-10 rounded-full bg-card border border-main flex items-center justify-center overflow-hidden">
                    @if ($member->profile && $member->profile->photo_url)
                        <img src="{{ $member->profile->photo_url }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover" />
                    @else
                        <i class="fas fa-user text-muted"></i>
                    @endif
                </div>
                <div class="min-w-0">
                    <div class="font-medium text-primary truncate">
                        <a href="{{ route('admin.staff.profile', $member) }}" class="text-primary hover:text-accent transition-colors">
                            {{ $member->full_name }}
                        </a>
                    </div>
                    <div class="text-sm text-secondary truncate">{{ $member->staffType?->display_name ?? __('staff.staff_type') }}</div>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-lg text-sm text-secondary">
                <div class="truncate">{{ $member->email ?? '—' }}</div>
                <div>{{ $member->phone ?? '—' }}</div>
                @php
                    $statusClass = $member->status === 'active' ? 'active' : ($member->status === 'inactive' ? 'inactive' : 'suspended');
                @endphp
                <div class="employee-status {{ $statusClass }}"><i class="fas fa-circle text-[10px]"></i> {{ __('staff.status_values.' . $member->status) }}</div>
            </div>
        </div>
        @empty
            <div class="text-secondary">{{ __('staff.no_staff_found') }}</div>
        @endforelse
