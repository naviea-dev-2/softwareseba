<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuperDepot extends Model
{
    protected $fillable = [
        'code',
        'name',
        'manager_id',
        'phone',
        'email',
        'address',
        'division',
        'district',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function depots(): HasMany
    {
        return $this->hasMany(Depot::class);
    }
}