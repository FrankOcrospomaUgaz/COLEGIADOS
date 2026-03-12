<?php

namespace App\Http\Livewire\Concerns;

use App\Models\Institution;
use Illuminate\Support\Facades\Auth;

trait UsesCurrentInstitution
{
    protected function currentInstitution(): Institution
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        if (! $user->currentInstitution) {
            $membership = $user->memberships()->where('status', 'active')->orderByDesc('is_primary')->first();

            if (! $membership) {
                abort(403, 'No existe una institución activa para este usuario.');
            }

            $user->switchInstitution($membership->institution);
            $user->refresh();
        }

        return $user->currentInstitution;
    }
}
