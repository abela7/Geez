<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get staff types
        $systemAdmin = StaffType::where('name', 'system_admin')->first();
        $administrator = StaffType::where('name', 'administrator')->first();
        
        if (!$systemAdmin || !$administrator) {
            $this->command->error('❌ Staff types not found! Please run StaffTypeSeeder first.');
            return;
        }

        // Create default system admin
        $admin = Staff::firstOrCreate(
            ['username' => 'admin'],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin123'), // Change in production!
                'staff_type_id' => $systemAdmin->id,
                'email' => 'admin@geez-restaurant.com',
                'hire_date' => now()->subYears(2),
                'status' => 'active',
            ]
        );

        // Create sample administrator
        $manager = Staff::firstOrCreate(
            ['username' => 'manager'],
            [
                'first_name' => 'Restaurant',
                'last_name' => 'Manager',
                'username' => 'manager',
                'password' => Hash::make('manager123'),
                'staff_type_id' => $administrator->id,
                'email' => 'manager@geez-restaurant.com',
                'hire_date' => now()->subYear(),
                'status' => 'active',
            ]
        );

        $this->command->info('✅ Staff members created successfully!');
        $this->command->table(
            ['Username', 'Full Name', 'Staff Type', 'Status'],
            Staff::with('staffType')->get()->map(fn($staff) => [
                $staff->username,
                $staff->full_name,
                $staff->staffType->display_name ?? 'No Type',
                $staff->status
            ])->toArray()
        );

        $this->command->warn('🔐 Default Login Credentials:');
        $this->command->line('   Username: admin');
        $this->command->line('   Password: admin123');
        $this->command->line('');
        $this->command->line('   Username: manager');
        $this->command->line('   Password: manager123');
        $this->command->line('');
        $this->command->error('⚠️  IMPORTANT: Change these passwords in production!');
    }
}