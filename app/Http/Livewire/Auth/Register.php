<?php

namespace App\Http\Livewire\Auth;

use App\Models\Institution;
use App\Models\InstitutionMembership;
use App\Models\InstitutionSubscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $institution_name = '';
    public $institution_slug = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|min:3',
        'institution_name' => 'required|min:4',
        'institution_slug' => 'nullable|alpha_dash|unique:institutions,slug',
        'email' => 'required|email:rfc,dns|unique:users',
        'password' => 'required|min:8|same:password_confirmation',
    ];

    public function register()
    {
        $this->validate();

        $slug = $this->resolveInstitutionSlug();

        $user = DB::transaction(function () use ($slug) {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $institution = Institution::create([
                'name' => $this->institution_name,
                'slug' => Str::lower($slug),
                'status' => 'trial',
                'country' => 'Perú',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'trial_ends_at' => now()->addDays(30),
            ]);

            $plan = SubscriptionPlan::firstOrCreate(
                ['code' => 'starter'],
                [
                    'name' => 'Starter',
                    'description' => 'Plan base para instituciones en implementación.',
                    'monthly_price' => 0,
                    'annual_price' => 0,
                    'max_users' => 10,
                    'max_records' => null,
                    'features' => ['modules' => 17, 'multi_tenant' => true],
                    'is_active' => true,
                ]
            );

            InstitutionSubscription::create([
                'institution_id' => $institution->id,
                'subscription_plan_id' => $plan->id,
                'status' => 'trial',
                'billing_cycle' => 'manual',
                'starts_at' => now(),
                'trial_ends_at' => now()->addDays(30),
            ]);

            InstitutionMembership::create([
                'institution_id' => $institution->id,
                'user_id' => $user->id,
                'invited_by' => $user->id,
                'role' => 'owner',
                'status' => 'active',
                'is_primary' => true,
                'accepted_at' => now(),
                'last_accessed_at' => now(),
            ]);

            $user->update([
                'current_institution_id' => $institution->id,
                'last_seen_at' => now(),
            ]);

            return $user;
        });

        auth()->login($user);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }

    private function resolveInstitutionSlug(): string
    {
        $base = Str::lower($this->institution_slug ?: Str::slug($this->institution_name));
        $slug = $base;
        $counter = 2;

        while (Institution::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
