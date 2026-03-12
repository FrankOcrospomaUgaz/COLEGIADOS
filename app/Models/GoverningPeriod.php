<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class GoverningPeriod extends TenantScopedModel
{
    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(GoverningPeriodMember::class);
    }
}
