<?php

namespace Database\Seeders;

use App\Models\ShiftType;
use Illuminate\Database\Seeder;

class ShiftTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shiftTypes = [
            [
                'name' => 'Regular',
                'slug' => 'regular',
                'description' => 'Standard daily shift',
                'color' => '#6B7280',
                'is_active' => true,
                'default_hourly_rate' => 15.00,
                'default_overtime_rate' => 22.50,
                'sort_order' => 1,
            ],
            [
                'name' => 'Weekend',
                'slug' => 'weekend',
                'description' => 'Weekend shift with higher rates',
                'color' => '#F59E0B',
                'is_active' => true,
                'default_hourly_rate' => 18.00,
                'default_overtime_rate' => 27.00,
                'sort_order' => 2,
            ],
            [
                'name' => 'Overtime',
                'slug' => 'overtime',
                'description' => 'Extended hours shift',
                'color' => '#EF4444',
                'is_active' => true,
                'default_hourly_rate' => 20.00,
                'default_overtime_rate' => 30.00,
                'sort_order' => 3,
            ],
            [
                'name' => 'Training',
                'slug' => 'training',
                'description' => 'Training and orientation shift',
                'color' => '#3B82F6',
                'is_active' => true,
                'default_hourly_rate' => 12.00,
                'default_overtime_rate' => 18.00,
                'sort_order' => 4,
            ],
            [
                'name' => 'Meeting',
                'slug' => 'meeting',
                'description' => 'Staff meetings and planning sessions',
                'color' => '#8B5CF6',
                'is_active' => true,
                'default_hourly_rate' => 15.00,
                'default_overtime_rate' => 22.50,
                'sort_order' => 5,
            ],
            [
                'name' => 'Event',
                'slug' => 'event',
                'description' => 'Special events and catering',
                'color' => '#EC4899',
                'is_active' => true,
                'default_hourly_rate' => 22.00,
                'default_overtime_rate' => 33.00,
                'sort_order' => 6,
            ],
            [
                'name' => 'Maintenance',
                'slug' => 'maintenance',
                'description' => 'Equipment and facility maintenance',
                'color' => '#059669',
                'is_active' => true,
                'default_hourly_rate' => 16.00,
                'default_overtime_rate' => 24.00,
                'sort_order' => 7,
            ],
        ];

        foreach ($shiftTypes as $shiftType) {
            ShiftType::create($shiftType);
        }
    }
}
