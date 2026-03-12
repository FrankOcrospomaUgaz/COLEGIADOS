<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecondSpecialtyRecord extends TenantScopedModel
{
    public function member(): BelongsTo
    {
        return $this->belongsTo(MemberProfile::class, 'member_profile_id');
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class);
    }

    public function academicInstitution(): BelongsTo
    {
        return $this->belongsTo(AcademicInstitution::class);
    }
}
