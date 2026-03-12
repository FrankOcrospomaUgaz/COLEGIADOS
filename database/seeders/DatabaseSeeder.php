<?php

namespace Database\Seeders;

use App\Models\AcademicInstitution;
use App\Models\Agreement;
use App\Models\AuditorRecord;
use App\Models\Conciliator;
use App\Models\DisciplinaryProcess;
use App\Models\DoctorateRecord;
use App\Models\FamBenefit;
use App\Models\GoverningPeriod;
use App\Models\HonoraryDistinction;
use App\Models\IllegalPracticeReport;
use App\Models\Institution;
use App\Models\InstitutionMembership;
use App\Models\InstitutionSubscription;
use App\Models\MasterDegreeRecord;
use App\Models\MemberDeath;
use App\Models\MemberProfile;
use App\Models\PartnerOrganization;
use App\Models\ResearchActivity;
use App\Models\Retirement;
use App\Models\ScientificAssociation;
use App\Models\SecondSpecialtyRecord;
use App\Models\Sponsorship;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\WorkCenter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $plan = SubscriptionPlan::firstOrCreate(
            ['code' => 'starter'],
            [
                'name' => 'Starter',
                'description' => 'Plan inicial para instituciones regionales.',
                'monthly_price' => 0,
                'annual_price' => 0,
                'max_users' => 10,
                'features' => ['modules' => 17, 'multi_tenant' => true],
                'is_active' => true,
            ]
        );

        $user = User::withTrashed()->firstOrNew(['email' => 'admin@colegiados.test']);
        $user->forceFill([
            'name' => 'Administracion Regional',
            'password' => Hash::make('secret123'),
            'job_title' => 'Owner institucional',
            'phone' => '999888777',
            'location' => 'Lima',
            'about' => 'Cuenta administrativa de demostracion.',
            'deleted_at' => null,
        ])->save();

        $institution = Institution::firstOrCreate(
            ['slug' => 'consejo-regional-demo'],
            [
                'name' => 'Consejo Regional Demo',
                'legal_name' => 'Consejo Regional de Enfermeria Demo',
                'tax_id' => '20123456789',
                'email' => 'mesa.partes@demo.pe',
                'phone' => '014567890',
                'address' => 'Av. Institucional 245',
                'city' => 'Lima',
                'state' => 'Lima',
                'country' => 'Peru',
                'status' => 'active',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        InstitutionMembership::firstOrCreate(
            ['institution_id' => $institution->id, 'user_id' => $user->id],
            [
                'invited_by' => $user->id,
                'role' => 'owner',
                'status' => 'active',
                'is_primary' => true,
                'accepted_at' => now(),
                'last_accessed_at' => now(),
            ]
        );

        InstitutionSubscription::firstOrCreate(
            ['institution_id' => $institution->id, 'subscription_plan_id' => $plan->id],
            [
                'status' => 'active',
                'billing_cycle' => 'manual',
                'starts_at' => now()->subMonth(),
            ]
        );

        $user->update(['current_institution_id' => $institution->id, 'last_seen_at' => now()]);

        $unmsm = AcademicInstitution::firstOrCreate(
            ['institution_id' => $institution->id, 'name' => 'Universidad Nacional Mayor de San Marcos'],
            ['acronym' => 'UNMSM', 'created_by' => $user->id, 'updated_by' => $user->id]
        );

        $upch = AcademicInstitution::firstOrCreate(
            ['institution_id' => $institution->id, 'name' => 'Universidad Peruana Cayetano Heredia'],
            ['acronym' => 'UPCH', 'created_by' => $user->id, 'updated_by' => $user->id]
        );

        $hospital = WorkCenter::firstOrCreate(
            ['institution_id' => $institution->id, 'name' => 'Hospital Regional de Referencia'],
            ['city' => 'Lima', 'created_by' => $user->id, 'updated_by' => $user->id]
        );

        $saludPartner = PartnerOrganization::firstOrCreate(
            ['institution_id' => $institution->id, 'name' => 'Instituto de Gestion Sanitaria'],
            ['category' => 'general', 'created_by' => $user->id, 'updated_by' => $user->id]
        );

        $member = MemberProfile::firstOrCreate(
            ['institution_id' => $institution->id, 'college_number' => 'CEP-000124'],
            [
                'first_names' => 'Maria Elena',
                'last_names' => 'Torres Quispe',
                'date_of_birth' => '1985-05-12',
                'sex' => 'female',
                'cellphone' => '987654321',
                'email' => 'maria.torres@example.com',
                'base_university_id' => $unmsm->id,
                'licensure_research_title' => 'Factores asociados al autocuidado en pacientes cronicos.',
                'licensure_thesis_url' => 'https://example.com/tesis/licenciatura-maria-torres',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        MasterDegreeRecord::firstOrCreate(
            ['institution_id' => $institution->id, 'record_number' => 'MAE-2023-001'],
            [
                'member_profile_id' => $member->id,
                'work_center_id' => $hospital->id,
                'academic_institution_id' => $upch->id,
                'sunedu_code' => 'SUN-MAE-0001',
                'graduation_year' => 2023,
                'research_title' => 'Gestion del cuidado enfermero en entornos hospitalarios complejos.',
                'thesis_url' => 'https://example.com/tesis/maestria-maria-torres',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        DoctorateRecord::firstOrCreate(
            ['institution_id' => $institution->id, 'record_number' => 'DOC-2025-001'],
            [
                'member_profile_id' => $member->id,
                'work_center_id' => $hospital->id,
                'academic_institution_id' => $upch->id,
                'sunedu_code' => 'SUN-DOC-0001',
                'graduation_year' => 2025,
                'research_title' => 'Modelos predictivos para calidad del cuidado y continuidad terapeutica.',
                'thesis_url' => 'https://example.com/tesis/doctorado-maria-torres',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        SecondSpecialtyRecord::firstOrCreate(
            ['institution_id' => $institution->id, 'sunedu_code' => 'SUN-ESP-0001'],
            [
                'member_profile_id' => $member->id,
                'work_center_id' => $hospital->id,
                'specialty_name' => 'Cuidados Intensivos',
                'academic_institution_id' => $upch->id,
                'research_title' => 'Intervenciones enfermeras en monitoreo hemodinamico.',
                'thesis_url' => 'https://example.com/tesis/especialidad-maria-torres',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        AuditorRecord::firstOrCreate(
            ['institution_id' => $institution->id, 'record_number' => 'AUD-001'],
            [
                'member_profile_id' => $member->id,
                'work_center_id' => $hospital->id,
                'diploma_granter_id' => $saludPartner->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $association = ScientificAssociation::firstOrCreate(
            ['institution_id' => $institution->id, 'name' => 'Asociacion Cientifica de Cuidado Critico'],
            [
                'public_registry_certificate' => 'Partida registral 1288822',
                'objective' => 'Promover investigacion aplicada y actualizacion cientifica.',
                'tax_id' => '20555123456',
                'legal_address' => 'Jr. Las Ciencias 220',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $association->members()->firstOrCreate(
            ['member_name' => $member->full_name],
            ['member_profile_id' => $member->id, 'responsibility' => 'Presidenta', 'display_order' => 1]
        );

        Sponsorship::firstOrCreate(
            ['institution_id' => $institution->id, 'requester_name' => 'Sociedad Peruana de Enfermeria'],
            [
                'requester_organization_id' => $saludPartner->id,
                'credits_awarded' => 3,
                'resolution_number' => 'RES-2026-018',
                'starts_at' => now()->toDateString(),
                'ends_at' => now()->addDays(2)->toDateString(),
                'status' => 'approved',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        DisciplinaryProcess::firstOrCreate(
            ['institution_id' => $institution->id, 'reported_name' => $member->full_name],
            [
                'member_profile_id' => $member->id,
                'reason' => 'Incumplimiento de protocolo documentario.',
                'work_center_id' => $hospital->id,
                'resolution' => 'ETI-2026-004',
                'sanction' => 'Amonestacion escrita.',
                'status' => 'open',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        IllegalPracticeReport::firstOrCreate(
            ['institution_id' => $institution->id, 'reported_name' => 'Rosa Mendoza'],
            [
                'subject' => 'Uso indebido de insignias profesionales.',
                'result' => 'Caso derivado a asesoria legal.',
                'status' => 'investigating',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $period = GoverningPeriod::firstOrCreate(
            ['institution_id' => $institution->id, 'period_name' => '2025-2026'],
            [
                'starts_at' => '2025-01-01',
                'ends_at' => '2026-12-31',
                'resolution_number' => 'DIR-2025-001',
                'status' => 'active',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $period->members()->firstOrCreate(
            ['member_name' => $member->full_name],
            ['member_profile_id' => $member->id, 'position' => 'Decana regional', 'display_order' => 1]
        );

        HonoraryDistinction::firstOrCreate(
            ['institution_id' => $institution->id, 'member_profile_id' => $member->id],
            [
                'work_center_id' => $hospital->id,
                'reason' => 'Trayectoria institucional destacada.',
                'resolution' => 'HON-2026-002',
                'awarded_at' => now()->toDateString(),
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $death = MemberDeath::firstOrCreate(
            ['institution_id' => $institution->id, 'member_profile_id' => $member->id],
            [
                'work_center_id' => $hospital->id,
                'date_of_death' => '2025-11-20',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        FamBenefit::firstOrCreate(
            ['institution_id' => $institution->id, 'member_profile_id' => $member->id],
            [
                'member_death_id' => $death->id,
                'beneficiary_name' => 'Carlos Torres',
                'benefit_delivered_at' => '2025-12-15',
                'resolution' => 'FAM-2025-009',
                'amount' => 4500.00,
                'contribution_years' => 16,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        Conciliator::firstOrCreate(
            ['institution_id' => $institution->id, 'registration_number' => 'CON-9001'],
            [
                'member_profile_id' => $member->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        Retirement::firstOrCreate(
            ['institution_id' => $institution->id, 'member_profile_id' => $member->id],
            [
                'retirement_type' => 'jubilada',
                'cessation_date' => '2024-12-31',
                'work_center_id' => $hospital->id,
                'former_institution_name' => 'Hospital Regional de Referencia',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $activity = ResearchActivity::firstOrCreate(
            ['institution_id' => $institution->id, 'member_profile_id' => $member->id],
            [
                'work_center_id' => $hospital->id,
                'activity_year' => 2025,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );

        $activity->items()->firstOrCreate(
            ['title' => 'Estrategias de cuidado en pacientes criticos'],
            ['item_type' => 'article', 'display_order' => 1]
        );

        $activity->items()->firstOrCreate(
            ['title' => 'Capitulo de libro sobre seguridad del paciente'],
            ['item_type' => 'book_or_chapter', 'display_order' => 2]
        );

        Agreement::firstOrCreate(
            ['institution_id' => $institution->id, 'name' => 'Convenio de capacitacion continua'],
            [
                'partner_organization_id' => $saludPartner->id,
                'legal_representative_name' => 'Luis Carpio',
                'legal_representative_address' => 'Av. Los Proceres 440',
                'legal_representative_phone' => '988777666',
                'address' => 'Av. Los Proceres 440',
                'benefit' => 'Descuentos y becas en programas de formacion.',
                'starts_at' => '2026-01-15',
                'ends_at' => '2027-01-15',
                'duration_months' => 12,
                'renewed_at' => null,
                'status' => 'active',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]
        );
    }
}
