<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Shifts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class OverviewController extends Controller
{
    /**
     * Display the shift overview page.
     */
    public function index(Request $request): View
    {
        $weekStart = $request->get('week') 
            ? Carbon::parse($request->get('week'))->startOfWeek()
            : Carbon::now()->startOfWeek();
        
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        $weeklySchedule = $this->getWeeklySchedule($weekStart, $weekEnd);
        $shiftSummary = $this->getShiftSummary($weekStart, $weekEnd);
        $upcomingShifts = $this->getUpcomingShifts();
        $currentShifts = $this->getCurrentShifts();
        $coverageGaps = $this->getCoverageGaps($weekStart, $weekEnd);
        $weekNavigation = $this->getWeekNavigation($weekStart);

        return view('admin.shifts.overview.index', compact(
            'weeklySchedule',
            'shiftSummary',
            'upcomingShifts',
            'currentShifts',
            'coverageGaps',
            'weekNavigation',
            'weekStart',
            'weekEnd'
        ));
    }

    private function getWeeklySchedule(Carbon $weekStart, Carbon $weekEnd): array
    {
        $schedule = [];
        $current = $weekStart->copy();
        
        while ($current <= $weekEnd) {
            $dayShifts = $this->getDayShifts($current);
            $schedule[] = [
                'date' => $current->copy(),
                'day_name' => $current->format('l'),
                'day_short' => $current->format('D'),
                'is_today' => $current->isToday(),
                'is_weekend' => $current->isWeekend(),
                'shifts' => $dayShifts,
                'total_shifts' => count($dayShifts),
                'total_staff' => array_sum(array_column($dayShifts, 'assigned_staff_count')),
                'total_hours' => array_sum(array_column($dayShifts, 'duration_hours'))
            ];
            $current->addDay();
        }
        
        return $schedule;
    }

    private function getDayShifts(Carbon $date): array
    {
        // Mock data for shifts on a specific day
        $shifts = [];
        
        if ($date->isWeekday()) {
            $shifts = [
                [
                    'id' => 1,
                    'name' => 'Morning Prep',
                    'start_time' => '06:00',
                    'end_time' => '14:00',
                    'duration_hours' => 8,
                    'department' => 'Kitchen',
                    'required_staff' => 3,
                    'assigned_staff_count' => 3,
                    'assigned_staff' => [
                        ['id' => 1, 'name' => 'Alemayehu Tadesse', 'role' => 'Head Chef'],
                        ['id' => 2, 'name' => 'Meron Gebremedhin', 'role' => 'Kitchen Staff'],
                        ['id' => 3, 'name' => 'Dawit Bekele', 'role' => 'Kitchen Staff']
                    ],
                    'color' => '#3B82F6',
                    'status' => 'fully_covered',
                    'type' => 'regular'
                ],
                [
                    'id' => 2,
                    'name' => 'Lunch Service',
                    'start_time' => '11:00',
                    'end_time' => '16:00',
                    'duration_hours' => 5,
                    'department' => 'Front of House',
                    'required_staff' => 4,
                    'assigned_staff_count' => 3,
                    'assigned_staff' => [
                        ['id' => 4, 'name' => 'Sara Ahmed', 'role' => 'Server'],
                        ['id' => 5, 'name' => 'Fatima Hassan', 'role' => 'Server'],
                        ['id' => 6, 'name' => 'Ahmed Ali', 'role' => 'Host']
                    ],
                    'color' => '#10B981',
                    'status' => 'partially_covered',
                    'type' => 'regular'
                ],
                [
                    'id' => 3,
                    'name' => 'Evening Service',
                    'start_time' => '17:00',
                    'end_time' => '23:00',
                    'duration_hours' => 6,
                    'department' => 'Front of House',
                    'required_staff' => 5,
                    'assigned_staff_count' => 5,
                    'assigned_staff' => [
                        ['id' => 4, 'name' => 'Sara Ahmed', 'role' => 'Server'],
                        ['id' => 7, 'name' => 'Yohannes Tesfaye', 'role' => 'Bartender'],
                        ['id' => 8, 'name' => 'Hanan Osman', 'role' => 'Manager'],
                        ['id' => 9, 'name' => 'Kedir Mohammed', 'role' => 'Server'],
                        ['id' => 10, 'name' => 'Tigist Wolde', 'role' => 'Server']
                    ],
                    'color' => '#8B5CF6',
                    'status' => 'fully_covered',
                    'type' => 'regular'
                ]
            ];
        } elseif ($date->isSaturday()) {
            $shifts = [
                [
                    'id' => 4,
                    'name' => 'Weekend Brunch',
                    'start_time' => '09:00',
                    'end_time' => '15:00',
                    'duration_hours' => 6,
                    'department' => 'Kitchen',
                    'required_staff' => 4,
                    'assigned_staff_count' => 4,
                    'assigned_staff' => [
                        ['id' => 1, 'name' => 'Alemayehu Tadesse', 'role' => 'Head Chef'],
                        ['id' => 2, 'name' => 'Meron Gebremedhin', 'role' => 'Kitchen Staff'],
                        ['id' => 11, 'name' => 'Bereket Taye', 'role' => 'Kitchen Staff'],
                        ['id' => 12, 'name' => 'Selamawit Desta', 'role' => 'Kitchen Staff']
                    ],
                    'color' => '#F59E0B',
                    'status' => 'fully_covered',
                    'type' => 'weekend'
                ],
                [
                    'id' => 5,
                    'name' => 'Weekend Evening',
                    'start_time' => '16:00',
                    'end_time' => '24:00',
                    'duration_hours' => 8,
                    'department' => 'Front of House',
                    'required_staff' => 6,
                    'assigned_staff_count' => 4,
                    'assigned_staff' => [
                        ['id' => 4, 'name' => 'Sara Ahmed', 'role' => 'Server'],
                        ['id' => 7, 'name' => 'Yohannes Tesfaye', 'role' => 'Bartender'],
                        ['id' => 9, 'name' => 'Kedir Mohammed', 'role' => 'Server'],
                        ['id' => 10, 'name' => 'Tigist Wolde', 'role' => 'Server']
                    ],
                    'color' => '#EF4444',
                    'status' => 'partially_covered',
                    'type' => 'weekend'
                ]
            ];
        }
        
        return $shifts;
    }

    private function getShiftSummary(Carbon $weekStart, Carbon $weekEnd): array
    {
        return [
            'total_shifts' => 18,
            'total_staff_scheduled' => 24,
            'total_hours' => 156,
            'coverage_gaps' => 3,
            'fully_covered_shifts' => 14,
            'partially_covered_shifts' => 3,
            'uncovered_shifts' => 1,
            'departments' => [
                'Kitchen' => ['shifts' => 6, 'staff' => 8, 'hours' => 48],
                'Front of House' => ['shifts' => 10, 'staff' => 14, 'hours' => 84],
                'Bar' => ['shifts' => 2, 'staff' => 2, 'hours' => 24],
                'Management' => ['shifts' => 0, 'staff' => 0, 'hours' => 0]
            ],
            'shift_types' => [
                'regular' => 15,
                'weekend' => 2,
                'overtime' => 1,
                'training' => 0
            ]
        ];
    }

    private function getUpcomingShifts(): array
    {
        return [
            [
                'id' => 6,
                'name' => 'Morning Prep',
                'date' => Carbon::tomorrow(),
                'start_time' => '06:00',
                'end_time' => '14:00',
                'department' => 'Kitchen',
                'assigned_staff' => [
                    ['id' => 1, 'name' => 'Alemayehu Tadesse'],
                    ['id' => 2, 'name' => 'Meron Gebremedhin'],
                    ['id' => 3, 'name' => 'Dawit Bekele']
                ],
                'status' => 'confirmed',
                'hours_until' => Carbon::tomorrow()->setTime(6, 0)->diffInHours(Carbon::now())
            ],
            [
                'id' => 7,
                'name' => 'Lunch Service',
                'date' => Carbon::tomorrow(),
                'start_time' => '11:00',
                'end_time' => '16:00',
                'department' => 'Front of House',
                'assigned_staff' => [
                    ['id' => 4, 'name' => 'Sara Ahmed'],
                    ['id' => 5, 'name' => 'Fatima Hassan']
                ],
                'status' => 'partially_covered',
                'hours_until' => Carbon::tomorrow()->setTime(11, 0)->diffInHours(Carbon::now())
            ]
        ];
    }

    private function getCurrentShifts(): array
    {
        $now = Carbon::now();
        $currentHour = $now->hour;
        
        // Only return current shifts if it's during business hours
        if ($currentHour >= 6 && $currentHour <= 23) {
            return [
                [
                    'id' => 8,
                    'name' => 'Lunch Service',
                    'start_time' => '11:00',
                    'end_time' => '16:00',
                    'department' => 'Front of House',
                    'assigned_staff' => [
                        ['id' => 4, 'name' => 'Sara Ahmed', 'checked_in' => true],
                        ['id' => 5, 'name' => 'Fatima Hassan', 'checked_in' => true],
                        ['id' => 6, 'name' => 'Ahmed Ali', 'checked_in' => false]
                    ],
                    'status' => 'in_progress',
                    'progress_percentage' => 60,
                    'time_remaining' => '2h 30m'
                ]
            ];
        }
        
        return [];
    }

    private function getCoverageGaps(Carbon $weekStart, Carbon $weekEnd): array
    {
        return [
            [
                'date' => Carbon::tomorrow(),
                'shift_name' => 'Lunch Service',
                'time' => '11:00 - 16:00',
                'department' => 'Front of House',
                'required_staff' => 4,
                'assigned_staff' => 2,
                'gap_count' => 2,
                'priority' => 'high',
                'suggested_staff' => [
                    ['id' => 9, 'name' => 'Kedir Mohammed', 'availability' => 'available'],
                    ['id' => 10, 'name' => 'Tigist Wolde', 'availability' => 'available']
                ]
            ],
            [
                'date' => Carbon::now()->addDays(3),
                'shift_name' => 'Weekend Evening',
                'time' => '16:00 - 24:00',
                'department' => 'Front of House',
                'required_staff' => 6,
                'assigned_staff' => 4,
                'gap_count' => 2,
                'priority' => 'medium',
                'suggested_staff' => [
                    ['id' => 11, 'name' => 'Bereket Taye', 'availability' => 'available'],
                    ['id' => 12, 'name' => 'Selamawit Desta', 'availability' => 'unavailable']
                ]
            ]
        ];
    }

    private function getWeekNavigation(Carbon $weekStart): array
    {
        return [
            'current_week' => $weekStart->copy(),
            'previous_week' => $weekStart->copy()->subWeek(),
            'next_week' => $weekStart->copy()->addWeek(),
            'current_week_label' => $weekStart->format('M d') . ' - ' . $weekStart->copy()->endOfWeek()->format('M d, Y'),
            'is_current_week' => $weekStart->isSameWeek(Carbon::now())
        ];
    }
}
