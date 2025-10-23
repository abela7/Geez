<!-- Dashboard Content Grid -->
<div class="dashboard-content-grid">
    <!-- Payroll Management Hub -->
    <div class="card" style="grid-column: 1 / -1;">
        <div class="card-header">
            <h3 class="card-title" style="color: var(--color-text-primary); font-size: 1.25rem; font-weight: 600;">Payroll Management</h3>
            <p style="color: var(--color-text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">Quick access to all payroll functions</p>
        </div>
        <div class="card-body" style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                <!-- Pay Periods -->
                <a href="{{ route('admin.staff.payroll.periods') }}" style="display: flex; align-items: center; padding: 1.5rem; border-radius: 0.75rem; text-decoration: none; transition: all 0.3s; background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='var(--color-primary)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='var(--color-border-base)';">
                    <div style="display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); color: white; flex-shrink: 0;">
                        <svg style="width: 2rem; height: 2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div style="margin-left: 1rem; flex: 1;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.25rem;">Pay Periods</h4>
                        <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">Manage payroll cycles and schedules</p>
                    </div>
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-text-muted); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <!-- Add Payroll -->
                <a href="{{ route('admin.staff.payroll.add') }}" style="display: flex; align-items: center; padding: 1.5rem; border-radius: 0.75rem; text-decoration: none; transition: all 0.3s; background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='var(--color-success)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='var(--color-border-base)';">
                    <div style="display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, var(--color-success) 0%, #047857 100%); color: white; flex-shrink: 0;">
                        <svg style="width: 2rem; height: 2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div style="margin-left: 1rem; flex: 1;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.25rem;">Add Payroll</h4>
                        <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">Generate new payroll for staff</p>
                    </div>
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-text-muted); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <!-- Review Payroll -->
                <a href="#" onclick="event.preventDefault(); fetch('{{ route('admin.staff.payroll.periods') }}').then(r => r.text()).then(html => { const match = html.match(/href=[\'\\\"].*?\/payroll\/periods\/([a-zA-Z0-9]+)\/review/); if (match) window.location.href = '/admin/staff/payroll/periods/' + match[1] + '/review'; else window.location.href = '{{ route('admin.staff.payroll.periods') }}'; });" style="display: flex; align-items: center; padding: 1.5rem; border-radius: 0.75rem; text-decoration: none; transition: all 0.3s; background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='var(--color-info)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='var(--color-border-base)';">
                    <div style="display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, var(--color-info) 0%, #1e40af 100%); color: white; flex-shrink: 0;">
                        <svg style="width: 2rem; height: 2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div style="margin-left: 1rem; flex: 1;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.25rem;">Review Payroll</h4>
                        <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">Review and approve payroll records</p>
                    </div>
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-text-muted); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <!-- Process Payment -->
                <a href="#" onclick="event.preventDefault(); fetch('{{ route('admin.staff.payroll.periods') }}').then(r => r.text()).then(html => { const match = html.match(/href=[\'\\\"].*?\/payroll\/periods\/([a-zA-Z0-9]+)\/payment/); if (match) window.location.href = '/admin/staff/payroll/periods/' + match[1] + '/payment'; else window.location.href = '{{ route('admin.staff.payroll.periods') }}'; });" style="display: flex; align-items: center; padding: 1.5rem; border-radius: 0.75rem; text-decoration: none; transition: all 0.3s; background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='#8b5cf6';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='var(--color-border-base)';">
                    <div style="display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); color: white; flex-shrink: 0;">
                        <svg style="width: 2rem; height: 2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div style="margin-left: 1rem; flex: 1;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.25rem;">Process Payment</h4>
                        <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">Process staff payments and transfers</p>
                    </div>
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-text-muted); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <!-- View Reports -->
                <a href="#" onclick="event.preventDefault(); fetch('{{ route('admin.staff.payroll.periods') }}').then(r => r.text()).then(html => { const match = html.match(/href=[\'\\\"].*?\/payroll\/periods\/([a-zA-Z0-9]+)\/reports/); if (match) window.location.href = '/admin/staff/payroll/periods/' + match[1] + '/reports'; else window.location.href = '{{ route('admin.staff.payroll.periods') }}'; });" style="display: flex; align-items: center; padding: 1.5rem; border-radius: 0.75rem; text-decoration: none; transition: all 0.3s; background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='#ec4899';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='var(--color-border-base)';">
                    <div style="display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white; flex-shrink: 0;">
                        <svg style="width: 2rem; height: 2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div style="margin-left: 1rem; flex: 1;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.25rem;">View Reports</h4>
                        <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">Generate payslips and reports</p>
                    </div>
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-text-muted); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <!-- Payroll Settings -->
                <a href="{{ route('admin.staff.payroll.settings') }}" style="display: flex; align-items: center; padding: 1.5rem; border-radius: 0.75rem; text-decoration: none; transition: all 0.3s; background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='var(--color-text-secondary)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='var(--color-border-base)';">
                    <div style="display: flex; align-items: center; justify-content: center; width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, var(--color-text-secondary) 0%, #374151 100%); color: white; flex-shrink: 0;">
                        <svg style="width: 2rem; height: 2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div style="margin-left: 1rem; flex: 1;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.25rem;">Payroll Settings</h4>
                        <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">Configure payroll rules and defaults</p>
                    </div>
                    <svg style="width: 1.25rem; height: 1.25rem; color: var(--color-text-muted); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
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
