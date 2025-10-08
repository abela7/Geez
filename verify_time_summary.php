<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$attendance = App\Models\StaffAttendance::find('01K72179T4TWEJAC73TPKCG5PP');

echo 'Time Summary Calculations:' . PHP_EOL;
echo '========================' . PHP_EOL;

$totalMinutes = $attendance->clock_in->diffInMinutes($attendance->clock_out);
$breakMinutes = $attendance->total_break_minutes ?? 0;
$netMinutes = $totalMinutes - $breakMinutes;

echo 'Total Minutes: ' . $totalMinutes . PHP_EOL;
echo 'Break Minutes: ' . $breakMinutes . PHP_EOL;
echo 'Net Minutes: ' . $netMinutes . PHP_EOL;
echo PHP_EOL;

$netHours = floor($netMinutes / 60);
$netMins = $netMinutes % 60;
$netDuration = $netHours > 0 ? $netHours . 'h ' . $netMins . 'm' : $netMins . 'm';

echo 'Time Summary Display:' . PHP_EOL;
echo 'Work Session: ' . $netDuration . ' productive work' . PHP_EOL;
echo PHP_EOL;

echo 'Database Fields Used:' . PHP_EOL;
echo 'clock_in: ' . $attendance->clock_in . PHP_EOL;
echo 'clock_out: ' . $attendance->clock_out . PHP_EOL;
echo 'total_break_minutes: ' . ($attendance->total_break_minutes ?? 'null') . PHP_EOL;
echo 'scheduled_minutes: ' . ($attendance->scheduled_minutes ?? 'null') . PHP_EOL;
echo PHP_EOL;

echo 'Break Check:' . PHP_EOL;
echo 'Has Breaks: ' . ($breakMinutes > 0 ? 'Yes' : 'No') . PHP_EOL;
echo 'Break Count: ' . ($attendance->break_count ?? 0) . PHP_EOL;
