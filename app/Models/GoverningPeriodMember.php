<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoverningPeriodMember extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function governingPeriod(): BelongsTo
    {
        return $this->belongsTo(GoverningPeriod::class);
    }

    public function memberProfile(): BelongsTo
    {
        return $this->belongsTo(MemberProfile::class, 'member_profile_id');
    }
}
