<!-- Dashboard Content Grid -->
<div class="dashboard-content-grid">
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('staff.payroll.quick_actions') }}</h3>
        </div>
        <div class="card-body">
            <div class="quick-actions-grid">
                <button @click="processMonthlyPayroll()" class="quick-action-btn quick-action-btn--primary">
                    <div class="quick-action-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span>{{ __('staff.payroll.monthly_payroll') }}</span>
                </button>

                <button @click="generatePayslips()" class="quick-action-btn quick-action-btn--success">
                    <div class="quick-action-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span>{{ __('staff.payroll.generate_payslips') }}</span>
                </button>

                <button @click="openBonusModal()" class="quick-action-btn quick-action-btn--warning">
                    <div class="quick-action-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <span>{{ __('staff.payroll.manage_bonuses') }}</span>
                </button>

                <button @click="openDeductionsModal()" class="quick-action-btn quick-action-btn--danger">
                    <div class="quick-action-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <span>{{ __('staff.payroll.manage_deductions') }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Payroll Calendar -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('staff.payroll.payroll_calendar') }}</h3>
            <div class="card-actions">
                <button @click="previousMonth()" class="card-action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <span class="current-month" x-text="currentMonthYear"></span>
                <button @click="nextMonth()" class="card-action-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="payroll-calendar">
                <div class="calendar-header">
                    <div class="calendar-day-header">{{ __('staff.payroll.sun') }}</div>
                    <div class="calendar-day-header">{{ __('staff.payroll.mon') }}</div>
                    <div class="calendar-day-header">{{ __('staff.payroll.tue') }}</div>
                    <div class="calendar-day-header">{{ __('staff.payroll.wed') }}</div>
                    <div class="calendar-day-header">{{ __('staff.payroll.thu') }}</div>
                    <div class="calendar-day-header">{{ __('staff.payroll.fri') }}</div>
                    <div class="calendar-day-header">{{ __('staff.payroll.sat') }}</div>
                </div>
                <div class="calendar-grid">
                    <template x-for="day in calendarDays" :key="day.date">
                        <div class="calendar-day" 
                             :class="{ 
                                 'has-payroll': day.hasPayroll, 
                                 'is-today': day.isToday,
                                 'is-payday': day.isPayday,
                                 'other-month': day.otherMonth
                             }"
                             @click="viewDayPayroll(day)">
                            <span class="day-number" x-text="day.day"></span>
                            <div x-show="day.hasPayroll" class="payroll-indicator">
                                <span x-text="formatCurrency(day.amount)"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Payroll Activity -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('staff.payroll.recent_activity') }}</h3>
        <button @click="setView('history')" class="card-action">
            {{ __('staff.payroll.view_all') }}
            <svg class="card-action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
    <div class="card-body">
        <div class="activity-timeline">
            <template x-for="activity in recentActivity" :key="activity.id">
                <div class="activity-item">
                    <div class="activity-icon" :class="`activity-icon--${activity.type}`">
                        <svg x-show="activity.type === 'processed'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <svg x-show="activity.type === 'pending'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <svg x-show="activity.type === 'bonus'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <svg x-show="activity.type === 'deduction'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <div class="activity-content">
                        <h4 class="activity-title" x-text="activity.title"></h4>
                        <p class="activity-description" x-text="activity.description"></p>
                        <span class="activity-time" x-text="activity.time"></span>
                    </div>
                    <div class="activity-amount" 
                         :class="{ 'negative': activity.type === 'deduction' }"
                         x-text="formatCurrency(activity.amount)"></div>
                </div>
            </template>
        </div>
    </div>
</div>
