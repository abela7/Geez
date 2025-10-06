        @forelse ($staff as $member)
        <div class="bg-card border border-main rounded-lg p-4 flex items-center justify-between relative group overflow-hidden">
            <!-- Action Bar - Hidden by default, shows on hover -->
            <div class="action-bar absolute right-0 top-0 bottom-0 bg-gradient-to-b from-primary/90 to-accent/90 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 z-10 flex items-center justify-center p-2 gap-1 h-full">
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
            <div class="flex items-center gap-md min-w-0 flex-1">
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
            <div class="hidden md:flex items-center gap-lg text-sm text-secondary flex-1">
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
