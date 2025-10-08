<?php

namespace App\Services;

use App\Models\StaffAttendance;
use App\Models\StaffPayrollRecord;
use Carbon\Carbon;

class PayrollCalculationService
{
    /**
     * Calculate daily pay for a staff member
     */
    public function calculateDailyPay(string $staffId, string $date, float $hourlyRate): array
    {
        $attendance = StaffAttendance::where('staff_id', $staffId)
            ->whereDate('clock_in', $date)
            ->where('current_state', 'clocked_out')
            ->first();

        if (!$attendance) {
            return [
                'error' => 'No attendance record found for this date',
                'daily_pay' => 0
            ];
        }

        $netHours = $attendance->net_hours_worked ?? 0;
        $dailyPay = $netHours * $hourlyRate;

        return [
            'staff_id' => $staffId,
            'date' => $date,
            'clock_in' => $attendance->clock_in,
            'clock_out' => $attendance->clock_out,
            'total_hours' => $attendance->hours_worked,
            'break_minutes' => $attendance->total_break_minutes,
            'net_hours' => $netHours,
            'hourly_rate' => $hourlyRate,
            'daily_pay' => round($dailyPay, 2),
            'break_details' => $this->getBreakDetails($attendance)
        ];
    }

    /**
     * Calculate weekly pay for a staff member
     */
    public function calculateWeeklyPay(string $staffId, Carbon $weekStart, float $hourlyRate): array
    {
        $weekEnd = $weekStart->copy()->addDays(6);
        
        $attendanceRecords = StaffAttendance::where('staff_id', $staffId)
            ->whereBetween('clock_in', [$weekStart, $weekEnd])
            ->where('current_state', 'clocked_out')
            ->get();

        $totalHours = $attendanceRecords->sum('net_hours_worked');
        $regularHours = min($totalHours, 40);
        $overtimeHours = max($totalHours - 40, 0);
        
        $regularPay = $regularHours * $hourlyRate;
        $overtimePay = $overtimeHours * ($hourlyRate * 1.5);
        $totalPay = $regularPay + $overtimePay;

        return [
            'staff_id' => $staffId,
            'week_start' => $weekStart->format('Y-m-d'),
            'week_end' => $weekEnd->format('Y-m-d'),
            'total_hours' => round($totalHours, 2),
            'regular_hours' => $regularHours,
            'overtime_hours' => $overtimeHours,
            'hourly_rate' => $hourlyRate,
            'regular_pay' => round($regularPay, 2),
            'overtime_pay' => round($overtimePay, 2),
            'total_pay' => round($totalPay, 2),
            'daily_breakdown' => $this->getDailyBreakdown($attendanceRecords)
        ];
    }

    /**
     * Get detailed break information
     */
    private function getBreakDetails(StaffAttendance $attendance): array
    {
        return $attendance->intervals()
            ->where('interval_type', 'break')
            ->get()
            ->map(function ($interval) {
                return [
                    'start_time' => $interval->start_time,
                    'end_time' => $interval->end_time,
                    'duration_minutes' => $interval->duration_minutes,
                    'category' => $interval->break_category,
                    'reason' => $interval->reason
                ];
            })
            ->toArray();
    }

    /**
     * Get daily breakdown for weekly calculation
     */
    private function getDailyBreakdown($attendanceRecords): array
    {
        return $attendanceRecords->map(function ($record) {
            return [
                'date' => $record->clock_in->format('Y-m-d'),
                'clock_in' => $record->clock_in->format('H:i'),
                'clock_out' => $record->clock_out->format('H:i'),
                'total_hours' => $record->hours_worked,
                'net_hours' => $record->net_hours_worked,
                'break_minutes' => $record->total_break_minutes
            ];
        })->toArray();
    }

    /**
     * Create payroll record for a pay period
     */
    public function createPayrollRecord(string $staffId, Carbon $payPeriodStart, Carbon $payPeriodEnd): StaffPayrollRecord
    {
        $weeklyPay = $this->calculateWeeklyPay($staffId, $payPeriodStart, 15.00); // $15/hour example
        
        return StaffPayrollRecord::create([
            'staff_id' => $staffId,
            'pay_period_start' => $payPeriodStart,
            'pay_period_end' => $payPeriodEnd,
            'regular_hours' => $weeklyPay['regular_hours'],
            'overtime_hours' => $weeklyPay['overtime_hours'],
            'gross_pay' => $weeklyPay['total_pay'],
            'deductions' => 0, // Calculate based on tax rules
            'net_pay' => $weeklyPay['total_pay'], // Will be adjusted after deductions
            'status' => 'pending',
            'processed_by' => auth()->id(),
            'created_by' => auth()->id()
        ]);
    }
}
