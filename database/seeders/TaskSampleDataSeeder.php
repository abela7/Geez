<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffTask;
use App\Models\StaffTaskAssignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating sample task data...');

        // Get existing staff members or create some if none exist
        $staff = Staff::active()->get();

        if ($staff->isEmpty()) {
            $this->command->info('No staff members found. Creating sample staff...');

            // Create sample staff members
            $staff = collect([
                Staff::create([
                    'id' => Str::ulid(),
                    'first_name' => 'John',
                    'last_name' => 'Chef',
                    'email' => 'john.chef@restaurant.com',
                    'phone' => '+1234567890',
                    'employee_id' => 'EMP001',
                    'staff_type_id' => '01K5E5G8J1MH9KM7A0S1E6N915', // Assuming this exists
                    'hire_date' => now()->subMonths(6),
                    'status' => 'active',
                    'created_by' => '01K5E5GE8XBXG9V91DMK9NEB4Q', // Assuming admin user
                ]),
                Staff::create([
                    'id' => Str::ulid(),
                    'first_name' => 'Sarah',
                    'last_name' => 'Server',
                    'email' => 'sarah.server@restaurant.com',
                    'phone' => '+1234567891',
                    'employee_id' => 'EMP002',
                    'staff_type_id' => '01K5E5G8JEM6M9N29AY8AC0601', // Assuming this exists
                    'hire_date' => now()->subMonths(3),
                    'status' => 'active',
                    'created_by' => '01K5E5GE8XBXG9V91DMK9NEB4Q',
                ]),
                Staff::create([
                    'id' => Str::ulid(),
                    'first_name' => 'Mike',
                    'last_name' => 'Manager',
                    'email' => 'mike.manager@restaurant.com',
                    'phone' => '+1234567892',
                    'employee_id' => 'EMP003',
                    'staff_type_id' => '01K5E5G8K4ASYPQGVXH16TZSN5', // Assuming this exists
                    'hire_date' => now()->subYear(),
                    'status' => 'active',
                    'created_by' => '01K5E5GE8XBXG9V91DMK9NEB4Q',
                ]),
            ]);
        }

        // Create sample tasks
        $tasks = [
            [
                'title' => 'Daily Kitchen Prep',
                'description' => 'Prepare vegetables, check inventory, and set up cooking stations for the day.',
                'task_type' => 'daily',
                'priority' => 'high',
                'category' => 'kitchen',
                'estimated_hours' => 2.5,
                'is_active' => true,
            ],
            [
                'title' => 'Weekly Deep Clean',
                'description' => 'Deep clean all kitchen equipment, sanitize surfaces, and organize storage areas.',
                'task_type' => 'weekly',
                'priority' => 'medium',
                'category' => 'cleaning',
                'estimated_hours' => 4.0,
                'is_active' => true,
            ],
            [
                'title' => 'Menu Update Project',
                'description' => 'Review current menu items, analyze sales data, and propose new seasonal dishes.',
                'task_type' => 'one_time',
                'priority' => 'medium',
                'category' => 'administration',
                'estimated_hours' => 8.0,
                'is_active' => true,
            ],
            [
                'title' => 'Equipment Maintenance Check',
                'description' => 'Inspect all kitchen equipment, check for wear and tear, and schedule repairs if needed.',
                'task_type' => 'monthly',
                'priority' => 'high',
                'category' => 'maintenance',
                'estimated_hours' => 3.0,
                'is_active' => true,
            ],
            [
                'title' => 'Customer Service Training',
                'description' => 'Conduct training session on customer service best practices and complaint handling.',
                'task_type' => 'one_time',
                'priority' => 'medium',
                'category' => 'service',
                'estimated_hours' => 2.0,
                'is_active' => true,
            ],
            [
                'title' => 'Inventory Stock Count',
                'description' => 'Count all inventory items, update stock levels, and identify items needing reorder.',
                'task_type' => 'weekly',
                'priority' => 'high',
                'category' => 'inventory',
                'estimated_hours' => 3.5,
                'is_active' => true,
            ],
        ];

        $createdTasks = [];
        foreach ($tasks as $taskData) {
            $task = StaffTask::create([
                'id' => Str::ulid(),
                ...$taskData,
                'created_by' => '01K5E5GE8XBXG9V91DMK9NEB4Q',
            ]);
            $createdTasks[] = $task;
        }

        $this->command->info('Created '.count($createdTasks).' sample tasks.');

        // Create sample assignments
        $assignments = [];
        foreach ($createdTasks as $index => $task) {
            // Assign each task to 1-2 random staff members
            $assignedStaff = $staff->random(rand(1, 2));

            foreach ($assignedStaff as $staffMember) {
                $dueDate = now()->addDays(rand(1, 14)); // Due in 1-14 days
                $status = ['pending', 'in_progress', 'completed'][rand(0, 2)];

                $assignment = StaffTaskAssignment::create([
                    'id' => Str::ulid(),
                    'staff_task_id' => $task->id,
                    'staff_id' => $staffMember->id,
                    'assigned_date' => now()->subDays(rand(0, 7)),
                    'due_date' => $dueDate,
                    'status' => $status,
                    'progress_percentage' => $status === 'completed' ? 100 : rand(0, 80),
                    'notes' => 'Sample assignment for testing purposes.',
                    'assigned_by' => '01K5E5GE8XBXG9V91DMK9NEB4Q',
                    'started_at' => $status !== 'pending' ? now()->subDays(rand(0, 3)) : null,
                    'completed_at' => $status === 'completed' ? now()->subDays(rand(0, 2)) : null,
                    'completed_by' => $status === 'completed' ? $staffMember->id : null,
                ]);

                $assignments[] = $assignment;
            }
        }

        $this->command->info('Created '.count($assignments).' sample task assignments.');

        $this->command->info('✅ Sample task data created successfully!');
        $this->command->info('📋 Tasks: '.count($createdTasks));
        $this->command->info('👥 Assignments: '.count($assignments));
        $this->command->info('🎯 You can now test the task management system!');
    }
}
