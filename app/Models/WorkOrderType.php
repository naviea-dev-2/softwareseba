<?php
// app/Models/WorkOrderType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrderType extends Model
{
    protected $fillable = [
        'business_id', 'name', 'slug', 'description',
        'config', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'config'    => 'array',
        'is_active' => 'boolean',
    ];

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function isVisible(string $field): bool
    {
        return $this->config['fields'][$field]['show'] ?? true;
    }

    public function isRequired(string $field): bool
    {
        return $this->config['fields'][$field]['required'] ?? false;
    }

    public function getFieldLabel(string $field): string
    {
        return $this->config['fields'][$field]['label'] ?? ucfirst(str_replace('_', ' ', $field));
    }

    public function getCustomSections(): array
    {
        return $this->config['sections'] ?? [];
    }

    public function lineItemsEnabled(): bool
    {
        return $this->config['line_items']['enabled'] ?? false;
    }

    public function lineItemLabel(): string
    {
        return $this->config['line_items']['label'] ?? 'Items';
    }

    public function lineItemTypes(): array
    {
        return $this->config['line_items']['types'] ?? ['product'];
    }
}