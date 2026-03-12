<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorateRecord extends TenantScopedModel
{
    protected $casts = [
        'graduation_year' => 'integer',
    ];

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
