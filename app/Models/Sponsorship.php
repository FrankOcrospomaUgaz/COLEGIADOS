<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sponsorship extends TenantScopedModel
{
    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'credits_awarded' => 'decimal:2',
    ];

    public function requesterOrganization(): BelongsTo
    {
        return $this->belongsTo(PartnerOrganization::class, 'requester_organization_id');
    }
}
