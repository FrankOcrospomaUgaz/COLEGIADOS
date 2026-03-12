<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conciliator extends TenantScopedModel
{
    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberProfile::class, 'member_profile_id');
    }
}
