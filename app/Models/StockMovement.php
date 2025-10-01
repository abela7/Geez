<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'type',
        'quantity',
        'unit_cost',
        'reference_number',
        'reason',
        'notes',
        'from_location',
        'to_location',
        'user_id',
        'movement_date',
        'stock_before',
        'stock_after',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'stock_before' => 'decimal:3',
        'stock_after' => 'decimal:3',
        'movement_date' => 'datetime',
    ];

    /**
     * Get the inventory item for this movement
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the user who created this movement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the total value of this movement
     */
    public function getTotalValueAttribute(): float
    {
        return abs($this->quantity) * ($this->unit_cost ?? 0);
    }

    /**
     * Check if this is an incoming movement
     */
    public function isIncoming(): bool
    {
        return in_array($this->type, ['received', 'returned', 'adjusted']) && $this->quantity > 0;
    }

    /**
     * Check if this is an outgoing movement
     */
    public function isOutgoing(): bool
    {
        return in_array($this->type, ['issued', 'wasted', 'expired', 'transferred']) || $this->quantity < 0;
    }

    /**
     * Scope for filtering by movement type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    /**
     * Scope for recent movements
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('movement_date', '>=', now()->subDays($days));
    }

    /**
     * Get formatted movement type for display
     */
    public function getFormattedTypeAttribute(): string
    {
        return match($this->type) {
            'received' => 'Stock Received',
            'issued' => 'Stock Issued',
            'adjusted' => 'Stock Adjusted',
            'transferred' => 'Stock Transferred',
            'wasted' => 'Stock Wasted',
            'returned' => 'Stock Returned',
            'expired' => 'Stock Expired',
            default => ucfirst($this->type),
        };
    }
}
