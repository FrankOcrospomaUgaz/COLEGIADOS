<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScientificAssociationMember extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function association(): BelongsTo
    {
        return $this->belongsTo(ScientificAssociation::class, 'scientific_association_id');
    }

    public function memberProfile(): BelongsTo
    {
        return $this->belongsTo(MemberProfile::class, 'member_profile_id');
    }
}
