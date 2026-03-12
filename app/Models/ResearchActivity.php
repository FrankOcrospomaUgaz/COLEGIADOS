<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchActivity extends TenantScopedModel
{
    protected $casts = [
        'activity_year' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberProfile::class, 'member_profile_id');
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ResearchActivityItem::class);
    }
}
