<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffShift;
use Illuminate\Database\Seeder;

class ShiftTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first staff member to use as creator
        $staff = Staff::first();

        if (! $staff) {
            $this->command->error('No staff members found. Please run StaffSeeder first.');

            return;
        }

        $this->command->info('Creating test shifts...');

        // Create Morning Kitchen Shift
        StaffShift::create([
            'name' => 'Morning Kitchen Shift',
            'department' => 'Kitchen',
            'shift_type' => 'regular',
            'description' => 'Morning kitchen preparation and breakfast service',
            'start_time' => '06:00',
            'end_time' => '14:00',
            'break_minutes' => 30,
            'days_of_week' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'min_staff_required' => 3,
            'max_staff_allowed' => 5,
            'hourly_rate_multiplier' => 1.00,
            'is_active' => true,
            'color_code' => '#10B981',
            'created_by' => $staff->id,
        ]);

        // Create Evening Service Shift
        StaffShift::create([
            'name' => 'Evening Service Shift',
            'department' => 'Front of House',
            'shift_type' => 'regular',
            'description' => 'Evening dining service',
            'start_time' => '17:00',
            'end_time' => '23:00',
            'break_minutes' => 20,
            'days_of_week' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
            'min_staff_required' => 2,
            'max_staff_allowed' => 4,
            'hourly_rate_multiplier' => 1.00,
            'is_active' => true,
            'color_code' => '#3B82F6',
            'created_by' => $staff->id,
        ]);

        // Create Weekend Bar Shift
        StaffShift::create([
            'name' => 'Weekend Bar Shift',
            'department' => 'Bar',
            'shift_type' => 'weekend',
            'description' => 'Weekend bar service',
            'start_time' => '20:00',
            'end_time' => '02:00',
            'break_minutes' => 15,
            'days_of_week' => ['friday', 'saturday'],
            'min_staff_required' => 2,
            'max_staff_allowed' => 3,
            'hourly_rate_multiplier' => 1.25,
            'is_active' => true,
            'is_holiday_shift' => false,
            'color_code' => '#8B5CF6',
            'created_by' => $staff->id,
        ]);

        $this->command->info('✅ Created 3 test shifts successfully!');
    }
}

