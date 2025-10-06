        @forelse ($staff as $member)
        <div class="employee-card relative overflow-hidden group">
            <!-- Action Bar - Hidden by default, shows on hover -->
            <div class="action-bar absolute top-0 left-0 right-0 bg-gradient-to-r from-primary/90 to-accent/90 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 z-10 flex justify-end p-2 gap-1">
                <!-- View -->
                <a href="{{ route('admin.staff.show', $member) }}" 
                   class="action-btn bg-white/20 hover:bg-white/30 text-white p-2 rounded-full transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-110" 
                   title="{{ __('common.view') }}" 
                   aria-label="{{ __('common.view') }}">
                    <i class="fas fa-eye text-sm"></i>
                </a>
                <!-- Edit -->
                <a href="{{ route('admin.staff.edit', $member) }}" 
                   class="action-btn bg-white/20 hover:bg-white/30 text-white p-2 rounded-full transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-110" 
                   title="{{ __('common.edit') }}" 
                   aria-label="{{ __('common.edit') }}">
                    <i class="fas fa-pencil-alt text-sm"></i>
                </a>
                <!-- Delete -->
                <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('common.confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="action-btn bg-white/20 hover:bg-red-500/80 text-white p-2 rounded-full transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-110" 
                            title="{{ __('common.delete') }}" 
                            aria-label="{{ __('common.delete') }}">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
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
