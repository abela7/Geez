<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\StaffType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestAdmin extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:create-test {--username=admin} {--password=password} {--email=admin@example.com}';

    /**
     * The console command description.
     */
    protected $description = 'Create a test admin user for development';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $username = $this->option('username');
        $password = $this->option('password');
        $email = $this->option('email');

        // Check if user already exists
        if (Staff::where('username', $username)->exists()) {
            $this->error("User with username '{$username}' already exists!");

            return Command::FAILURE;
        }

        // Get or create admin staff type
        $adminType = StaffType::firstOrCreate(
            ['name' => 'Administrator'],
            [
                'display_name' => 'Administrator',
                'description' => 'System Administrator',
                'access_level' => 100,
                'is_active' => true,
                'created_by' => null,
            ]
        );

        // Create admin user
        $admin = Staff::create([
            'staff_type_id' => $adminType->id,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'phone' => '1234567890',
            'hire_date' => now(),
            'status' => 'active',
            'is_active' => true,
        ]);

        $this->info('✅ Test admin created successfully!');
        $this->info("📧 Email: {$email}");
        $this->info("👤 Username: {$username}");
        $this->info("🔑 Password: {$password}");
        $this->info('🌐 Login URL: '.url('/admin/login'));

        return Command::SUCCESS;
    }
}
