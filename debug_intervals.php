<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$attendance = App\Models\StaffAttendance::find('01K72179T4TWEJAC73TPKCG5PP');

echo 'Attendance ID: ' . $attendance->id . PHP_EOL;
echo 'Clock In: ' . $attendance->clock_in . PHP_EOL;
echo 'Clock Out: ' . $attendance->clock_out . PHP_EOL;
echo 'Created: ' . $attendance->created_at . PHP_EOL;
echo 'Updated: ' . $attendance->updated_at . PHP_EOL;
echo PHP_EOL;

echo 'All Intervals (ordered by creation):' . PHP_EOL;
$intervals = $attendance->intervals()->orderBy('created_at')->get();
foreach($intervals as $i => $interval) {
    echo ($i+1) . '. ' . $interval->interval_type . ':' . PHP_EOL;
    echo '    ID: ' . $interval->id . PHP_EOL;
    echo '    Start: ' . $interval->start_time . PHP_EOL;
    echo '    End: ' . $interval->end_time . PHP_EOL;
    echo '    Duration: ' . $interval->duration_minutes . ' minutes' . PHP_EOL;
    echo '    Created: ' . $interval->created_at . PHP_EOL;
    echo '    Updated: ' . $interval->updated_at . PHP_EOL;
    echo '---' . PHP_EOL;
}

echo PHP_EOL . 'Timeline Analysis:' . PHP_EOL;
echo '1. Attendance record created at: ' . $attendance->created_at . PHP_EOL;
echo '2. Clock in time set to: ' . $attendance->clock_in . PHP_EOL;
echo '3. Clock out time set to: ' . $attendance->clock_out . PHP_EOL;
echo '4. Time difference: ' . $attendance->clock_in->diffInMinutes($attendance->clock_out) . ' minutes' . PHP_EOL;

if ($intervals->count() > 0) {
    $interval = $intervals->first();
    echo PHP_EOL . 'Interval Analysis:' . PHP_EOL;
    echo '1. Interval created at: ' . $interval->created_at . PHP_EOL;
    echo '2. Interval start_time: ' . $interval->start_time . PHP_EOL;
    echo '3. Interval end_time: ' . $interval->end_time . PHP_EOL;
    echo '4. Expected start_time (should be clock_in): ' . $attendance->clock_in . PHP_EOL;
    echo '5. Expected end_time (should be clock_out): ' . $attendance->clock_out . PHP_EOL;
    echo PHP_EOL . 'What the view displays:' . PHP_EOL;
    echo 'Start time shown: ' . $interval->start_time->format('g:i A') . PHP_EOL;
    echo 'End time shown: ' . ($interval->end_time ? $interval->end_time->format('g:i A') : 'null') . PHP_EOL;
    echo 'Duration shown: ' . $interval->duration_minutes . ' minutes' . PHP_EOL;
}
