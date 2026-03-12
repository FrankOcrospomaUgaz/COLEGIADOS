<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function memberships(): HasMany
    {
        return $this->hasMany(InstitutionMembership::class);
    }

    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'institution_memberships')
            ->withPivot(['role', 'status', 'is_primary', 'accepted_at', 'last_accessed_at'])
            ->withTimestamps();
    }

    public function currentInstitution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'current_institution_id');
    }

    public function activeMembership(): ?InstitutionMembership
    {
        return $this->memberships()
            ->where('institution_id', $this->current_institution_id)
            ->where('status', 'active')
            ->first();
    }

    public function switchInstitution(Institution $institution): void
    {
        $this->forceFill(['current_institution_id' => $institution->getKey()])->save();

        $this->memberships()
            ->where('institution_id', $institution->getKey())
            ->update(['last_accessed_at' => now()]);
    }
}
