<div class="space-y-6">
    <!-- Attendance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['attendance_rate'] }}%</div>
            <div class="text-sm text-secondary">{{ __('staff.attendance_rate') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_hours_this_month'], 1) }}h</div>
            <div class="text-sm text-secondary">{{ __('staff.total_hours') }} ({{ __('common.this_month') }})</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $recentAttendance->count() }}</div>
            <div class="text-sm text-secondary">{{ __('staff.records_last_30_days') }}</div>
        </div>
    </div>

    <!-- Recent Attendance Records -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-primary">{{ __('staff.recent_attendance') }}</h3>
            <button class="btn btn-secondary btn-sm" onclick="alert('{{ __('common.coming_soon') }}')">
                <i class="fas fa-plus mr-2"></i>{{ __('staff.record_attendance') }}
            </button>
        </div>

        @if ($recentAttendance->count() > 0)
        <div class="bg-background border border-main rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-main">
                    <thead class="bg-card">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('common.date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.clock_in') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.clock_out') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.hours_worked') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-main">
                        @foreach ($recentAttendance as $attendance)
                        <tr class="hover:bg-card">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ $attendance->clock_in->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ $attendance->clock_in->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ $attendance->clock_out ? $attendance->clock_out->format('H:i') : '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ $attendance->hours_worked ? number_format($attendance->hours_worked, 2) . 'h' : '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'present' => 'bg-green-100 text-green-800',
                                        'late' => 'bg-yellow-100 text-yellow-800',
                                        'absent' => 'bg-red-100 text-red-800',
                                        'early_leave' => 'bg-orange-100 text-orange-800',
                                        'overtime' => 'bg-blue-100 text-blue-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$attendance->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-calendar-times text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('staff.no_attendance_records') }}</h3>
            <p class="text-secondary">{{ __('staff.no_attendance_description') }}</p>
        </div>
        @endif
    </div>

    <!-- Attendance Patterns (Placeholder) -->
    <div>
        <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.attendance_patterns') }}</h3>
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-chart-bar text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('common.coming_soon') }}</h3>
            <p class="text-secondary">{{ __('staff.attendance_charts_description') }}</p>
        </div>
    </div>
</div>
