<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'email' => 'required|email:rfc,dns',
        'password' => 'required',
    ];

    public function mount()
    {
        $this->fill(['email' => '', 'password' => '']);
    }

    public function login()
    {
        if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $user = User::where(['email' => $this->email])->first();

            if (! $user->current_institution_id) {
                $membership = $user->memberships()->where('status', 'active')->orderByDesc('is_primary')->first();
                if ($membership) {
                    $user->update(['current_institution_id' => $membership->institution_id]);
                }
            }

            $user->update(['last_seen_at' => now()]);
            auth()->login($user, $this->remember_me);
            return redirect()->intended(route('dashboard'));
        } else {
            return $this->addError('email', trans('auth.failed'));
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
