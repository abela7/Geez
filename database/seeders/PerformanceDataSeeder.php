<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffPerformanceGoal;
use App\Models\StaffPerformanceMetric;
use App\Models\StaffPerformanceReview;
use App\Models\StaffPerformanceTemplate;
use App\Models\StaffProfile;
use App\Models\StaffType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PerformanceDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create staff types if they don't exist
        $waiterType = StaffType::firstOrCreate([
            'name' => 'waiter',
        ], [
            'display_name' => 'Waiter',
            'description' => 'Restaurant service staff responsible for taking orders and serving customers',
            'priority' => 30,
            'is_active' => true,
        ]);

        $chefType = StaffType::firstOrCreate([
            'name' => 'chef',
        ], [
            'display_name' => 'Chef',
            'description' => 'Kitchen staff responsible for food preparation and cooking',
            'priority' => 50,
            'is_active' => true,
        ]);

        // Create sample waiter
        $waiter = Staff::create([
            'staff_type_id' => $waiterType->id,
            'username' => 'sarah.johnson',
            'email' => 'sarah.johnson@restaurant.com',
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'phone' => '+1234567890',
            'hire_date' => now()->subMonths(8),
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        // Create waiter profile
        StaffProfile::create([
            'staff_id' => $waiter->id,
            'employee_id' => 'EMP001',
            'date_of_birth' => now()->subYears(25),
            'address' => '123 Main Street, City, State 12345',
            'emergency_contacts' => json_encode([
                ['name' => 'John Johnson', 'relationship' => 'Spouse', 'phone' => '+1234567891'],
            ]),
            'hourly_rate' => 15.50,
            'notes' => 'Excellent customer service skills, very reliable',
        ]);

        // Create sample chef
        $chef = Staff::create([
            'staff_type_id' => $chefType->id,
            'username' => 'mike.chen',
            'email' => 'mike.chen@restaurant.com',
            'first_name' => 'Mike',
            'last_name' => 'Chen',
            'phone' => '+1234567892',
            'hire_date' => now()->subMonths(14),
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        // Create chef profile
        StaffProfile::create([
            'staff_id' => $chef->id,
            'employee_id' => 'EMP002',
            'date_of_birth' => now()->subYears(32),
            'address' => '456 Oak Avenue, City, State 12345',
            'emergency_contacts' => json_encode([
                ['name' => 'Lisa Chen', 'relationship' => 'Wife', 'phone' => '+1234567893'],
            ]),
            'hourly_rate' => 22.00,
            'notes' => 'Experienced chef with excellent knife skills and food safety knowledge',
        ]);

        // Create performance templates
        $waiterTemplate = StaffPerformanceTemplate::create([
            'staff_type_id' => $waiterType->id,
            'template_name' => 'Waiter Quarterly Review',
            'review_frequency' => 'quarterly',
            'rating_criteria' => [
                ['key' => 'customer_service', 'weight' => 30, 'description' => 'Customer interaction and satisfaction'],
                ['key' => 'punctuality', 'weight' => 20, 'description' => 'Timeliness and attendance'],
                ['key' => 'teamwork', 'weight' => 20, 'description' => 'Collaboration with team members'],
                ['key' => 'order_accuracy', 'weight' => 20, 'description' => 'Accuracy in taking and serving orders'],
                ['key' => 'appearance', 'weight' => 10, 'description' => 'Professional appearance and hygiene'],
            ],
            'version' => 1,
            'is_active' => true,
        ]);

        $chefTemplate = StaffPerformanceTemplate::create([
            'staff_type_id' => $chefType->id,
            'template_name' => 'Chef Quarterly Review',
            'review_frequency' => 'quarterly',
            'rating_criteria' => [
                ['key' => 'food_quality', 'weight' => 35, 'description' => 'Quality and consistency of food preparation'],
                ['key' => 'food_safety', 'weight' => 25, 'description' => 'Adherence to food safety protocols'],
                ['key' => 'efficiency', 'weight' => 20, 'description' => 'Speed and efficiency in kitchen operations'],
                ['key' => 'teamwork', 'weight' => 15, 'description' => 'Collaboration with kitchen staff'],
                ['key' => 'creativity', 'weight' => 5, 'description' => 'Innovation and creativity in dishes'],
            ],
            'version' => 1,
            'is_active' => true,
        ]);

        // Create performance goals for waiter
        StaffPerformanceGoal::create([
            'staff_id' => $waiter->id,
            'goal_title' => 'Improve Customer Satisfaction Score',
            'goal_description' => 'Achieve and maintain a customer satisfaction rating of 4.5/5 or higher',
            'target_value' => 4.50,
            'current_value' => 4.20,
            'measurement_unit' => 'rating (1-5)',
            'goal_type' => 'individual',
            'priority' => 'high',
            'start_date' => now()->startOfQuarter(),
            'target_date' => now()->endOfQuarter(),
            'status' => 'active',
        ]);

        StaffPerformanceGoal::create([
            'staff_id' => $waiter->id,
            'goal_title' => 'Reduce Order Errors',
            'goal_description' => 'Reduce order errors to less than 2% of total orders taken',
            'target_value' => 2.00,
            'current_value' => 3.50,
            'measurement_unit' => '%',
            'goal_type' => 'individual',
            'priority' => 'medium',
            'start_date' => now()->startOfMonth(),
            'target_date' => now()->endOfMonth(),
            'status' => 'active',
        ]);

        // Create performance goals for chef
        StaffPerformanceGoal::create([
            'staff_id' => $chef->id,
            'goal_title' => 'Improve Kitchen Efficiency',
            'goal_description' => 'Reduce average order preparation time to under 12 minutes',
            'target_value' => 12.00,
            'current_value' => 14.50,
            'measurement_unit' => 'minutes',
            'goal_type' => 'individual',
            'priority' => 'high',
            'start_date' => now()->startOfQuarter(),
            'target_date' => now()->endOfQuarter(),
            'status' => 'active',
        ]);

        StaffPerformanceGoal::create([
            'staff_id' => $chef->id,
            'goal_title' => 'Zero Food Safety Violations',
            'goal_description' => 'Maintain perfect food safety record with zero violations',
            'target_value' => 0.00,
            'current_value' => 0.00,
            'measurement_unit' => 'violations',
            'goal_type' => 'individual',
            'priority' => 'urgent',
            'start_date' => now()->startOfYear(),
            'target_date' => now()->endOfYear(),
            'status' => 'completed',
        ]);

        // Create performance metrics for waiter (last 3 months)
        $waiterMetrics = [
            ['metric_name' => 'customer_satisfaction', 'values' => [4.1, 4.2, 4.2, 4.3, 4.2, 4.4, 4.3, 4.2, 4.5, 4.4, 4.3, 4.2]],
            ['metric_name' => 'orders_per_hour', 'values' => [12, 14, 13, 15, 16, 14, 15, 17, 16, 15, 16, 18]],
            ['metric_name' => 'order_accuracy', 'values' => [96.5, 97.2, 96.8, 97.5, 96.9, 97.8, 97.1, 96.7, 98.2, 97.6, 97.3, 97.9]],
        ];

        foreach ($waiterMetrics as $metric) {
            foreach ($metric['values'] as $index => $value) {
                StaffPerformanceMetric::create([
                    'staff_id' => $waiter->id,
                    'metric_name' => $metric['metric_name'],
                    'metric_value' => $value,
                    'measurement_period' => 'weekly',
                    'recorded_date' => now()->subWeeks(11 - $index),
                    'data_source' => 'manual',
                ]);
            }
        }

        // Create performance metrics for chef (last 3 months)
        $chefMetrics = [
            ['metric_name' => 'order_prep_time', 'values' => [15.2, 14.8, 14.5, 14.2, 14.0, 13.8, 13.5, 13.2, 14.1, 13.9, 14.2, 14.5]],
            ['metric_name' => 'food_waste_percentage', 'values' => [8.5, 7.8, 7.2, 6.9, 6.5, 6.2, 5.8, 5.5, 6.1, 5.9, 5.7, 5.4]],
            ['metric_name' => 'dishes_per_hour', 'values' => [25, 27, 28, 29, 30, 32, 33, 35, 34, 33, 35, 36]],
        ];

        foreach ($chefMetrics as $metric) {
            foreach ($metric['values'] as $index => $value) {
                StaffPerformanceMetric::create([
                    'staff_id' => $chef->id,
                    'metric_name' => $metric['metric_name'],
                    'metric_value' => $value,
                    'measurement_period' => 'weekly',
                    'recorded_date' => now()->subWeeks(11 - $index),
                    'data_source' => 'manual',
                ]);
            }
        }

        // Create performance reviews
        StaffPerformanceReview::create([
            'staff_id' => $waiter->id,
            'review_period_start' => now()->subMonths(3)->startOfMonth(),
            'review_period_end' => now()->subMonths(1)->endOfMonth(),
            'overall_rating' => 4.20,
            'punctuality_rating' => 4.50,
            'quality_rating' => 4.00,
            'teamwork_rating' => 4.30,
            'customer_service_rating' => 4.10,
            'strengths' => 'Excellent customer interaction skills, always punctual, great team player',
            'areas_for_improvement' => 'Could improve order accuracy, needs to learn new POS system features',
            'goals' => 'Achieve 4.5+ customer satisfaction rating, reduce order errors to <2%',
            'reviewer_id' => $chef->id, // Chef reviewing waiter
            'review_date' => now()->subWeeks(2),
            'status' => 'completed',
        ]);

        StaffPerformanceReview::create([
            'staff_id' => $chef->id,
            'review_period_start' => now()->subMonths(3)->startOfMonth(),
            'review_period_end' => now()->subMonths(1)->endOfMonth(),
            'overall_rating' => 4.60,
            'punctuality_rating' => 4.80,
            'quality_rating' => 4.70,
            'teamwork_rating' => 4.40,
            'customer_service_rating' => null, // Not applicable for chef
            'strengths' => 'Exceptional food quality, excellent food safety practices, efficient kitchen management',
            'areas_for_improvement' => 'Could mentor junior kitchen staff more, explore new cooking techniques',
            'goals' => 'Reduce prep time to <12 minutes, train 2 junior chefs, develop 3 new seasonal dishes',
            'reviewer_id' => $waiter->id, // For demo purposes
            'review_date' => now()->subWeeks(1),
            'status' => 'completed',
        ]);

        $this->command->info('✅ Created sample performance data:');
        $this->command->info('   • 2 Staff members (Sarah Johnson - Waiter, Mike Chen - Chef)');
        $this->command->info('   • 2 Performance templates (Waiter & Chef quarterly reviews)');
        $this->command->info('   • 4 Performance goals (2 per staff member)');
        $this->command->info('   • 72 Performance metrics (36 per staff member, 3 months data)');
        $this->command->info('   • 2 Performance reviews (1 per staff member)');
    }
}
