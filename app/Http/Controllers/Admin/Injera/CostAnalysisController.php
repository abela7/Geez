<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Injera;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CostAnalysisController extends Controller
{
    /**
     * Display the cost analysis dashboard.
     */
    public function index(Request $request): View
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $period = $request->get('period', 'monthly');

        // Get cost analysis data
        $costMetrics = $this->getCostMetrics($startDate, $endDate);
        $profitabilityData = $this->getProfitabilityData($startDate, $endDate);
        $costBreakdown = $this->getCostBreakdown($startDate, $endDate);
        $trendData = $this->getTrendData($startDate, $endDate, $period);
        $batchComparison = $this->getBatchComparison($startDate, $endDate);

        return view('admin.injera.cost-analysis.index', compact(
            'costMetrics',
            'profitabilityData',
            'costBreakdown',
            'trendData',
            'batchComparison',
            'startDate',
            'endDate',
            'period'
        ));
    }

    /**
     * Export cost analysis data.
     */
    public function export(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $format = $request->get('format', 'pdf');

        try {
            // Generate export data
            $exportData = $this->generateExportData($startDate, $endDate);

            return response()->json([
                'success' => true,
                'message' => 'Cost analysis exported successfully',
                'download_url' => '/admin/injera/cost-analysis/download/'.$exportData['filename'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export cost analysis: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get cost metrics summary.
     */
    private function getCostMetrics(string $startDate, string $endDate): array
    {
        // Mock data - replace with actual database queries later
        return [
            'total_production_cost' => 2847.50,
            'cost_per_injera' => 0.42,
            'total_revenue' => 4275.00,
            'gross_profit' => 1427.50,
            'profit_margin' => 33.4,
            'average_batch_cost' => 142.38,
            'cost_efficiency' => 87.2,
            'waste_percentage' => 3.8,
            'labor_cost_percentage' => 35.2,
            'material_cost_percentage' => 52.8,
            'overhead_cost_percentage' => 12.0,
        ];
    }

    /**
     * Get profitability analysis data.
     */
    private function getProfitabilityData(string $startDate, string $endDate): array
    {
        return [
            'by_quality' => [
                'A' => [
                    'revenue' => 2850.00,
                    'cost' => 1710.00,
                    'profit' => 1140.00,
                    'margin' => 40.0,
                    'volume' => 3800,
                ],
                'B' => [
                    'revenue' => 1140.00,
                    'cost' => 798.00,
                    'profit' => 342.00,
                    'margin' => 30.0,
                    'volume' => 1900,
                ],
                'C' => [
                    'revenue' => 285.00,
                    'cost' => 228.00,
                    'profit' => 57.00,
                    'margin' => 20.0,
                    'volume' => 570,
                ],
            ],
            'by_batch_size' => [
                'small' => [
                    'avg_cost' => 89.50,
                    'avg_revenue' => 127.50,
                    'avg_profit' => 38.00,
                    'margin' => 29.8,
                    'count' => 8,
                ],
                'medium' => [
                    'avg_cost' => 142.30,
                    'avg_revenue' => 201.50,
                    'avg_profit' => 59.20,
                    'margin' => 29.4,
                    'count' => 12,
                ],
                'large' => [
                    'avg_cost' => 198.75,
                    'avg_revenue' => 285.00,
                    'avg_profit' => 86.25,
                    'margin' => 30.3,
                    'count' => 6,
                ],
            ],
        ];
    }

    /**
     * Get detailed cost breakdown.
     */
    private function getCostBreakdown(string $startDate, string $endDate): array
    {
        return [
            'materials' => [
                'teff_flour' => [
                    'amount' => 945.60,
                    'percentage' => 33.2,
                    'per_injera' => 0.139,
                ],
                'wheat_flour' => [
                    'amount' => 378.40,
                    'percentage' => 13.3,
                    'per_injera' => 0.056,
                ],
                'barley_flour' => [
                    'amount' => 189.20,
                    'percentage' => 6.6,
                    'per_injera' => 0.028,
                ],
                'water_utilities' => [
                    'amount' => 56.80,
                    'percentage' => 2.0,
                    'per_injera' => 0.008,
                ],
            ],
            'labor' => [
                'baking_staff' => [
                    'amount' => 682.50,
                    'percentage' => 24.0,
                    'per_injera' => 0.101,
                ],
                'preparation_staff' => [
                    'amount' => 227.50,
                    'percentage' => 8.0,
                    'per_injera' => 0.034,
                ],
                'quality_control' => [
                    'amount' => 91.00,
                    'percentage' => 3.2,
                    'per_injera' => 0.013,
                ],
            ],
            'overhead' => [
                'equipment_depreciation' => [
                    'amount' => 170.85,
                    'percentage' => 6.0,
                    'per_injera' => 0.025,
                ],
                'facility_costs' => [
                    'amount' => 113.90,
                    'percentage' => 4.0,
                    'per_injera' => 0.017,
                ],
                'maintenance' => [
                    'amount' => 56.95,
                    'percentage' => 2.0,
                    'per_injera' => 0.008,
                ],
            ],
        ];
    }

    /**
     * Get trend data for charts.
     */
    private function getTrendData(string $startDate, string $endDate, string $period): array
    {
        // Generate mock trend data based on period
        $dates = [];
        $costs = [];
        $revenues = [];
        $profits = [];

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($period === 'daily') {
            $current = $start->copy();
            while ($current->lte($end)) {
                $dates[] = $current->format('M j');
                $costs[] = rand(120, 180);
                $revenues[] = rand(180, 270);
                $profits[] = end($revenues) - end($costs);
                $current->addDay();
            }
        } elseif ($period === 'weekly') {
            $current = $start->copy()->startOfWeek();
            while ($current->lte($end)) {
                $dates[] = 'Week '.$current->weekOfYear;
                $costs[] = rand(800, 1200);
                $revenues[] = rand(1200, 1800);
                $profits[] = end($revenues) - end($costs);
                $current->addWeek();
            }
        } else { // monthly
            $current = $start->copy()->startOfMonth();
            while ($current->lte($end)) {
                $dates[] = $current->format('M Y');
                $costs[] = rand(2400, 3600);
                $revenues[] = rand(3600, 5400);
                $profits[] = end($revenues) - end($costs);
                $current->addMonth();
            }
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Total Cost',
                    'data' => $costs,
                    'color' => '#ef4444',
                ],
                [
                    'label' => 'Revenue',
                    'data' => $revenues,
                    'color' => '#059669',
                ],
                [
                    'label' => 'Profit',
                    'data' => $profits,
                    'color' => '#2563eb',
                ],
            ],
        ];
    }

    /**
     * Get batch comparison data.
     */
    private function getBatchComparison(string $startDate, string $endDate): array
    {
        return [
            [
                'batch_id' => 'INJ-2025-001',
                'batch_name' => 'Weekend Production',
                'production_date' => '2025-01-15',
                'total_cost' => 142.50,
                'cost_per_injera' => 0.41,
                'revenue' => 195.00,
                'profit' => 52.50,
                'margin' => 26.9,
                'efficiency_score' => 88.5,
                'quality_grade' => 'A',
                'volume' => 350,
            ],
            [
                'batch_id' => 'INJ-2025-002',
                'batch_name' => 'Premium Teff Batch',
                'production_date' => '2025-01-14',
                'total_cost' => 198.75,
                'cost_per_injera' => 0.48,
                'revenue' => 285.00,
                'profit' => 86.25,
                'margin' => 30.3,
                'efficiency_score' => 92.1,
                'quality_grade' => 'A',
                'volume' => 410,
            ],
            [
                'batch_id' => 'INJ-2025-003',
                'batch_name' => 'Medium Daily Batch',
                'production_date' => '2025-01-16',
                'total_cost' => 127.80,
                'cost_per_injera' => 0.44,
                'revenue' => 171.00,
                'profit' => 43.20,
                'margin' => 25.3,
                'efficiency_score' => 85.2,
                'quality_grade' => 'B',
                'volume' => 290,
            ],
            [
                'batch_id' => 'INJ-2025-004',
                'batch_name' => 'Economy Batch',
                'production_date' => '2025-01-13',
                'total_cost' => 89.60,
                'cost_per_injera' => 0.38,
                'revenue' => 114.00,
                'profit' => 24.40,
                'margin' => 21.4,
                'efficiency_score' => 79.8,
                'quality_grade' => 'C',
                'volume' => 235,
            ],
        ];
    }

    /**
     * Generate export data.
     */
    private function generateExportData(string $startDate, string $endDate): array
    {
        // Mock implementation - replace with actual export generation
        $filename = 'cost-analysis-'.$startDate.'-to-'.$endDate.'.pdf';

        return [
            'filename' => $filename,
            'path' => storage_path('app/exports/'.$filename),
            'size' => '2.3MB',
        ];
    }
}
