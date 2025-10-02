<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Kitchen',
                'slug' => 'kitchen',
                'description' => 'Food preparation, cooking, and kitchen operations',
                'color' => '#EF4444',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Front of House',
                'slug' => 'front-of-house',
                'description' => 'Customer service, serving, and dining room operations',
                'color' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Bar',
                'slug' => 'bar',
                'description' => 'Beverage preparation and bar service',
                'color' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Management',
                'slug' => 'management',
                'description' => 'Supervisory and administrative roles',
                'color' => '#059669',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Cleaning',
                'slug' => 'cleaning',
                'description' => 'Housekeeping and sanitation services',
                'color' => '#06B6D4',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Security',
                'slug' => 'security',
                'description' => 'Safety and security operations',
                'color' => '#DC2626',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Maintenance',
                'slug' => 'maintenance',
                'description' => 'Equipment and facility maintenance',
                'color' => '#D97706',
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
