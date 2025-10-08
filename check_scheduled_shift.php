<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$attendance = App\Models\StaffAttendance::find('01K72179T4TWEJAC73TPKCG5PP');

echo 'Scheduled Shift Card Calculations:' . PHP_EOL;
echo '=================================' . PHP_EOL;

echo 'Database Values:' . PHP_EOL;
echo 'scheduled_minutes: ' . ($attendance->scheduled_minutes ?? 'null') . PHP_EOL;
echo 'actual_minutes: ' . ($attendance->actual_minutes ?? 'null') . PHP_EOL;
echo 'variance_minutes: ' . ($attendance->variance_minutes ?? 'null') . PHP_EOL;
echo PHP_EOL;

if ($attendance->scheduled_minutes) {
    $scheduledMinutes = $attendance->scheduled_minutes;
    $scheduledHours = floor($scheduledMinutes / 60);
    $scheduledMins = $scheduledMinutes % 60;
    $scheduledDuration = $scheduledHours > 0 ? $scheduledHours . 'h ' . $scheduledMins . 'm' : $scheduledMins . 'm';
    echo 'Expected Shift Length: ' . $scheduledDuration . PHP_EOL;
}

if ($attendance->actual_minutes) {
    $actualMinutes = $attendance->actual_minutes;
    $actualHours = floor($actualMinutes / 60);
    $actualMins = $actualMinutes % 60;
    $actualDuration = $actualHours > 0 ? $actualHours . 'h ' . $actualMins . 'm' : $actualMins . 'm';
    echo 'Time Actually Worked: ' . $actualDuration . PHP_EOL;
}

if ($attendance->variance_minutes) {
    $varianceMinutes = abs($attendance->variance_minutes);
    $varianceHours = floor($varianceMinutes / 60);
    $varianceMins = $varianceMinutes % 60;
    $varianceDuration = $varianceHours > 0 ? $varianceHours . 'h ' . $varianceMins . 'm' : $varianceMins . 'm';
    $varianceSign = $attendance->variance_minutes > 0 ? '+' : '';
    $varianceText = $attendance->variance_minutes > 0 ? 'extra' : 'short';
    echo 'Difference from Schedule: ' . $varianceSign . $varianceDuration . ' ' . $varianceText . PHP_EOL;
}
