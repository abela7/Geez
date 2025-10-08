<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo 'Testing Midnight-Crossing Shift Logic:' . PHP_EOL;
echo '=====================================' . PHP_EOL;

// Test case: Shift from 16:00 to 01:00 (next day)
$assignedDate = '2025-10-08';
$startTime = '16:00:00';
$endTime = '01:00:00';

echo 'Shift: ' . $startTime . ' to ' . $endTime . ' on ' . $assignedDate . PHP_EOL;

$scheduledStart = Carbon\Carbon::parse($assignedDate . ' ' . $startTime);
$scheduledEnd = Carbon\Carbon::parse($assignedDate . ' ' . $endTime);

echo 'Before fix:' . PHP_EOL;
echo 'Scheduled Start: ' . $scheduledStart . PHP_EOL;
echo 'Scheduled End: ' . $scheduledEnd . PHP_EOL;
echo 'Duration: ' . $scheduledStart->diffInMinutes($scheduledEnd) . ' minutes' . PHP_EOL;
echo PHP_EOL;

// Apply the fix
if ($endTime < $startTime) {
    $scheduledEnd = $scheduledEnd->addDay();
}

echo 'After fix:' . PHP_EOL;
echo 'Scheduled Start: ' . $scheduledStart . PHP_EOL;
echo 'Scheduled End: ' . $scheduledEnd . PHP_EOL;
echo 'Duration: ' . $scheduledStart->diffInMinutes($scheduledEnd) . ' minutes' . PHP_EOL;
echo 'Duration in hours: ' . round($scheduledStart->diffInMinutes($scheduledEnd) / 60, 2) . ' hours' . PHP_EOL;
echo PHP_EOL;

// Test with actual attendance times
echo 'Testing with actual attendance:' . PHP_EOL;
echo 'Clock In: 2025-10-08 16:00:00' . PHP_EOL;
echo 'Clock Out: 2025-10-09 01:00:00' . PHP_EOL;

$clockIn = Carbon\Carbon::parse('2025-10-08 16:00:00');
$clockOut = Carbon\Carbon::parse('2025-10-09 01:00:00');
$actualMinutes = $clockIn->diffInMinutes($clockOut);

echo 'Actual Duration: ' . $actualMinutes . ' minutes (' . round($actualMinutes / 60, 2) . ' hours)' . PHP_EOL;
echo 'Scheduled Duration: ' . $scheduledStart->diffInMinutes($scheduledEnd) . ' minutes (' . round($scheduledStart->diffInMinutes($scheduledEnd) / 60, 2) . ' hours)' . PHP_EOL;

$varianceMinutes = $actualMinutes - $scheduledStart->diffInMinutes($scheduledEnd);
echo 'Variance: ' . $varianceMinutes . ' minutes (' . round($varianceMinutes / 60, 2) . ' hours)' . PHP_EOL;
