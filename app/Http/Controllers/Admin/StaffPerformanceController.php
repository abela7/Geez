<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPerformanceGoal;
use App\Models\StaffPerformanceMetric;
use App\Models\StaffPerformanceReview;
use App\Models\StaffPerformanceTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StaffPerformanceController extends Controller
{
    public function index(Request $request): View
    {
        // Get filter parameters
        $period = $request->get('period', 'quarterly');
        $staffTypeId = $request->get('staff_type_id');
        
        // Calculate date ranges based on period
        $dateRange = $this->getDateRange($period);
        
        // Get overview statistics
        $overviewStats = $this->getOverviewStats($dateRange);
        
        // Get top performers
        $topPerformers = $this->getTopPerformers($dateRange, $staffTypeId);
        
        // Get performance trends data
        $performanceTrends = $this->getPerformanceTrends($dateRange);
        
        // Get upcoming reviews
        $upcomingReviews = $this->getUpcomingReviews();
        
        // Get key metrics
        $keyMetrics = $this->getKeyMetrics($dateRange);
        
        // Get staff types for filter
        $staffTypes = \App\Models\StaffType::active()->get();
        
        return view('admin.staff.performance', compact(
            'overviewStats',
            'topPerformers',
            'performanceTrends',
            'upcomingReviews',
            'keyMetrics',
            'staffTypes',
            'period'
        ));
    }
    
    /**
     * Get date range based on period.
     */
    private function getDateRange(string $period): array
    {
        return match ($period) {
            'monthly' => [
                'start' => now()->subMonth()->startOfMonth(),
                'end' => now()->endOfMonth(),
                'label' => 'Last Month'
            ],
            'quarterly' => [
                'start' => now()->subMonths(3)->startOfMonth(),
                'end' => now()->endOfMonth(),
                'label' => 'Last 3 Months'
            ],
            'yearly' => [
                'start' => now()->subYear()->startOfYear(),
                'end' => now()->endOfYear(),
                'label' => 'Last Year'
            ],
            default => [
                'start' => now()->subMonths(3)->startOfMonth(),
                'end' => now()->endOfMonth(),
                'label' => 'Last 3 Months'
            ]
        };
    }
    
    /**
     * Get overview statistics.
     */
    private function getOverviewStats(array $dateRange): array
    {
        // Get completed reviews in period
        $completedReviews = StaffPerformanceReview::whereBetween('review_date', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->get();
            
        // Calculate overall performance score
        $overallScore = $completedReviews->avg('overall_rating') * 20; // Convert 5-point scale to percentage
        
        // Get top performers (rating >= 4.0)
        $topPerformersCount = $completedReviews->where('overall_rating', '>=', 4.0)->count();
        
        // Get staff needing improvement (rating < 3.5)
        $needsImprovementCount = $completedReviews->where('overall_rating', '<', 3.5)->count();
        
        // Get reviews due this week
        $reviewsDue = StaffPerformanceReview::where('status', 'draft')
            ->orWhere(function ($query) {
                $query->whereNull('review_date')
                      ->where('review_period_end', '<=', now()->addWeek());
            })
            ->count();
            
        // Calculate improvement trend
        $previousPeriodStart = $dateRange['start']->copy()->subMonths(3);
        $previousPeriodEnd = $dateRange['start']->copy()->subDay();
        
        $previousReviews = StaffPerformanceReview::whereBetween('review_date', [$previousPeriodStart, $previousPeriodEnd])
            ->where('status', 'completed')
            ->get();
            
        $previousScore = $previousReviews->avg('overall_rating') * 20;
        $improvementTrend = $overallScore - $previousScore;
        
        return [
            'overall_score' => round($overallScore ?: 0, 1),
            'top_performers' => $topPerformersCount,
            'needs_improvement' => $needsImprovementCount,
            'reviews_due' => $reviewsDue,
            'improvement_trend' => round($improvementTrend, 1),
            'period_label' => $dateRange['label']
        ];
    }
    
    /**
     * Get top performers.
     */
    private function getTopPerformers(array $dateRange, ?string $staffTypeId = null): \Illuminate\Support\Collection
    {
        $query = Staff::with(['staffType', 'performanceReviews' => function ($q) use ($dateRange) {
            $q->whereBetween('review_date', [$dateRange['start'], $dateRange['end']])
              ->where('status', 'completed')
              ->latest('review_date');
        }]);
        
        if ($staffTypeId) {
            $query->where('staff_type_id', $staffTypeId);
        }
        
        return $query->get()
            ->filter(function ($staff) {
                return $staff->performanceReviews->isNotEmpty();
            })
            ->map(function ($staff) {
                $latestReview = $staff->performanceReviews->first();
                return [
                    'id' => $staff->id,
                    'name' => $staff->full_name,
                    'position' => $staff->staffType->display_name ?? 'Staff',
                    'score' => round(($latestReview->overall_rating ?? 0) * 20, 1),
                    'rating' => $latestReview->overall_rating ?? 0,
                ];
            })
            ->sortByDesc('rating')
            ->take(5)
            ->values();
    }
    
    /**
     * Get performance trends data for chart.
     */
    private function getPerformanceTrends(array $dateRange): array
    {
        $metrics = StaffPerformanceMetric::whereBetween('recorded_date', [$dateRange['start'], $dateRange['end']])
            ->where('measurement_period', 'weekly')
            ->orderBy('recorded_date')
            ->get()
            ->groupBy('recorded_date')
            ->map(function ($dayMetrics) {
                return [
                    'date' => $dayMetrics->first()->recorded_date->format('M d'),
                    'average_score' => round($dayMetrics->avg('metric_value'), 1)
                ];
            })
            ->values()
            ->toArray();
            
        return $metrics;
    }
    
    /**
     * Get upcoming reviews.
     */
    private function getUpcomingReviews(): \Illuminate\Support\Collection
    {
        // For demo purposes, we'll simulate upcoming reviews based on hire dates
        return Staff::with(['staffType'])
            ->where('status', 'active')
            ->get()
            ->map(function ($staff) {
                // Simulate review due dates based on hire date + quarterly reviews
                $nextReviewDate = $staff->hire_date->copy()->addMonths(3);
                while ($nextReviewDate->isPast()) {
                    $nextReviewDate->addMonths(3);
                }
                
                $daysUntilReview = now()->diffInDays($nextReviewDate, false);
                
                if ($daysUntilReview <= 14) { // Show reviews due within 2 weeks
                    return [
                        'staff_name' => $staff->full_name,
                        'position' => $staff->staffType->display_name ?? 'Staff',
                        'review_type' => 'Quarterly Review',
                        'due_date' => $nextReviewDate,
                        'days_until_due' => $daysUntilReview,
                        'urgency' => $daysUntilReview <= 1 ? 'urgent' : ($daysUntilReview <= 7 ? 'warning' : 'info')
                    ];
                }
                return null;
            })
            ->filter()
            ->sortBy('days_until_due')
            ->take(5)
            ->values();
    }
    
    /**
     * Get key metrics summary.
     */
    private function getKeyMetrics(array $dateRange): array
    {
        $metrics = StaffPerformanceMetric::whereBetween('recorded_date', [$dateRange['start'], $dateRange['end']])
            ->get()
            ->groupBy('metric_name');
            
        $keyMetrics = [];
        
        foreach ($metrics as $metricName => $metricData) {
            $average = $metricData->avg('metric_value');
            
            // Map metric names to display names and determine if higher is better
            $displayInfo = $this->getMetricDisplayInfo($metricName);
            
            $keyMetrics[] = [
                'name' => $displayInfo['display_name'],
                'value' => round($average, 1),
                'unit' => $displayInfo['unit'],
                'color' => $this->getMetricColor($average, $displayInfo['target'], $displayInfo['higher_is_better'])
            ];
        }
        
        return collect($keyMetrics)->take(4)->toArray();
    }
    
    /**
     * Get metric display information.
     */
    private function getMetricDisplayInfo(string $metricName): array
    {
        return match ($metricName) {
            'customer_satisfaction' => [
                'display_name' => 'Customer Satisfaction',
                'unit' => '/5',
                'target' => 4.0,
                'higher_is_better' => true
            ],
            'order_accuracy' => [
                'display_name' => 'Order Accuracy',
                'unit' => '%',
                'target' => 95.0,
                'higher_is_better' => true
            ],
            'orders_per_hour' => [
                'display_name' => 'Orders Per Hour',
                'unit' => '',
                'target' => 15.0,
                'higher_is_better' => true
            ],
            'order_prep_time' => [
                'display_name' => 'Prep Time',
                'unit' => 'min',
                'target' => 12.0,
                'higher_is_better' => false
            ],
            'food_waste_percentage' => [
                'display_name' => 'Food Waste',
                'unit' => '%',
                'target' => 5.0,
                'higher_is_better' => false
            ],
            'dishes_per_hour' => [
                'display_name' => 'Dishes Per Hour',
                'unit' => '',
                'target' => 30.0,
                'higher_is_better' => true
            ],
            default => [
                'display_name' => ucwords(str_replace('_', ' ', $metricName)),
                'unit' => '',
                'target' => 50.0,
                'higher_is_better' => true
            ]
        };
    }
    
    /**
     * Get color class based on metric performance.
     */
    private function getMetricColor(float $value, float $target, bool $higherIsBetter): string
    {
        $percentage = $higherIsBetter ? ($value / $target) : ($target / $value);
        
        if ($percentage >= 1.0) {
            return 'success'; // Green - meeting or exceeding target
        } elseif ($percentage >= 0.8) {
            return 'warning'; // Yellow - close to target
        } else {
            return 'error'; // Red - below target
        }
    }
}
