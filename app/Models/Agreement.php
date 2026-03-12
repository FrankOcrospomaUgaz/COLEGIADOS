<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agreement extends TenantScopedModel
{
    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'renewed_at' => 'date',
    ];

    public function partnerOrganization(): BelongsTo
    {
        return $this->belongsTo(PartnerOrganization::class);
    }
}
