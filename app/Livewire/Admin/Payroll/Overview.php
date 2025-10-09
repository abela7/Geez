<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use App\Models\StaffPayrollSetting;
use App\Models\Staff;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Overview extends Component
{
    public function render()
    {
        return view('livewire.admin.payroll.overview', [
            'stats' => $this->getPayrollStats(),
            'currentPeriod' => $this->getCurrentPeriod(),
            'recentPeriods' => $this->getRecentPeriods(),
            'recentActivities' => $this->getRecentActivities(),
            'quickActions' => $this->getQuickActions(),
            'monthlyTrend' => $this->getMonthlyTrend(),
        ]);
    }

    private function getPayrollStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        return [
            'total_staff' => Staff::active()->count(),
            'active_periods' => StaffPayrollPeriod::whereIn('status', ['draft', 'calculated', 'approved'])->count(),
            'pending_records' => StaffPayrollRecord::whereHas('period', function($q) {
                $q->where('status', 'calculated');
            })->count(),
            'this_month_total' => StaffPayrollPeriod::where('period_start', '>=', $currentMonth)
                ->where('status', '!=', 'draft')
                ->sum('total_net_pay') ?? 0,
            'last_month_total' => StaffPayrollPeriod::where('period_start', '>=', $lastMonth)
                ->where('period_start', '<', $currentMonth)
                ->where('status', '!=', 'draft')
                ->sum('total_net_pay') ?? 0,
            'ytd_total' => StaffPayrollPeriod::where('period_start', '>=', Carbon::now()->startOfYear())
                ->where('status', '!=', 'draft')
                ->sum('total_net_pay') ?? 0,
        ];
    }

    private function getCurrentPeriod(): ?StaffPayrollPeriod
    {
        $now = Carbon::now();
        
        return StaffPayrollPeriod::where('period_start', '<=', $now)
            ->where('period_end', '>=', $now)
            ->first();
    }

    private function getRecentPeriods(): \Illuminate\Support\Collection
    {
        return StaffPayrollPeriod::with(['payrollSetting'])
            ->orderBy('period_start', 'desc')
            ->limit(5)
            ->get();
    }

    private function getRecentActivities(): array
    {
        // Get recent payroll activities from audit log
        $activities = [];
        
        // Recent period creations
        $recentPeriods = StaffPayrollPeriod::with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach ($recentPeriods as $period) {
            $activities[] = [
                'type' => 'period_created',
                'message' => "Pay period '{$period->name}' was created",
                'user' => $period->creator?->name ?? 'System',
                'time' => $period->created_at,
                'icon' => 'calendar',
                'color' => 'blue',
            ];
        }

        // Recent payroll generations
        $recentGenerated = StaffPayrollPeriod::where('status', '!=', 'draft')
            ->with('creator')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
            
        foreach ($recentGenerated as $period) {
            if ($period->total_records > 0) {
                $activities[] = [
                    'type' => 'payroll_generated',
                    'message' => "Payroll generated for '{$period->name}' - {$period->total_records} records",
                    'user' => $period->creator?->name ?? 'System',
                    'time' => $period->updated_at,
                    'icon' => 'calculator',
                    'color' => 'green',
                ];
            }
        }

        // Sort by time and limit
        usort($activities, function($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        return array_slice($activities, 0, 8);
    }

    private function getQuickActions(): array
    {
        $currentPeriod = $this->getCurrentPeriod();
        $defaultSetting = StaffPayrollSetting::getDefault();
        
        return [
            [
                'title' => 'Create New Period',
                'description' => 'Start a new payroll period',
                'icon' => 'plus',
                'color' => 'indigo',
                'route' => 'admin.staff.payroll.periods',
                'enabled' => true,
            ],
            [
                'title' => 'Generate Current Payroll',
                'description' => $currentPeriod ? "Generate payroll for {$currentPeriod->name}" : 'No current period',
                'icon' => 'calculator',
                'color' => 'green',
                'action' => $currentPeriod && $currentPeriod->status === 'draft' ? 'generateCurrent' : null,
                'enabled' => $currentPeriod && $currentPeriod->status === 'draft',
            ],
            [
                'title' => 'Review Pending',
                'description' => 'Review calculated payroll records',
                'icon' => 'eye',
                'color' => 'yellow',
                'route' => 'admin.staff.payroll.periods',
                'enabled' => StaffPayrollPeriod::where('status', 'calculated')->exists(),
            ],
            [
                'title' => 'Payroll Settings',
                'description' => $defaultSetting ? "Current: {$defaultSetting->name}" : 'Configure payroll settings',
                'icon' => 'cog',
                'color' => 'gray',
                'route' => 'admin.staff.payroll.settings',
                'enabled' => true,
            ],
        ];
    }

    private function getMonthlyTrend(): array
    {
        $months = [];
        $totals = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $total = StaffPayrollPeriod::where('period_start', '>=', $monthStart)
                ->where('period_start', '<=', $monthEnd)
                ->where('status', '!=', 'draft')
                ->sum('total_net_pay') ?? 0;
                
            $months[] = $month->format('M Y');
            $totals[] = $total;
        }
        
        return [
            'months' => $months,
            'totals' => $totals,
        ];
    }

    public function generateCurrent(): void
    {
        $currentPeriod = $this->getCurrentPeriod();
        
        if (!$currentPeriod || $currentPeriod->status !== 'draft') {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'No current draft period available for generation.'
            ]);
            return;
        }

        $this->redirect(route('admin.staff.payroll.periods'));
    }
}
