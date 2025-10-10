<div class="space-y-6">
    <!-- Payroll Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">
                @if ($staff->profile && $staff->profile->hourly_rate)
                    £{{ number_format($staff->profile->hourly_rate, 2) }}
                @else
                    —
                @endif
            </div>
            <div class="text-sm text-secondary">{{ __('staff.hourly_rate') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">
                @if ($recentPayroll->count() > 0)
                    £{{ number_format($recentPayroll->first()->gross_pay, 2) }}
                @else
                    —
                @endif
            </div>
            <div class="text-sm text-secondary">{{ __('staff.last_gross_pay') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">
                @if ($recentPayroll->count() > 0)
                    £{{ number_format($recentPayroll->sum('gross_pay'), 2) }}
                @else
                    —
                @endif
            </div>
            <div class="text-sm text-secondary">{{ __('staff.total_earnings_6_months') }}</div>
        </div>
        <div class="bg-background border border-main rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-orange-600">{{ $recentPayroll->count() }}</div>
            <div class="text-sm text-secondary">{{ __('staff.payroll_records') }}</div>
        </div>
    </div>

    <!-- Recent Payroll Records -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-primary">{{ __('staff.recent_payroll') }}</h3>
            <button class="btn btn-secondary btn-sm" onclick="alert('{{ __('common.coming_soon') }}')">
                <i class="fas fa-plus mr-2"></i>{{ __('staff.generate_payroll') }}
            </button>
        </div>

        @if ($recentPayroll->count() > 0)
        <div class="bg-background border border-main rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-main">
                    <thead class="bg-card">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.pay_period') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.regular_hours') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.overtime_hours') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.gross_pay') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.deductions') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('staff.net_pay') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary uppercase tracking-wider">{{ __('common.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-main">
                        @foreach ($recentPayroll as $payroll)
                        <tr class="hover:bg-card">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ $payroll->pay_period_start->format('M d') }} - {{ $payroll->pay_period_end->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ number_format($payroll->regular_hours, 1) }}h
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                {{ number_format($payroll->overtime_hours ?? 0, 1) }}h
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary font-medium">
                                £{{ number_format($payroll->gross_pay, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary">
                                £{{ number_format($payroll->deductions ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary font-medium">
                                £{{ number_format($payroll->net_pay, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'paid' => 'bg-blue-100 text-blue-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$payroll->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($payroll->status) }}
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
            <i class="fas fa-money-bill-wave text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('staff.no_payroll_records') }}</h3>
            <p class="text-secondary">{{ __('staff.no_payroll_description') }}</p>
        </div>
        @endif
    </div>

    <!-- Earnings Chart (Placeholder) -->
    <div>
        <h3 class="text-lg font-semibold text-primary mb-4">{{ __('staff.earnings_trend') }}</h3>
        <div class="bg-background border border-main rounded-lg p-8 text-center">
            <i class="fas fa-chart-area text-4xl text-muted mb-4"></i>
            <h3 class="text-lg font-medium text-primary mb-2">{{ __('common.coming_soon') }}</h3>
            <p class="text-secondary">{{ __('staff.earnings_chart_description') }}</p>
        </div>
    </div>
</div>
