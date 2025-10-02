<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_number',
        'status',
        'payment_terms',
        'notes',
    ];

    protected $casts = [
        'payment_terms' => 'array',
    ];

    /**
     * Get all inventory items for this supplier
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Get active inventory items for this supplier
     */
    public function activeItems(): HasMany
    {
        return $this->inventoryItems()->where('status', 'active');
    }

    /**
     * Scope for active suppliers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get supplier's full contact information
     */
    public function getFullContactAttribute(): string
    {
        $contact = $this->name;
        if ($this->contact_person) {
            $contact .= ' ('.$this->contact_person.')';
        }
        if ($this->phone) {
            $contact .= ' - '.$this->phone;
        }

        return $contact;
    }
}
