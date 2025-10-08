<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$attendance = App\Models\StaffAttendance::find('01K72179T4TWEJAC73TPKCG5PP');

echo 'Database Values:' . PHP_EOL;
echo 'clock_in: ' . $attendance->clock_in . PHP_EOL;
echo 'clock_out: ' . $attendance->clock_out . PHP_EOL;
echo 'total_break_minutes: ' . ($attendance->total_break_minutes ?? 'null') . PHP_EOL;
echo 'break_count: ' . ($attendance->break_count ?? 'null') . PHP_EOL;
echo PHP_EOL;

$totalMinutes = $attendance->clock_in->diffInMinutes($attendance->clock_out);
echo 'Calculated Values:' . PHP_EOL;
echo 'Total minutes: ' . $totalMinutes . PHP_EOL;
echo 'Total hours: ' . floor($totalMinutes / 60) . PHP_EOL;
echo 'Total mins: ' . ($totalMinutes % 60) . PHP_EOL;
echo PHP_EOL;

$breakMinutes = $attendance->total_break_minutes ?? 0;
$netMinutes = $totalMinutes - $breakMinutes;
echo 'Net minutes: ' . $netMinutes . PHP_EOL;
echo 'Net hours: ' . floor($netMinutes / 60) . PHP_EOL;
echo 'Net mins: ' . ($netMinutes % 60) . PHP_EOL;
echo PHP_EOL;

echo 'Display Format:' . PHP_EOL;
$totalHours = floor($totalMinutes / 60);
$totalMins = $totalMinutes % 60;
$totalDisplay = $totalHours > 0 ? $totalHours . 'h ' . $totalMins . 'm' : $totalMins . 'm';
echo 'Total Time: ' . $totalDisplay . PHP_EOL;

$netHours = floor($netMinutes / 60);
$netMins = $netMinutes % 60;
$netDisplay = $netHours > 0 ? $netHours . 'h ' . $netMins . 'm' : $netMins . 'm';
echo 'Net Time: ' . $netDisplay . PHP_EOL;

$breakHours = floor($breakMinutes / 60);
$breakMins = $breakMinutes % 60;
$breakDisplay = $breakHours > 0 ? $breakHours . 'h ' . $breakMins . 'm' : $breakMins . 'm';
echo 'Break Time: ' . $breakDisplay . PHP_EOL;
