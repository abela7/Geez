<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SampleStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the staff members to create
        $staffMembers = [
            [
                'first_name' => 'Michael',
                'last_name' => 'Werkneh',
                'staff_type_name' => 'administrator',
                'email' => 'michael.werkneh@geez-restaurant.com',
                'phone' => '+251911123456',
                'hire_date' => $this->randomHireDate(), // Between 6 months and 2 years ago
            ],
            [
                'first_name' => 'Sara',
                'last_name' => 'Teshome',
                'staff_type_name' => 'management',
                'email' => 'sara.teshome@geez-restaurant.com',
                'phone' => '+251922234567',
                'hire_date' => $this->randomHireDate(),
            ],
            [
                'first_name' => 'Fani',
                'last_name' => 'Alemu',
                'staff_type_name' => 'chef',
                'email' => 'fani.alemu@geez-restaurant.com',
                'phone' => '+251933345678',
                'hire_date' => $this->randomHireDate(),
            ],
            [
                'first_name' => 'Sosi',
                'last_name' => 'Wendmu',
                'staff_type_name' => 'chef',
                'email' => 'sosi.wendmu@geez-restaurant.com',
                'phone' => '+251944456789',
                'hire_date' => $this->randomHireDate(),
            ],
            [
                'first_name' => 'Lidiya',
                'last_name' => 'Mengstu',
                'staff_type_name' => 'waiter',
                'email' => 'lidiya.mengstu@geez-restaurant.com',
                'phone' => '+251955567890',
                'hire_date' => $this->randomHireDate(),
            ],
            [
                'first_name' => 'Kibra',
                'last_name' => 'Alex',
                'staff_type_name' => 'waiter',
                'email' => 'kibra.alex@geez-restaurant.com',
                'phone' => '+251966678901',
                'hire_date' => $this->randomHireDate(),
            ],
            [
                'first_name' => 'Senait',
                'last_name' => 'Tadesse',
                'staff_type_name' => 'injera_maker',
                'email' => 'senait.tadesse@geez-restaurant.com',
                'phone' => '+251977789012',
                'hire_date' => $this->randomHireDate(),
            ],
            [
                'first_name' => 'Roza',
                'last_name' => 'Tajebe',
                'staff_type_name' => 'kitchen_porter',
                'email' => 'roza.tajebe@geez-restaurant.com',
                'phone' => '+251988890123',
                'hire_date' => $this->randomHireDate(),
            ],
        ];

        $createdCount = 0;

        foreach ($staffMembers as $staffData) {
            // Get the staff type
            $staffType = StaffType::where('name', $staffData['staff_type_name'])->first();

            if (!$staffType) {
                $this->command->error("❌ Staff type '{$staffData['staff_type_name']}' not found! Skipping {$staffData['first_name']} {$staffData['last_name']}.");
                continue;
            }

            // Generate username
            $username = strtolower($staffData['first_name'] . '_' . $staffData['last_name']);

            // Check if staff already exists
            $existingStaff = Staff::where('username', $username)->first();

            if ($existingStaff) {
                $this->command->warn("⚠️  Staff member '{$username}' already exists. Skipping.");
                continue;
            }

            // Create the staff member
            $staff = Staff::create([
                'first_name' => $staffData['first_name'],
                'last_name' => $staffData['last_name'],
                'username' => $username,
                'password' => Hash::make('00000000'),
                'email' => $staffData['email'],
                'phone' => $staffData['phone'],
                'staff_type_id' => $staffType->id,
                'hire_date' => $staffData['hire_date'],
                'status' => 'active',
            ]);

            $createdCount++;
            $this->command->info("✅ Created: {$staff->full_name} ({$staffType->display_name}) - Username: {$username}");
        }

        $this->command->info("🎉 Successfully created {$createdCount} staff members!");

        // Display summary table
        $this->command->info("\n📋 Staff Members Summary:");
        $this->command->table(
            ['Username', 'Full Name', 'Staff Type', 'Email', 'Hire Date', 'Status'],
            Staff::whereIn('username', array_map(function($staff) {
                return strtolower($staff['first_name'] . '_' . $staff['last_name']);
            }, $staffMembers))
            ->with('staffType')
            ->get()
            ->map(fn ($staff) => [
                $staff->username,
                $staff->full_name,
                $staff->staffType->display_name ?? 'No Type',
                $staff->email,
                $staff->hire_date ? $staff->hire_date->format('Y-m-d') : 'N/A',
                ucfirst($staff->status),
            ])
            ->toArray()
        );

        $this->command->warn("\n🔐 Login Credentials for all staff:");
        $this->command->line('   Password: 00000000');
        $this->command->line('   Status: Active');
        $this->command->error('⚠️  IMPORTANT: Change these passwords in production!');
    }

    /**
     * Generate a random hire date between 6 months and 2 years ago.
     */
    private function randomHireDate(): Carbon
    {
        // 6 months ago = 180 days
        // 2 years ago = 730 days
        $daysAgo = rand(180, 730);

        return now()->subDays($daysAgo);
    }
}
