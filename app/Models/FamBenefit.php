<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamBenefit extends TenantScopedModel
{
    protected $casts = [
        'benefit_delivered_at' => 'date',
        'amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberProfile::class, 'member_profile_id');
    }

    public function deathRecord(): BelongsTo
    {
        return $this->belongsTo(MemberDeath::class, 'member_death_id');
    }
}
