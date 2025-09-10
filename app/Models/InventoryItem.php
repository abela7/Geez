<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'code',
        'barcode',
        'description',
        'category',
        'subcategory',
        'unit',
        'conversion_rates',
        'current_stock',
        'reserved_stock',
        'reorder_level',
        'max_level',
        'minimum_order_qty',
        'cost_per_unit',
        'selling_price',
        'location',
        'storage_requirements',
        'shelf_life_days',
        'supplier_id',
        'allergen_info',
        'status',
        'last_stock_update',
        'average_daily_usage',
    ];

    protected $casts = [
        'conversion_rates' => 'array',
        'allergen_info' => 'array',
        'current_stock' => 'decimal:3',
        'reserved_stock' => 'decimal:3',
        'reorder_level' => 'decimal:3',
        'max_level' => 'decimal:3',
        'minimum_order_qty' => 'decimal:3',
        'cost_per_unit' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'average_daily_usage' => 'decimal:3',
        'last_stock_update' => 'datetime',
    ];

    /**
     * Get the supplier for this inventory item
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get all stock movements for this item
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->orderBy('movement_date', 'desc');
    }

    /**
     * Get recent stock movements (last 10)
     */
    public function recentMovements(): HasMany
    {
        return $this->stockMovements()->limit(10);
    }

    /**
     * Get available stock (current - reserved)
     */
    public function getAvailableStockAttribute(): float
    {
        return $this->current_stock - $this->reserved_stock;
    }

    /**
     * Get stock status based on current levels
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0) {
            return 'out';
        }
        
        if ($this->current_stock <= $this->reorder_level) {
            return $this->current_stock <= ($this->reorder_level * 0.5) ? 'critical' : 'low';
        }
        
        if ($this->max_level && $this->current_stock >= $this->max_level) {
            return 'overstocked';
        }
        
        return 'ok';
    }

    /**
     * Get total inventory value
     */
    public function getTotalValueAttribute(): float
    {
        return $this->current_stock * $this->cost_per_unit;
    }

    /**
     * Calculate days remaining based on average usage
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->average_daily_usage || $this->average_daily_usage <= 0) {
            return null;
        }
        
        return (int) floor($this->available_stock / $this->average_daily_usage);
    }

    /**
     * Check if item needs reordering
     */
    public function needsReorder(): bool
    {
        return $this->current_stock <= $this->reorder_level;
    }

    /**
     * Scope for low stock items
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= reorder_level');
    }

    /**
     * Scope for out of stock items
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    /**
     * Scope for active items
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for filtering by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for filtering by location
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Update stock level and create movement record
     */
    public function updateStock(float $quantity, string $type, array $details = []): StockMovement
    {
        $stockBefore = $this->current_stock;
        $this->current_stock += $quantity;
        $this->last_stock_update = now();
        $this->save();

        return $this->stockMovements()->create([
            'type' => $type,
            'quantity' => $quantity,
            'unit_cost' => $details['unit_cost'] ?? $this->cost_per_unit,
            'reference_number' => $details['reference_number'] ?? null,
            'reason' => $details['reason'] ?? null,
            'notes' => $details['notes'] ?? null,
            'from_location' => $details['from_location'] ?? null,
            'to_location' => $details['to_location'] ?? null,
            'user_id' => $details['user_id'] ?? auth()->id(),
            'movement_date' => $details['movement_date'] ?? now(),
            'stock_before' => $stockBefore,
            'stock_after' => $this->current_stock,
        ]);
    }
}
