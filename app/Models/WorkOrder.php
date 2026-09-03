<?php
// app/Models/WorkOrder.php  (additions to your existing model)

namespace App\Models;

use App\Models\Inventory\Customer;
use App\Models\Inventory\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'work_order_type_id',
        'work_order_no',
        'work_order_type',
        'title',
        'description',
        'reference_type',
        'reference_id',
        'reference_no',
        'customer_id',
        'vendor_id',
        'assigned_to',
        'warehouse_id',
        'priority',
        'status',
        'requested_at',
        'scheduled_at',
        'started_at',
        'due_at',
        'completed_at',
        'cancelled_at',
        'progress',
        'estimated_cost',
        'actual_cost',
        'estimated_hours',
        'actual_hours',
        'instructions',
        'internal_notes',
        'completion_notes',
        'meta',
        'company_settings',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'requested_at'    => 'datetime',
        'scheduled_at'    => 'datetime',
        'started_at'      => 'datetime',
        'due_at'          => 'datetime',
        'completed_at'    => 'datetime',
        'cancelled_at'    => 'datetime',
        'progress'        => 'decimal:2',
        'estimated_cost'  => 'decimal:2',
        'actual_cost'     => 'decimal:2',
        'estimated_hours' => 'decimal:2',
        'actual_hours'    => 'decimal:2',
        'meta' => 'array',
        'company_settings' => 'array',
    ];

    // Constants
    public const STATUS_DRAFT       = 'draft';
    public const STATUS_PENDING     = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_ON_HOLD     = 'on_hold';
    public const STATUS_COMPLETED   = 'completed';
    public const STATUS_CANCELLED   = 'cancelled';
    public const STATUS_CLOSED      = 'closed';

    public const PRIORITY_LOW    = 'low';
    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH   = 'high';
    public const PRIORITY_URGENT = 'urgent';

    /**
     * Set the meta attribute - ensure it's always JSON
     */
    public function setMetaAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['meta'] = json_encode($value);
        } else {
            $this->attributes['meta'] = $value;
        }
    }

    /**
     * Set the company_settings attribute - ensure it's always JSON
     */
    public function setCompanySettingsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['company_settings'] = json_encode($value);
        } else {
            $this->attributes['company_settings'] = $value;
        }
    }

    /**
     * Get the meta attribute - always return array
     */
    public function getMetaAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        return json_decode($value, true) ?? [];
    }

    /**
     * Get the company_settings attribute - always return array
     */
    public function getCompanySettingsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        return json_decode($value, true) ?? [];
    }

    // Relationships
    public function workOrderType(): BelongsTo
    {
        return $this->belongsTo(WorkOrderType::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Stock\Warehouse::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeForBusiness($query, int $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('work_order_type', $type);
    }

    public function scopeStatus($query, string|array $status)
    {
        return $query->whereIn('status', (array) $status);
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_CLOSED]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_at', '<', now())
            ->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_CLOSED]);
    }

    // Accessors
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_at || in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_CLOSED])) {
            return false;
        }
        return $this->due_at->isPast();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT       => '<span class="badge bg-secondary">Draft</span>',
            self::STATUS_PENDING     => '<span class="badge bg-warning text-dark">Pending</span>',
            self::STATUS_IN_PROGRESS => '<span class="badge bg-primary">In Progress</span>',
            self::STATUS_ON_HOLD     => '<span class="badge bg-info">On Hold</span>',
            self::STATUS_COMPLETED   => '<span class="badge bg-success">Completed</span>',
            self::STATUS_CANCELLED   => '<span class="badge bg-dark">Cancelled</span>',
            self::STATUS_CLOSED      => '<span class="badge bg-light text-dark border">Closed</span>',
            default                  => '<span class="badge bg-light text-dark">' . ucfirst($this->status) . '</span>',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW    => '<span class="badge bg-success">Low</span>',
            self::PRIORITY_NORMAL => '<span class="badge bg-secondary">Normal</span>',
            self::PRIORITY_HIGH   => '<span class="badge bg-warning text-dark">High</span>',
            self::PRIORITY_URGENT => '<span class="badge bg-danger">Urgent</span>',
            default               => '<span class="badge bg-light text-dark">' . ucfirst($this->priority) . '</span>',
        };
    }

    // Helpers
    public function canStart(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_PENDING]);
    }

    public function canComplete(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function canCancel(): bool
    {
        return !in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_CLOSED]);
    }

    public function canReopen(): bool
    {
        return in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CLOSED, self::STATUS_CANCELLED]);
    }
}
