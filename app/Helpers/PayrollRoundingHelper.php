<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\StaffPayrollSetting;

/**
 * Centralized rounding policy helper for payroll calculations.
 * Ensures consistent rounding across all payroll operations.
 */
class PayrollRoundingHelper
{
    /**
     * Round amount using the active payroll setting's policy.
     */
    public static function round(float $amount, ?StaffPayrollSetting $setting = null): float
    {
        $setting = $setting ?? StaffPayrollSetting::getDefault();
        
        if (! $setting) {
            // Fallback to standard rounding if no setting exists
            return round($amount, 2);
        }

        return $setting->roundAmount($amount);
    }

    /**
     * Round to 2 decimal places (standard currency rounding).
     */
    public static function roundCurrency(float $amount): float
    {
        return round($amount, 2);
    }

    /**
     * Round up (ceiling).
     */
    public static function roundUp(float $amount, int $precision = 2): float
    {
        $multiplier = pow(10, $precision);
        
        return ceil($amount * $multiplier) / $multiplier;
    }

    /**
     * Round down (floor).
     */
    public static function roundDown(float $amount, int $precision = 2): float
    {
        $multiplier = pow(10, $precision);
        
        return floor($amount * $multiplier) / $multiplier;
    }

    /**
     * Round to nearest (standard rounding).
     */
    public static function roundNearest(float $amount, int $precision = 2): float
    {
        return round($amount, $precision);
    }

    /**
     * Round hours to quarter-hour increments (0.25).
     * Used for attendance time rounding.
     */
    public static function roundToQuarterHour(float $hours): float
    {
        return round($hours * 4) / 4;
    }

    /**
     * Ensure no negative amounts (floor at 0).
     */
    public static function ensurePositive(float $amount): float
    {
        return max(0, $amount);
    }

    /**
     * Calculate percentage and round properly.
     */
    public static function calculatePercentage(float $amount, float $percentage): float
    {
        return self::roundCurrency($amount * $percentage);
    }

    /**
     * Add multiple amounts and round the total (avoids accumulated rounding errors).
     */
    public static function sumAndRound(array $amounts): float
    {
        $total = array_sum($amounts);
        
        return self::roundCurrency($total);
    }

    /**
     * Calculate net pay from gross and deductions.
     * Ensures result is never negative.
     */
    public static function calculateNetPay(float $grossPay, float $deductions): float
    {
        $netPay = $grossPay - $deductions;
        
        return self::ensurePositive(self::roundCurrency($netPay));
    }
}

