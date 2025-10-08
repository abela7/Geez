<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$interval = App\Models\StaffAttendanceInterval::find('01K72179T9RQ2VGKSNM3YYJ1GB');
$attendance = App\Models\StaffAttendance::find('01K72179T4TWEJAC73TPKCG5PP');

echo 'Before fix:' . PHP_EOL;
echo 'Interval start_time: ' . $interval->start_time . PHP_EOL;
echo 'Interval end_time: ' . $interval->end_time . PHP_EOL;
echo 'Attendance clock_in: ' . $attendance->clock_in . PHP_EOL;
echo 'Attendance clock_out: ' . $attendance->clock_out . PHP_EOL;
echo PHP_EOL;

echo 'Fixing interval...' . PHP_EOL;
$interval->update([
    'start_time' => $attendance->clock_in,
    'end_time' => $attendance->clock_out
]);

echo 'After fix:' . PHP_EOL;
echo 'Interval start_time: ' . $interval->start_time . PHP_EOL;
echo 'Interval end_time: ' . $interval->end_time . PHP_EOL;
echo PHP_EOL;

echo 'What the view will now show:' . PHP_EOL;
echo 'Start time: ' . $interval->start_time->format('g:i A') . PHP_EOL;
echo 'End time: ' . $interval->end_time->format('g:i A') . PHP_EOL;
echo 'Duration: ' . $interval->duration_minutes . ' minutes' . PHP_EOL;
