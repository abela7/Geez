<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffShift;
use App\Models\StaffShiftAssignment;
use App\Models\StaffType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScheduleTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating test data for schedule system...');

        // Clear existing assignments for current week to avoid conflicts
        $currentWeek = Carbon::now()->startOfWeek();
        $endOfWeek = $currentWeek->copy()->endOfWeek();
        
        StaffShiftAssignment::whereBetween('assigned_date', [
            $currentWeek->format('Y-m-d'), 
            $endOfWeek->format('Y-m-d')
        ])->delete();

        // Create staff types if they don't exist
        $staffTypes = [
            ['name' => 'Server', 'display_name' => 'Server', 'description' => 'Food service staff'],
            ['name' => 'Bartender', 'display_name' => 'Bartender', 'description' => 'Bar service staff'],
            ['name' => 'Host', 'display_name' => 'Host/Hostess', 'description' => 'Front of house greeting'],
            ['name' => 'Kitchen', 'display_name' => 'Kitchen Staff', 'description' => 'Kitchen preparation staff'],
            ['name' => 'Manager', 'display_name' => 'Manager', 'description' => 'Management staff'],
        ];

        foreach ($staffTypes as $type) {
            StaffType::firstOrCreate(['name' => $type['name']], $type);
        }

        // Use existing staff or create minimal new ones with unique usernames
        $this->ensureMinimalStaff();

        // Create shift templates
        $this->createShiftTemplates();

        // Create some sample assignments for the current week
        $this->createSampleAssignments();

        $this->command->info('Schedule test data created successfully!');
    }

    /**
     * Ensure we have minimal staff for testing.
     */
    private function ensureMinimalStaff(): void
    {
        $existingStaff = Staff::where('status', 'active')->count();
        
        if ($existingStaff >= 5) {
            $this->command->info("Found {$existingStaff} existing staff members, using them for testing");
            return;
        }

        // Create only missing staff with unique usernames
        $staffToCreate = 5 - $existingStaff;
        $this->command->info("Creating {$staffToCreate} additional staff members for testing");

        $staffTypes = StaffType::all()->keyBy('name');
        
        for ($i = 1; $i <= $staffToCreate; $i++) {
            $username = 'teststaff' . $i;
            
            if (Staff::where('username', $username)->exists()) {
                continue;
            }

            $types = ['Server', 'Bartender', 'Host', 'Kitchen', 'Manager'];
            $randomType = $types[array_rand($types)];
            
            Staff::create([
                'first_name' => 'Test',
                'last_name' => 'Staff' . $i,
                'username' => $username,
                'email' => $username . '@geez.restaurant',
                'staff_type_id' => $staffTypes[$randomType]->id,
                'password' => 'password123',
                'hire_date' => Carbon::now()->subMonths(rand(1, 12)),
                'status' => 'active',
            ]);
        }
    }

    /**
     * Create shift templates.
     */
    private function createShiftTemplates(): void
    {
        $shifts = [
            [
                'name' => 'Morning Shift',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'min_staff_required' => 3,
                'max_staff_allowed' => 5,
                'days_of_week' => [1, 2, 3, 4, 5], // Monday to Friday
                'color_code' => '#10B981',
                'description' => 'Morning service shift',
            ],
            [
                'name' => 'Evening Shift',
                'start_time' => '16:00:00',
                'end_time' => '23:00:00',
                'min_staff_required' => 4,
                'max_staff_allowed' => 6,
                'days_of_week' => [1, 2, 3, 4, 5, 6, 0], // All days
                'color_code' => '#3B82F6',
                'description' => 'Evening service shift',
            ],
            [
                'name' => 'Weekend Brunch',
                'start_time' => '10:00:00',
                'end_time' => '15:00:00',
                'min_staff_required' => 2,
                'max_staff_allowed' => 4,
                'days_of_week' => [6, 0], // Saturday and Sunday
                'color_code' => '#F59E0B',
                'description' => 'Weekend brunch service',
            ],
            [
                'name' => 'Bar Shift',
                'start_time' => '18:00:00',
                'end_time' => '02:00:00',
                'min_staff_required' => 2,
                'max_staff_allowed' => 3,
                'days_of_week' => [5, 6], // Friday and Saturday
                'color_code' => '#8B5CF6',
                'description' => 'Late night bar service',
            ],
        ];

        foreach ($shifts as $shift) {
            $firstStaff = Staff::first();
            
            StaffShift::firstOrCreate(
                ['name' => $shift['name']],
                array_merge($shift, [
                    'is_active' => true,
                    'is_template' => false,
                    'break_minutes' => 30,
                    'department' => 'Restaurant',
                    'shift_type' => 'regular',
                    'created_by' => $firstStaff?->id,
                ])
            );
        }
    }

    /**
     * Create sample assignments for testing.
     */
    private function createSampleAssignments(): void
    {
        $shifts = StaffShift::where('is_active', true)->get();
        $staff = Staff::where('status', 'active')->get();
        
        if ($shifts->isEmpty() || $staff->isEmpty()) {
            $this->command->warn('No shifts or staff found for creating assignments');
            return;
        }

        $currentWeek = Carbon::now()->startOfWeek();
        
        // Create assignments for the current week
        for ($day = 0; $day < 7; $day++) {
            $date = $currentWeek->copy()->addDays($day);
            $dayOfWeek = $date->dayOfWeek;
            
            foreach ($shifts as $shift) {
                // Check if shift is scheduled for this day
                if (!in_array($dayOfWeek, $shift->days_of_week ?? [])) {
                    continue;
                }
                
                // Randomly assign staff to this shift (but not over-assign)
                $assignCount = rand(1, min($shift->max_staff_allowed, $staff->count()));
                $assignedStaff = $staff->random($assignCount);
                
                foreach ($assignedStaff as $staffMember) {
                    // Check if staff is already assigned for this date
                    $existingAssignment = StaffShiftAssignment::where('staff_id', $staffMember->id)
                        ->where('assigned_date', $date->format('Y-m-d'))
                        ->first();
                    
                    if ($existingAssignment) {
                        continue;
                    }
                    
                    // Determine role based on staff type (store in notes for now)
                    $role = match($staffMember->staffType?->name) {
                        'Server' => 'Server',
                        'Bartender' => 'Bartender',
                        'Host' => 'Host',
                        'Kitchen' => 'Kitchen Staff',
                        'Manager' => 'Shift Manager',
                        default => 'Staff',
                    };
                    
                    StaffShiftAssignment::create([
                        'staff_shift_id' => $shift->id,
                        'staff_id' => $staffMember->id,
                        'assigned_date' => $date->format('Y-m-d'),
                        'status' => 'scheduled',
                        'notes' => "Role: {$role} - Sample assignment for testing",
                        'assigned_by' => $staff->where('staffType.name', 'Manager')->first()?->id ?? $staff->first()->id,
                    ]);
                }
            }
        }
        
        $this->command->info('Sample shift assignments created for current week');
    }
}
