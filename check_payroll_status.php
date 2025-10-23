<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollSetting;
use App\Models\StaffAttendance;
use App\Models\StaffPayrollRecord;
use App\Models\Staff;

echo "=== PAYROLL SYSTEM STATUS ===\n\n";

echo "1. PAYROLL PERIODS:\n";
$periods = StaffPayrollPeriod::all();
if ($periods->isEmpty()) {
    echo "   ❌ No periods created yet\n";
} else {
    foreach ($periods as $p) {
        echo "   ✅ {$p->name} ({$p->status}) | Records: {$p->total_records}\n";
    }
}

echo "\n2. PAYROLL SETTINGS:\n";
$settings = StaffPayrollSetting::all();
if ($settings->isEmpty()) {
    echo "   ❌ No settings created yet\n";
} else {
    foreach ($settings as $s) {
        $default = $s->is_default ? "✅ DEFAULT" : "";
        echo "   ✅ {$s->name} | Overtime: {$s->overtime_multiplier}x | {$default}\n";
    }
}

echo "\n3. ATTENDANCE DATA:\n";
$attendance = StaffAttendance::count();
echo "   ✅ Total attendance records: {$attendance}\n";

echo "\n4. ACTIVE STAFF WITH HOURLY RATES:\n";
$staff = Staff::active()->get();
if ($staff->isEmpty()) {
    echo "   ❌ No active staff\n";
} else {
    foreach ($staff as $s) {
        $rate = $s->profile?->hourly_rate ?? 0;
        echo "   ✅ {$s->name} (£" . number_format($rate, 2) . "/hr)\n";
    }
}

echo "\n5. PAYROLL RECORDS GENERATED:\n";
$records = StaffPayrollRecord::count();
if ($records === 0) {
    echo "   ❌ No payroll records generated yet\n";
} else {
    echo "   ✅ Total records: {$records}\n";
}

echo "\n=== NEXT STEP ===\n";
echo "📍 Go to: http://127.0.0.1:8000/admin/staff/payroll/periods\n";
echo "📌 Click the GREEN 'Generate' button on your payroll period\n";
echo "✨ This will create payroll records for all staff based on attendance\n";
