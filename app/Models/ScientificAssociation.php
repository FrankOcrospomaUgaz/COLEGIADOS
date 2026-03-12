<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ScientificAssociation extends TenantScopedModel
{
    public function members(): HasMany
    {
        return $this->hasMany(ScientificAssociationMember::class);
    }
}
