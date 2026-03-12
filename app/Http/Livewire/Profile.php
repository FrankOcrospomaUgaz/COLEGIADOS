<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\UsesCurrentInstitution;
use App\Models\Institution;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    use UsesCurrentInstitution;

    public $name = '';
    public $email = '';
    public $phone = '';
    public $job_title = '';
    public $location = '';
    public $about = '';
    public $current_institution_id;

    public function mount()
    {
        $user = auth()->user();
        $this->fill([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'job_title' => $user->job_title,
            'location' => $user->location,
            'about' => $user->about,
            'current_institution_id' => $user->current_institution_id,
        ]);
    }

    public function saveProfile()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
            'phone' => ['nullable', 'string', 'max:32'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'current_institution_id' => ['required', 'integer'],
        ]);

        $user = auth()->user();
        $user->update($validated);

        $institution = Institution::findOrFail($this->current_institution_id);

        abort_unless(
            $user->memberships()->where('institution_id', $institution->id)->where('status', 'active')->exists(),
            403
        );

        $user->switchInstitution($institution);

        session()->flash('status', 'Perfil actualizado.');
    }

    public function render()
    {   
        $user = auth()->user();
        $memberships = $user->memberships()->with('institution')->where('status', 'active')->get();

        return view('livewire.profile', [
            'memberships' => $memberships,
            'institution' => $this->currentInstitution(),
        ]);
    }
}
