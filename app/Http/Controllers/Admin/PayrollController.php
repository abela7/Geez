<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PayrollCalculationService;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollCalculationService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Calculate daily pay for a specific staff member
     */
    public function calculateDailyPay(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'hourly_rate' => 'required|numeric|min:0'
        ]);

        $result = $this->payrollService->calculateDailyPay(
            $request->staff_id,
            $request->date,
            $request->hourly_rate
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Calculate weekly pay for a staff member
     */
    public function calculateWeeklyPay(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'week_start' => 'required|date',
            'hourly_rate' => 'required|numeric|min:0'
        ]);

        $weekStart = Carbon::parse($request->week_start);
        $result = $this->payrollService->calculateWeeklyPay(
            $request->staff_id,
            $weekStart,
            $request->hourly_rate
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Process payroll for all staff in a pay period
     */
    public function processPayroll(Request $request)
    {
        $request->validate([
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date'
        ]);

        $payPeriodStart = Carbon::parse($request->pay_period_start);
        $payPeriodEnd = Carbon::parse($request->pay_period_end);

        $staff = Staff::where('status', 'active')->get();
        $payrollRecords = [];

        foreach ($staff as $staffMember) {
            // Get staff's hourly rate from their profile
            $hourlyRate = $staffMember->hourly_rate ?? 15.00; // Default $15/hour
            
            $weeklyPay = $this->payrollService->calculateWeeklyPay(
                $staffMember->id,
                $payPeriodStart,
                $hourlyRate
            );

            if ($weeklyPay['total_hours'] > 0) {
                $payrollRecord = $this->payrollService->createPayrollRecord(
                    $staffMember->id,
                    $payPeriodStart,
                    $payPeriodEnd
                );
                
                $payrollRecords[] = $payrollRecord;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payroll processed successfully',
            'data' => [
                'payroll_records' => $payrollRecords,
                'total_staff_processed' => count($payrollRecords)
            ]
        ]);
    }

    /**
     * Get payroll summary for a staff member
     */
    public function getPayrollSummary(Request $request, string $staffId)
    {
        $staff = Staff::findOrFail($staffId);
        
        // Get last 4 weeks of attendance
        $fourWeeksAgo = Carbon::now()->subWeeks(4);
        
        $attendanceRecords = \App\Models\StaffAttendance::where('staff_id', $staffId)
            ->where('clock_in', '>=', $fourWeeksAgo)
            ->where('current_state', 'clocked_out')
            ->orderBy('clock_in', 'desc')
            ->get();

        $weeklySummaries = [];
        $currentWeek = Carbon::now()->startOfWeek();
        
        for ($i = 0; $i < 4; $i++) {
            $weekStart = $currentWeek->copy()->subWeeks($i);
            $weekEnd = $weekStart->copy()->addDays(6);
            
            $weekRecords = $attendanceRecords->filter(function ($record) use ($weekStart, $weekEnd) {
                return $record->clock_in->between($weekStart, $weekEnd);
            });
            
            $totalHours = $weekRecords->sum('net_hours_worked');
            $regularHours = min($totalHours, 40);
            $overtimeHours = max($totalHours - 40, 0);
            
            $weeklySummaries[] = [
                'week_start' => $weekStart->format('M d'),
                'week_end' => $weekEnd->format('M d'),
                'total_hours' => round($totalHours, 2),
                'regular_hours' => $regularHours,
                'overtime_hours' => $overtimeHours,
                'days_worked' => $weekRecords->count()
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'staff' => [
                    'id' => $staff->id,
                    'name' => $staff->full_name,
                    'hourly_rate' => $staff->hourly_rate ?? 15.00
                ],
                'weekly_summaries' => $weeklySummaries,
                'total_hours_4_weeks' => $attendanceRecords->sum('net_hours_worked'),
                'average_hours_per_week' => round($attendanceRecords->sum('net_hours_worked') / 4, 2)
            ]
        ]);
    }
}
