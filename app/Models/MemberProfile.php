<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberProfile extends TenantScopedModel
{
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim("{$this->first_names} {$this->last_names}")
        );
    }

    public function baseUniversity(): BelongsTo
    {
        return $this->belongsTo(AcademicInstitution::class, 'base_university_id');
    }

    public function masterDegrees(): HasMany
    {
        return $this->hasMany(MasterDegreeRecord::class);
    }

    public function doctorates(): HasMany
    {
        return $this->hasMany(DoctorateRecord::class);
    }

    public function secondSpecialties(): HasMany
    {
        return $this->hasMany(SecondSpecialtyRecord::class);
    }

    public function auditorRecords(): HasMany
    {
        return $this->hasMany(AuditorRecord::class);
    }
}
