<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StaffType;
use Illuminate\Database\Seeder;

class StaffTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default staff types
        StaffType::createDefaultTypes();

        $this->command->info('✅ Staff types created successfully!');
        $this->command->table(
            ['Name', 'Display Name', 'Priority', 'Status'],
            StaffType::all()->map(fn ($type) => [
                $type->name,
                $type->display_name,
                $type->priority,
                $type->is_active ? 'Active' : 'Inactive',
            ])->toArray()
        );
    }
}
