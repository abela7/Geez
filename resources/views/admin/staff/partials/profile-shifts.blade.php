<div class="space-y-6">
    <!-- Shift Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $upcomingShifts->count() }}</div>
            <div class="text-sm text-secondary">{{ __('staff.upcoming_shifts') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">
                @if ($upcomingShifts->count() > 0)
                    {{ $upcomingShifts->first()->assigned_date->format('M d') }}
                @else
                    —
                @endif
            </div>
            <div class="text-sm text-secondary">{{ __('staff.next_shift') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">
                @php
                    $totalHours = $upcomingShifts->sum(function($assignment) {
                        return $assignment->shift ? $assignment->shift->getDurationInHours() : 0;
                    });
                @endphp
                {{ number_format($totalHours, 1) }}h
            </div>
            <div class="text-sm text-secondary">{{ __('staff.scheduled_hours') }}</div>
        </div>
    </div>

    <!-- Upcoming Shifts -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-primary">{{ __('staff.upcoming_shifts') }}</h3>
            <button class="btn btn-secondary btn-sm" onclick="alert('{{ __('common.coming_soon') }}')">
                <i class="fas fa-plus mr-2"></i>{{ __('staff.assign_shift') }}
            </button>
        </div>

        @if ($upcomingShifts->count() > 0)
        <div class="space-y-4">
            @foreach ($upcomingShifts as $shiftAssignment)
            <div class="bg-background border border-main rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="font-medium text-primary">{{ $shiftAssignment->shift->name ?? __('staff.shift') }}</h4>
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-blue-100 text-blue-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$shiftAssignment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($shiftAssignment->status) }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-secondary">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $shiftAssignment->assigned_date->format('l, M d, Y') }}</span>
                            </div>
                            @if ($shiftAssignment->shift)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                <span>{{ $shiftAssignment->shift->start_time }} - {{ $shiftAssignment->shift->end_time }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-hourglass-half"></i>
                                <span>{{ $shiftAssignment->shift->getDurationInHours() }} {{ __('staff.hours') }}</span>
                            </div>
                            @endif
                        </div>
                        
                        @if ($shiftAssignment->notes)
                        <p class="text-secondary text-sm mt-2">{{ $shiftAssignment->notes }}</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2 ml-4">
                        @if ($shiftAssignment->status === 'scheduled')
                        <button class="btn btn-sm btn-success" onclick="alert('{{ __('common.coming_soon') }}')">
                            {{ __('staff.confirm_shift') }}
                        </button>
                        @endif
                        <button class="btn btn-sm btn-secondary" onclick="alert('{{ __('common.coming_soon') }}')">
                            {{ __('common.edit') }}
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-calendar-times text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('staff.no_upcoming_shifts') }}</h3>
            <p class="text-secondary">{{ __('staff.no_upcoming_shifts_description') }}</p>
        </div>
        @endif
    </div>

    <!-- Shift Templates (Placeholder) -->
    <div>
        <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.shift_templates') }}</h3>
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-calendar-alt text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('common.coming_soon') }}</h3>
            <p class="text-secondary">{{ __('staff.shift_templates_description') }}</p>
        </div>
    </div>

    <!-- Shift History (Placeholder) -->
    <div>
        <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.shift_history') }}</h3>
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-history text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('common.coming_soon') }}</h3>
            <p class="text-secondary">{{ __('staff.shift_history_description') }}</p>
        </div>
    </div>
</div>
