<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeeklySchedule;
use App\Models\WeeklyRotaTemplate;
use App\Models\Staff;
use App\Models\StaffShift;
use App\Services\WeeklyScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyScheduleController extends Controller
{
    public function __construct(
        private WeeklyScheduleService $weeklyScheduleService
    ) {}

    /**
     * Display weekly schedule management interface.
     */
    public function index(Request $request)
    {
        try {
            $currentDate = $request->get('date') ? Carbon::parse($request->get('date')) : now();
            $weekStart = $currentDate->copy()->startOfWeek(Carbon::MONDAY);
            $weekEnd = $currentDate->copy()->endOfWeek(Carbon::SUNDAY);

            // Get or create weekly schedule for current week
            $weeklySchedule = $this->weeklyScheduleService->getOrCreateScheduleForDate(
                $currentDate,
                null // Temporarily set to null to avoid auth issues
            );

            // Get available templates
            $templates = WeeklyRotaTemplate::where('is_active', true)
                ->with('assignments.staff', 'assignments.shift')
                ->orderBy('name')
                ->get();

            // Get schedule statistics for the last 4 weeks
            $statisticsStartDate = $weekStart->copy()->subWeeks(3);
            $statistics = $this->weeklyScheduleService->getStatistics($statisticsStartDate, $weekEnd);

            // Get assignments grouped by day
            $assignmentsByDay = $weeklySchedule->getAssignmentsByDay();

            // Get conflicts for this schedule
            $conflicts = $this->weeklyScheduleService->getScheduleConflicts($weeklySchedule);

            // Get upcoming schedules needing attention
            $upcomingSchedules = $this->weeklyScheduleService->getUpcomingSchedulesNeedingAttention();

            return view('admin.shifts.weekly-schedule.index', compact(
                'weeklySchedule',
                'templates',
                'statistics',
                'assignmentsByDay',
                'conflicts',
                'upcomingSchedules',
                'currentDate',
                'weekStart',
                'weekEnd'
            ));
        } catch (\Exception $e) {
            // For debugging
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Apply a template to the weekly schedule.
     */
    public function applyTemplate(Request $request, WeeklySchedule $weeklySchedule)
    {
        $request->validate([
            'template_id' => 'required|exists:weekly_rota_templates,id',
        ]);

        try {
            $template = WeeklyRotaTemplate::findOrFail($request->template_id);
            
            $this->weeklyScheduleService->applyTemplateToSchedule($weeklySchedule, $template);

            return response()->json([
                'success' => true,
                'message' => __('shifts.weekly_schedule.template_applied_success'),
                'schedule' => $weeklySchedule->fresh()->load('assignments.staffShiftAssignment.staff'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Publish a weekly schedule.
     */
    public function publish(WeeklySchedule $weeklySchedule)
    {
        try {
            $this->weeklyScheduleService->publishSchedule(
                $weeklySchedule,
                auth()->user()->staff->id ?? null
            );

            return response()->json([
                'success' => true,
                'message' => __('shifts.weekly_schedule.schedule_published'),
                'schedule' => $weeklySchedule->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create a manual assignment for the weekly schedule.
     */
    public function createAssignment(Request $request, WeeklySchedule $weeklySchedule)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'shift_id' => 'required|exists:staff_shifts,id',
            'day_of_week' => 'required|integer|between:0,6',
        ]);

        try {
            $assignment = $this->weeklyScheduleService->createManualAssignment(
                $weeklySchedule,
                $request->staff_id,
                $request->shift_id,
                $request->day_of_week,
                auth()->user()->staff->id ?? null
            );

            return response()->json([
                'success' => true,
                'message' => __('shifts.assignments.assign') . ' ' . __('shifts.common.complete'),
                'assignment' => $assignment->load('staffShiftAssignment.staff', 'staffShiftAssignment.shift'),
                'schedule' => $weeklySchedule->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove an assignment from the weekly schedule.
     */
    public function removeAssignment(WeeklySchedule $weeklySchedule, $assignmentId)
    {
        try {
            $assignment = $weeklySchedule->assignments()->findOrFail($assignmentId);
            
            $this->weeklyScheduleService->removeAssignment($assignment);

            return response()->json([
                'success' => true,
                'message' => 'Assignment removed successfully',
                'schedule' => $weeklySchedule->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Copy assignments from another week.
     */
    public function copyFromWeek(Request $request, WeeklySchedule $weeklySchedule)
    {
        $request->validate([
            'source_date' => 'required|date',
        ]);

        try {
            $sourceDate = Carbon::parse($request->source_date);
            $sourceSchedule = WeeklySchedule::forDate($sourceDate);

            if (!$sourceSchedule) {
                throw new \Exception('Source week schedule not found');
            }

            $this->weeklyScheduleService->copyWeeklySchedule($sourceSchedule, $weeklySchedule);

            return response()->json([
                'success' => true,
                'message' => 'Schedule copied successfully',
                'schedule' => $weeklySchedule->fresh()->load('assignments.staffShiftAssignment.staff'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get weekly schedule data for API/AJAX requests.
     */
    public function show(WeeklySchedule $weeklySchedule)
    {
        $weeklySchedule->load([
            'assignments.staffShiftAssignment.staff',
            'assignments.staffShiftAssignment.shift',
            'template',
            'creator',
            'publisher'
        ]);

        $assignmentsByDay = $weeklySchedule->getAssignmentsByDay();
        $conflicts = $this->weeklyScheduleService->getScheduleConflicts($weeklySchedule);

        return response()->json([
            'schedule' => $weeklySchedule,
            'assignments_by_day' => $assignmentsByDay,
            'conflicts' => $conflicts,
        ]);
    }

    /**
     * Get available staff and shifts for assignment creation.
     */
    public function getAssignmentOptions(WeeklySchedule $weeklySchedule)
    {
        $staff = Staff::with('profile')
            ->where('is_active', true)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $shifts = StaffShift::where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'staff' => $staff,
            'shifts' => $shifts,
        ]);
    }

    /**
     * Archive a weekly schedule.
     */
    public function archive(WeeklySchedule $weeklySchedule)
    {
        try {
            $this->weeklyScheduleService->archiveSchedule($weeklySchedule);

            return response()->json([
                'success' => true,
                'message' => 'Schedule archived successfully',
                'schedule' => $weeklySchedule->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get weekly schedule statistics.
     */
    public function statistics(Request $request)
    {
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date'))
            : now()->subMonths(3);
            
        $endDate = $request->get('end_date')
            ? Carbon::parse($request->get('end_date'))
            : now();

        $statistics = $this->weeklyScheduleService->getStatistics($startDate, $endDate);

        return response()->json($statistics);
    }

    /**
     * Auto-create upcoming weekly schedules.
     */
    public function autoCreateUpcoming(Request $request)
    {
        $weeksAhead = $request->get('weeks_ahead', 4);
        
        try {
            $created = $this->weeklyScheduleService->autoCreateUpcomingSchedules(
                $weeksAhead,
                auth()->user()->staff->id ?? null
            );

            return response()->json([
                'success' => true,
                'message' => count($created) . ' weekly schedules created',
                'created_schedules' => $created,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
