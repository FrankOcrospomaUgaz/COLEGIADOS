<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\UsesCurrentInstitution;
use Livewire\Component;

class InstitutionProfile extends Component
{
    use UsesCurrentInstitution;

    public $name = '';
    public $legal_name = '';
    public $tax_id = '';
    public $email = '';
    public $phone = '';
    public $website = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $country = '';
    public $primary_color = '';
    public $secondary_color = '';

    public function mount()
    {
        $institution = $this->currentInstitution();
        $this->fill($institution->only([
            'name', 'legal_name', 'tax_id', 'email', 'phone', 'website',
            'address', 'city', 'state', 'country', 'primary_color', 'secondary_color',
        ]));
    }

    public function saveInstitution()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'primary_color' => ['required', 'string', 'max:16'],
            'secondary_color' => ['required', 'string', 'max:16'],
        ]);

        $institution = $this->currentInstitution();
        $institution->update(array_merge($validated, ['updated_by' => auth()->id()]));

        session()->flash('status', 'Institución actualizada.');
    }

    public function render()
    {
        $institution = $this->currentInstitution()->load(['currentSubscription.plan', 'memberships.user']);
        $memberships = $institution->memberships->sortByDesc('is_primary');

        return view('livewire.institution-profile', compact('institution', 'memberships'));
    }
}
