<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffShift;
use App\Models\StaffShiftAssignment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShiftAssignmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all staff members
        $staff = Staff::where('status', 'active')->get();
        
        // Get all shift templates
        $shifts = StaffShift::where('is_active', true)->get();
        
        if ($staff->isEmpty() || $shifts->isEmpty()) {
            $this->command->error('❌ No active staff or shifts found! Please create staff and shifts first.');
            return;
        }
        
        $assignmentsCreated = 0;
        
        // Assign staff to shifts for the next 7 days
        for ($day = 0; $day < 7; $day++) {
            $date = Carbon::now()->addDays($day);
            
            foreach ($shifts as $shift) {
                // Find appropriate staff for this shift type
                $suitableStaff = $this->findSuitableStaff($staff, $shift);
                
                if ($suitableStaff) {
                    // Check if already assigned
                    $existingAssignment = StaffShiftAssignment::where([
                        'staff_id' => $suitableStaff->id,
                        'staff_shift_id' => $shift->id,
                        'assigned_date' => $date->format('Y-m-d'),
                    ])->first();
                    
                    if (!$existingAssignment) {
                        StaffShiftAssignment::create([
                            'staff_id' => $suitableStaff->id,
                            'staff_shift_id' => $shift->id,
                            'assigned_date' => $date->format('Y-m-d'),
                            'status' => 'scheduled',
                            'notes' => 'Auto-assigned for demonstration',
                            'assigned_by' => Staff::first()->id, // Use first staff member as assigned_by
                        ]);
                        
                        $assignmentsCreated++;
                        $this->command->info("✅ Assigned {$suitableStaff->full_name} to {$shift->name} on {$date->format('Y-m-d')}");
                    }
                }
            }
        }
        
        $this->command->info("🎉 Created {$assignmentsCreated} shift assignments!");
        
        // Show summary
        $this->command->table(
            ['Staff Member', 'Shift', 'Date', 'Status'],
            StaffShiftAssignment::with(['staff', 'shift'])
                ->orderBy('assigned_date')
                ->orderBy('staff_id')
                ->get()
                ->map(fn($assignment) => [
                    $assignment->staff->full_name,
                    $assignment->shift->name,
                    $assignment->assigned_date,
                    ucfirst($assignment->status),
                ])
                ->toArray()
        );
    }
    
    /**
     * Find suitable staff for a shift based on staff type and shift requirements.
     */
    private function findSuitableStaff($staff, $shift)
    {
        // Simple matching logic - you can enhance this
        $shiftName = strtolower($shift->name);
        
        foreach ($staff as $member) {
            $staffType = strtolower($member->staffType->name ?? '');
            
            // Match staff types to shift names
            if (strpos($shiftName, 'chef') !== false && strpos($staffType, 'chef') !== false) {
                return $member;
            }
            if (strpos($shiftName, 'waiter') !== false && strpos($staffType, 'waiter') !== false) {
                return $member;
            }
            if (strpos($shiftName, 'injera') !== false && strpos($staffType, 'injera') !== false) {
                return $member;
            }
            if (strpos($shiftName, 'porter') !== false && strpos($staffType, 'porter') !== false) {
                return $member;
            }
        }
        
        // If no specific match, return first available staff
        return $staff->first();
    }
}
