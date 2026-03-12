<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HonoraryDistinction extends TenantScopedModel
{
    protected $casts = [
        'awarded_at' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberProfile::class, 'member_profile_id');
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class);
    }
}
