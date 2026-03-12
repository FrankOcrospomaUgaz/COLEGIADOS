<?php

namespace Tests\Feature;

use App\Models\Institution;
use App\Models\InstitutionMembership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_root_redirects_to_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_register_page_is_available(): void
    {
        $this->get(route('register'))
            ->assertOk();
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        [$user] = $this->authenticatedContext();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Panel de control');
    }

    public function test_authenticated_user_can_access_registry_catalog(): void
    {
        [$user] = $this->authenticatedContext();

        $this->actingAs($user)
            ->get(route('registries.catalog'))
            ->assertOk()
            ->assertSee('Mapa de registros');
    }

    public function test_authenticated_user_can_access_member_profile_creation_form(): void
    {
        [$user] = $this->authenticatedContext();

        $this->actingAs($user)
            ->get(route('registries.create', 'member-profiles'))
            ->assertOk()
            ->assertSee('Nuevo colegiada');
    }

    private function authenticatedContext(): array
    {
        $user = User::factory()->create();
        $institution = Institution::create([
            'name' => 'Consejo Regional Test',
            'slug' => 'consejo-regional-test',
            'status' => 'active',
        ]);

        InstitutionMembership::create([
            'institution_id' => $institution->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'status' => 'active',
            'is_primary' => true,
            'accepted_at' => now(),
        ]);

        $user->update(['current_institution_id' => $institution->id]);

        return [$user, $institution];
    }
}
