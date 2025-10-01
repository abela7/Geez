<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Injera;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductionBatchesController extends Controller
{
    /**
     * Display the production batches page.
     */
    public function index(Request $request): View
    {
        // Get production batches data
        $batches = $this->getProductionBatches($request);
        
        // Get available bucket configurations
        $bucketConfigurations = $this->getBucketConfigurations();
        
        // Get summary statistics
        $statistics = $this->getBatchStatistics();

        return view('admin.injera.production-batches.index', compact(
            'batches',
            'bucketConfigurations',
            'statistics'
        ));
    }

    /**
     * Create a new production batch.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bucket_configuration_id' => 'required|integer',
            'batch_name' => 'required|string|max:255',
            'planned_start_date' => 'required|date',
            'baker_assigned' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get bucket configuration details
            $bucketConfig = $this->getBucketConfigById($request->bucket_configuration_id);
            
            if (!$bucketConfig) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bucket configuration not found'
                ], 404);
            }

            // Create production batch
            $batchData = [
                'batch_number' => $this->generateBatchNumber(),
                'batch_name' => $request->batch_name,
                'bucket_configuration_id' => $request->bucket_configuration_id,
                'bucket_name' => $bucketConfig['name'],
                'bucket_capacity' => $bucketConfig['capacity'],
                'flour_recipe' => $bucketConfig['flour_recipe'],
                'expected_yield' => $bucketConfig['expected_yield'],
                'planned_start_date' => $request->planned_start_date,
                'baker_assigned' => $request->baker_assigned,
                'current_stage' => 'planning',
                'stage_1_buy_flour' => ['status' => 'pending', 'completed_at' => null],
                'stage_2_mixing' => ['status' => 'pending', 'completed_at' => null],
                'stage_3_fermentation' => ['status' => 'pending', 'completed_at' => null],
                'stage_4_hot_water' => ['status' => 'pending', 'completed_at' => null],
                'stage_5_baking' => ['status' => 'pending', 'completed_at' => null],
                'total_cost' => $bucketConfig['total_cost'],
                'cost_per_injera' => $bucketConfig['cost_per_injera'],
                'actual_yield' => null,
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // TODO: Replace with actual model creation
            // ProductionBatch::create($batchData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('injera.production_batches.batch_created_success')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create production batch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update batch stage.
     */
    public function updateStage(Request $request, int $batchId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'stage' => 'required|in:buy_flour,mixing,fermentation,hot_water,baking',
            'status' => 'required|in:in_progress,completed',
            'notes' => 'nullable|string|max:500',
            'actual_yield' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // TODO: Implement actual stage update logic
            return response()->json([
                'success' => true,
                'message' => __('injera.production_batches.stage_updated_success')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete production batch.
     */
    public function complete(Request $request, int $batchId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'actual_yield' => 'required|integer|min:1',
            'quality_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // TODO: Implement actual completion logic
            return response()->json([
                'success' => true,
                'message' => __('injera.production_batches.batch_completed_success')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete batch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel production batch.
     */
    public function cancel(Request $request, int $batchId): JsonResponse
    {
        try {
            // TODO: Implement actual cancellation logic
            return response()->json([
                'success' => true,
                'message' => __('injera.production_batches.batch_cancelled_success')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel batch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get production batches data (temporary mock data).
     */
    private function getProductionBatches(Request $request): array
    {
        // Mock data - replace with actual database queries later
        return [
            [
                'id' => 1,
                'batch_number' => 'INJ-2025-001',
                'batch_name' => 'Weekend Production',
                'bucket_name' => 'Large Production Bucket',
                'bucket_capacity' => 90.0,
                'expected_yield' => 130,
                'actual_yield' => null,
                'current_stage' => 'fermentation',
                'stage_progress' => 60,
                'baker_assigned' => 'Almaz Tadesse',
                'start_date' => '2025-01-15',
                'planned_completion' => '2025-01-19',
                'estimated_completion' => '2025-01-19',
                'stages' => [
                    'buy_flour' => ['status' => 'completed', 'completed_at' => '2025-01-15 08:00', 'notes' => 'Purchased 15kg Teff, 5kg Wheat'],
                    'mixing' => ['status' => 'completed', 'completed_at' => '2025-01-15 14:00', 'notes' => 'Mixed with 25L cold water'],
                    'fermentation' => ['status' => 'in_progress', 'started_at' => '2025-01-15 14:30', 'notes' => 'Day 2 of fermentation - good bubbling'],
                    'hot_water' => ['status' => 'pending', 'planned_at' => '2025-01-18 10:00'],
                    'baking' => ['status' => 'pending', 'planned_at' => '2025-01-19 08:00']
                ],
                'total_cost' => 48.75,
                'cost_per_injera' => 0.375,
                'notes' => 'High demand weekend batch',
                'status' => 'active',
                'priority' => 'high'
            ],
            [
                'id' => 2,
                'batch_number' => 'INJ-2025-002',
                'batch_name' => 'Premium Teff Batch',
                'bucket_name' => 'Premium Teff Only',
                'bucket_capacity' => 45.0,
                'expected_yield' => 70,
                'actual_yield' => 68,
                'current_stage' => 'completed',
                'stage_progress' => 100,
                'baker_assigned' => 'Meseret Alemu',
                'start_date' => '2025-01-10',
                'planned_completion' => '2025-01-14',
                'estimated_completion' => '2025-01-14',
                'completion_date' => '2025-01-14',
                'stages' => [
                    'buy_flour' => ['status' => 'completed', 'completed_at' => '2025-01-10 09:00'],
                    'mixing' => ['status' => 'completed', 'completed_at' => '2025-01-10 15:00'],
                    'fermentation' => ['status' => 'completed', 'completed_at' => '2025-01-13 16:00'],
                    'hot_water' => ['status' => 'completed', 'completed_at' => '2025-01-13 18:00'],
                    'baking' => ['status' => 'completed', 'completed_at' => '2025-01-14 10:00']
                ],
                'total_cost' => 31.10,
                'cost_per_injera' => 0.444,
                'actual_cost_per_injera' => 0.457,
                'notes' => 'Special order for premium customers',
                'status' => 'completed',
                'priority' => 'medium'
            ],
            [
                'id' => 3,
                'batch_number' => 'INJ-2025-003',
                'batch_name' => 'Medium Daily Batch',
                'bucket_name' => 'Medium Batch',
                'bucket_capacity' => 60.0,
                'expected_yield' => 85,
                'actual_yield' => null,
                'current_stage' => 'mixing',
                'stage_progress' => 20,
                'baker_assigned' => 'Tigist Bekele',
                'start_date' => '2025-01-16',
                'planned_completion' => '2025-01-20',
                'estimated_completion' => '2025-01-20',
                'stages' => [
                    'buy_flour' => ['status' => 'completed', 'completed_at' => '2025-01-16 07:30'],
                    'mixing' => ['status' => 'in_progress', 'started_at' => '2025-01-16 13:00'],
                    'fermentation' => ['status' => 'pending', 'planned_at' => '2025-01-16 16:00'],
                    'hot_water' => ['status' => 'pending', 'planned_at' => '2025-01-19 11:00'],
                    'baking' => ['status' => 'pending', 'planned_at' => '2025-01-20 09:00']
                ],
                'total_cost' => 32.30,
                'cost_per_injera' => 0.380,
                'notes' => 'Regular daily production',
                'status' => 'active',
                'priority' => 'normal'
            ]
        ];
    }

    /**
     * Get bucket configurations for dropdown.
     */
    private function getBucketConfigurations(): array
    {
        return [
            ['id' => 1, 'name' => 'Large Production Bucket', 'capacity' => 90, 'expected_yield' => 130],
            ['id' => 2, 'name' => 'Medium Batch', 'capacity' => 60, 'expected_yield' => 85],
            ['id' => 3, 'name' => 'Premium Teff Only', 'capacity' => 45, 'expected_yield' => 70],
        ];
    }

    /**
     * Get bucket configuration by ID.
     */
    private function getBucketConfigById(int $bucketId): ?array
    {
        $configs = [
            1 => [
                'name' => 'Large Production Bucket',
                'capacity' => 90,
                'flour_recipe' => [
                    ['flour_type' => 'Teff', 'quantity' => 15.0],
                    ['flour_type' => 'Wheat', 'quantity' => 5.0]
                ],
                'expected_yield' => 130,
                'total_cost' => 48.75,
                'cost_per_injera' => 0.375
            ],
            2 => [
                'name' => 'Medium Batch',
                'capacity' => 60,
                'flour_recipe' => [
                    ['flour_type' => 'Teff', 'quantity' => 10.0],
                    ['flour_type' => 'Wheat', 'quantity' => 3.0]
                ],
                'expected_yield' => 85,
                'total_cost' => 32.30,
                'cost_per_injera' => 0.380
            ]
        ];

        return $configs[$bucketId] ?? null;
    }

    /**
     * Generate unique batch number.
     */
    private function generateBatchNumber(): string
    {
        return 'INJ-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get batch statistics for summary cards.
     */
    private function getBatchStatistics(): array
    {
        // Mock statistics - replace with actual calculations
        return [
            'active_batches' => 2,
            'completed_this_week' => 5,
            'total_injera_produced' => 847,
            'avg_batch_time' => 4.2,
            'success_rate' => 96.8,
            'total_production_cost' => 245.75,
        ];
    }
}
