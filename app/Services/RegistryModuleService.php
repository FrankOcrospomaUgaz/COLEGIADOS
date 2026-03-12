<?php

namespace App\Services;

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
use App\Models\MemberDeath;
use App\Models\MemberProfile;
use App\Models\MasterDegreeRecord;
use App\Models\PartnerOrganization;
use App\Models\ResearchActivity;
use App\Models\Retirement;
use App\Models\ScientificAssociation;
use App\Models\SecondSpecialtyRecord;
use App\Models\Sponsorship;
use App\Models\TenantScopedModel;
use App\Models\User;
use App\Models\WorkCenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegistryModuleService
{
    public function modules(): array
    {
        return [
            'member-profiles' => [
                'slug' => 'member-profiles',
                'title' => 'Enfermeras colegiadas',
                'singular' => 'colegiada',
                'category' => 'Base profesional',
                'description' => 'Padrón maestro de enfermeras colegiadas y base del resto de registros.',
                'accent' => 'teal',
                'model' => MemberProfile::class,
                'with' => ['baseUniversity'],
                'columns' => [
                    ['label' => 'Colegiada', 'key' => 'full_name'],
                    ['label' => 'N. colegio', 'key' => 'college_number'],
                    ['label' => 'Correo', 'key' => 'email'],
                    ['label' => 'Universidad', 'key' => 'baseUniversity.name'],
                ],
            ],
            'master-degrees' => [
                'slug' => 'master-degrees',
                'title' => 'Grados de maestría',
                'singular' => 'maestría',
                'category' => 'Desarrollo académico',
                'description' => 'Seguimiento de grados de maestría con sustento académico y SUNEDU.',
                'accent' => 'blue',
                'model' => MasterDegreeRecord::class,
                'with' => ['member', 'workCenter', 'academicInstitution'],
                'columns' => [
                    ['label' => 'Enfermera', 'key' => 'member.full_name'],
                    ['label' => 'Registro', 'key' => 'record_number'],
                    ['label' => 'Universidad', 'key' => 'academicInstitution.name'],
                    ['label' => 'Año', 'key' => 'graduation_year'],
                ],
            ],
            'doctorates' => [
                'slug' => 'doctorates',
                'title' => 'Grados de doctorado',
                'singular' => 'doctorado',
                'category' => 'Desarrollo académico',
                'description' => 'Registro formal de doctorados y trazabilidad de investigación.',
                'accent' => 'sky',
                'model' => DoctorateRecord::class,
                'with' => ['member', 'workCenter', 'academicInstitution'],
                'columns' => [
                    ['label' => 'Enfermera', 'key' => 'member.full_name'],
                    ['label' => 'Registro', 'key' => 'record_number'],
                    ['label' => 'Universidad', 'key' => 'academicInstitution.name'],
                    ['label' => 'Año', 'key' => 'graduation_year'],
                ],
            ],
            'second-specialties' => [
                'slug' => 'second-specialties',
                'title' => 'Segundas especialidades',
                'singular' => 'segunda especialidad',
                'category' => 'Desarrollo académico',
                'description' => 'Especialidades con universidad, SUNEDU y sustento de investigación.',
                'accent' => 'cyan',
                'model' => SecondSpecialtyRecord::class,
                'with' => ['member', 'workCenter', 'academicInstitution'],
                'columns' => [
                    ['label' => 'Enfermera', 'key' => 'member.full_name'],
                    ['label' => 'Especialidad', 'key' => 'specialty_name'],
                    ['label' => 'Centro laboral', 'key' => 'workCenter.name'],
                    ['label' => 'SUNEDU', 'key' => 'sunedu_code'],
                ],
            ],
            'auditors' => [
                'slug' => 'auditors',
                'title' => 'Enfermeras auditoras',
                'singular' => 'auditoría',
                'category' => 'Desarrollo académico',
                'description' => 'Registro del diplomado de auditoría y trazabilidad del centro laboral.',
                'accent' => 'amber',
                'model' => AuditorRecord::class,
                'with' => ['member', 'workCenter', 'diplomaGranter'],
                'columns' => [
                    ['label' => 'Enfermera', 'key' => 'member.full_name'],
                    ['label' => 'Registro', 'key' => 'record_number'],
                    ['label' => 'Centro laboral', 'key' => 'workCenter.name'],
                    ['label' => 'Entidad', 'key' => 'diplomaGranter.name'],
                ],
            ],
            'scientific-associations' => [
                'slug' => 'scientific-associations',
                'title' => 'Asociaciones científicas',
                'singular' => 'asociación',
                'category' => 'Gobernanza institucional',
                'description' => 'Asociaciones registradas con integrantes, objetivo y sustento legal.',
                'accent' => 'emerald',
                'model' => ScientificAssociation::class,
                'with' => ['members.memberProfile'],
                'columns' => [
                    ['label' => 'Asociación', 'key' => 'name'],
                    ['label' => 'RUC', 'key' => 'tax_id'],
                    ['label' => 'Integrantes', 'key' => 'members_count'],
                    ['label' => 'Domicilio legal', 'key' => 'legal_address'],
                ],
            ],
            'sponsorships' => [
                'slug' => 'sponsorships',
                'title' => 'Auspicios',
                'singular' => 'auspicio',
                'category' => 'Gobernanza institucional',
                'description' => 'Control de auspicios, créditos y resoluciones por evento.',
                'accent' => 'orange',
                'model' => Sponsorship::class,
                'with' => ['requesterOrganization'],
                'columns' => [
                    ['label' => 'Solicitante', 'key' => 'requester_name'],
                    ['label' => 'Créditos', 'key' => 'credits_awarded'],
                    ['label' => 'Resolución', 'key' => 'resolution_number'],
                    ['label' => 'Estado', 'key' => 'status'],
                ],
            ],
            'disciplinary-processes' => [
                'slug' => 'disciplinary-processes',
                'title' => 'Procesos disciplinarios',
                'singular' => 'proceso disciplinario',
                'category' => 'Ética y control',
                'description' => 'Seguimiento a procesos y sanciones éticas con resoluciones.',
                'accent' => 'rose',
                'model' => DisciplinaryProcess::class,
                'with' => ['member', 'workCenter'],
                'columns' => [
                    ['label' => 'Persona', 'key' => 'reported_name'],
                    ['label' => 'Centro laboral', 'key' => 'workCenter.name'],
                    ['label' => 'Resolución', 'key' => 'resolution'],
                    ['label' => 'Estado', 'key' => 'status'],
                ],
            ],
            'illegal-practice-reports' => [
                'slug' => 'illegal-practice-reports',
                'title' => 'Denuncias por ejercicio ilegal',
                'singular' => 'denuncia',
                'category' => 'Ética y control',
                'description' => 'Registro de denuncias y resultado de seguimiento.',
                'accent' => 'red',
                'model' => IllegalPracticeReport::class,
                'with' => [],
                'columns' => [
                    ['label' => 'Denunciada', 'key' => 'reported_name'],
                    ['label' => 'Asunto', 'key' => 'subject'],
                    ['label' => 'Resultado', 'key' => 'result'],
                    ['label' => 'Estado', 'key' => 'status'],
                ],
            ],
            'governing-periods' => [
                'slug' => 'governing-periods',
                'title' => 'Consejo directivo regional',
                'singular' => 'período directivo',
                'category' => 'Gobernanza institucional',
                'description' => 'Períodos de junta directiva con integrantes y cargos.',
                'accent' => 'slate',
                'model' => GoverningPeriod::class,
                'with' => ['members.memberProfile'],
                'columns' => [
                    ['label' => 'Periodo', 'key' => 'period_name'],
                    ['label' => 'Inicio', 'key' => 'starts_at'],
                    ['label' => 'Fin', 'key' => 'ends_at'],
                    ['label' => 'Estado', 'key' => 'status'],
                ],
            ],
            'honorary-distinctions' => [
                'slug' => 'honorary-distinctions',
                'title' => 'Distinciones honoríficas',
                'singular' => 'distinción',
                'category' => 'Reconocimiento y bienestar',
                'description' => 'Distinciones con motivo, resolución y centro laboral.',
                'accent' => 'yellow',
                'model' => HonoraryDistinction::class,
                'with' => ['member', 'workCenter'],
                'columns' => [
                    ['label' => 'Enfermera', 'key' => 'member.full_name'],
                    ['label' => 'Motivo', 'key' => 'reason'],
                    ['label' => 'Resolución', 'key' => 'resolution'],
                    ['label' => 'Fecha', 'key' => 'awarded_at'],
                ],
            ],
            'member-deaths' => [
                'slug' => 'member-deaths',
                'title' => 'Defunciones',
                'singular' => 'defunción',
                'category' => 'Reconocimiento y bienestar',
                'description' => 'Registro regional de defunciones con trazabilidad del miembro.',
                'accent' => 'stone',
                'model' => MemberDeath::class,
                'with' => ['member', 'workCenter'],
                'columns' => [
                    ['label' => 'Miembro', 'key' => 'member.full_name'],
                    ['label' => 'N. colegio', 'key' => 'member.college_number'],
                    ['label' => 'Fecha de defunción', 'key' => 'date_of_death'],
                    ['label' => 'Centro laboral', 'key' => 'workCenter.name'],
                ],
            ],
            'fam-benefits' => [
                'slug' => 'fam-benefits',
                'title' => 'Beneficiarios FAM',
                'singular' => 'beneficio FAM',
                'category' => 'Reconocimiento y bienestar',
                'description' => 'Beneficios, beneficiarios y resoluciones vinculadas a fallecimiento.',
                'accent' => 'lime',
                'model' => FamBenefit::class,
                'with' => ['member', 'deathRecord.member'],
                'columns' => [
                    ['label' => 'Miembro', 'key' => 'member.full_name'],
                    ['label' => 'Beneficiario', 'key' => 'beneficiary_name'],
                    ['label' => 'Importe', 'key' => 'amount'],
                    ['label' => 'Entrega', 'key' => 'benefit_delivered_at'],
                ],
            ],
            'conciliators' => [
                'slug' => 'conciliators',
                'title' => 'Conciliadores extrajudiciales',
                'singular' => 'conciliadora',
                'category' => 'Reconocimiento y bienestar',
                'description' => 'Registro de conciliadoras con número oficial de registro.',
                'accent' => 'indigo',
                'model' => Conciliator::class,
                'with' => ['member'],
                'columns' => [
                    ['label' => 'Enfermera', 'key' => 'member.full_name'],
                    ['label' => 'Registro', 'key' => 'registration_number'],
                    ['label' => 'Correo', 'key' => 'member.email'],
                    ['label' => 'Celular', 'key' => 'member.cellphone'],
                ],
            ],
            'retirements' => [
                'slug' => 'retirements',
                'title' => 'Cesantes y jubiladas',
                'singular' => 'cese o jubilación',
                'category' => 'Reconocimiento y bienestar',
                'description' => 'Ceses y jubilaciones con fecha e institución de procedencia.',
                'accent' => 'violet',
                'model' => Retirement::class,
                'with' => ['member', 'workCenter'],
                'columns' => [
                    ['label' => 'Enfermera', 'key' => 'member.full_name'],
                    ['label' => 'Tipo', 'key' => 'retirement_type'],
                    ['label' => 'Fecha de cese', 'key' => 'cessation_date'],
                    ['label' => 'Institución', 'key' => 'former_institution_name'],
                ],
            ],
            'research-activities' => [
                'slug' => 'research-activities',
                'title' => 'Actividades investigativas',
                'singular' => 'actividad investigativa',
                'category' => 'Producción científica',
                'description' => 'Artículos, investigaciones y libros publicados por colegiadas.',
                'accent' => 'fuchsia',
                'model' => ResearchActivity::class,
                'with' => ['member', 'workCenter', 'items'],
                'columns' => [
                    ['label' => 'Enfermera', 'key' => 'member.full_name'],
                    ['label' => 'Centro laboral', 'key' => 'workCenter.name'],
                    ['label' => 'Año', 'key' => 'activity_year'],
                    ['label' => 'Producciones', 'key' => 'items_count'],
                ],
            ],
            'agreements' => [
                'slug' => 'agreements',
                'title' => 'Convenios',
                'singular' => 'convenio',
                'category' => 'Gobernanza institucional',
                'description' => 'Convenios con representante legal, vigencia y renovación.',
                'accent' => 'green',
                'model' => Agreement::class,
                'with' => ['partnerOrganization'],
                'columns' => [
                    ['label' => 'Convenio', 'key' => 'name'],
                    ['label' => 'Representante', 'key' => 'legal_representative_name'],
                    ['label' => 'Inicio', 'key' => 'starts_at'],
                    ['label' => 'Fin', 'key' => 'ends_at'],
                ],
            ],
        ];
    }

    public function categoryMeta(): array
    {
        return [
            'Base profesional' => [
                'title' => 'Padrón profesional',
                'description' => 'Registro base de colegiadas y datos troncales de identificación.',
                'icon' => 'fas fa-id-card',
            ],
            'Desarrollo académico' => [
                'title' => 'Desarrollo académico',
                'description' => 'Grados, especialidades y auditoría profesional.',
                'icon' => 'fas fa-graduation-cap',
            ],
            'Gobernanza institucional' => [
                'title' => 'Gobernanza institucional',
                'description' => 'Asociaciones, consejo directivo, auspicios y convenios.',
                'icon' => 'fas fa-sitemap',
            ],
            'Ética y control' => [
                'title' => 'Ética y control',
                'description' => 'Procesos disciplinarios y denuncias de ejercicio ilegal.',
                'icon' => 'fas fa-balance-scale',
            ],
            'Reconocimiento y bienestar' => [
                'title' => 'Reconocimiento y bienestar',
                'description' => 'Distinciones, beneficios, defunciones y situación previsional.',
                'icon' => 'fas fa-hand-holding-heart',
            ],
            'Producción científica' => [
                'title' => 'Producción científica',
                'description' => 'Seguimiento de artículos, investigaciones y libros.',
                'icon' => 'fas fa-flask',
            ],
        ];
    }

    public function groupedModules(): array
    {
        $modules = collect($this->modules());

        return collect($this->categoryMeta())
            ->mapWithKeys(fn (array $meta, string $category) => [
                $category => $modules
                    ->filter(fn (array $module) => $module['category'] === $category)
                    ->values()
                    ->all(),
            ])
            ->filter(fn (array $modules) => ! empty($modules))
            ->all();
    }

    public function module(string $slug): array
    {
        return $this->modules()[$slug] ?? abort(404);
    }

    public function query(string $slug, Institution $institution): Builder
    {
        $module = $this->module($slug);
        $query = $module['model']::query()
            ->where('institution_id', $institution->getKey())
            ->with($module['with'] ?? []);

        return match ($slug) {
            'scientific-associations' => $query->withCount('members'),
            'research-activities' => $query->withCount('items'),
            default => $query,
        };
    }

    public function applySearch(string $slug, Builder $query, ?string $search): Builder
    {
        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        return match ($slug) {
            'member-profiles' => $query->where(function (Builder $builder) use ($search) {
                $builder->where('first_names', 'like', "%{$search}%")
                    ->orWhere('last_names', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('college_number', 'like', "%{$search}%");
            }),
            'master-degrees', 'doctorates', 'second-specialties', 'auditors', 'honorary-distinctions', 'member-deaths', 'fam-benefits', 'conciliators', 'retirements', 'research-activities'
                => $query->whereHas('member', function (Builder $builder) use ($search) {
                    $builder->where('first_names', 'like', "%{$search}%")
                        ->orWhere('last_names', 'like', "%{$search}%")
                        ->orWhere('college_number', 'like', "%{$search}%");
                }),
            'scientific-associations' => $query->where('name', 'like', "%{$search}%")
                ->orWhere('tax_id', 'like', "%{$search}%"),
            'sponsorships' => $query->where('requester_name', 'like', "%{$search}%")
                ->orWhere('resolution_number', 'like', "%{$search}%"),
            'disciplinary-processes' => $query->where('reported_name', 'like', "%{$search}%")
                ->orWhere('reason', 'like', "%{$search}%"),
            'illegal-practice-reports' => $query->where('reported_name', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%"),
            'governing-periods' => $query->where('period_name', 'like', "%{$search}%")
                ->orWhere('resolution_number', 'like', "%{$search}%"),
            'agreements' => $query->where('name', 'like', "%{$search}%")
                ->orWhere('legal_representative_name', 'like', "%{$search}%"),
            default => $query,
        };
    }

    public function formSchema(string $slug): array
    {
        return match ($slug) {
            'member-profiles' => [
                [
                    'title' => 'Identificacion',
                    'fields' => [
                        ['name' => 'first_names', 'label' => 'Nombres', 'type' => 'text', 'required' => true],
                        ['name' => 'last_names', 'label' => 'Apellidos', 'type' => 'text', 'required' => true],
                        ['name' => 'date_of_birth', 'label' => 'Fecha de nacimiento', 'type' => 'date', 'required' => true],
                        ['name' => 'sex', 'label' => 'Sexo', 'type' => 'select', 'required' => true, 'options' => 'sexes'],
                    ],
                ],
                [
                    'title' => 'Contacto',
                    'fields' => [
                        ['name' => 'cellphone', 'label' => 'Celular', 'type' => 'text', 'required' => true],
                        ['name' => 'email', 'label' => 'Correo electrónico', 'type' => 'email', 'required' => true],
                        ['name' => 'college_number', 'label' => 'Número de colegio', 'type' => 'text', 'required' => true],
                    ],
                ],
                [
                    'title' => 'Formación de base',
                    'fields' => [
                        ['name' => 'base_university_id', 'label' => 'Universidad de egreso', 'type' => 'select', 'required' => true, 'options' => 'academic_institutions'],
                        ['name' => 'new_base_university_name', 'label' => 'Nueva universidad', 'type' => 'text', 'required' => false, 'full' => true],
                        ['name' => 'licensure_research_title', 'label' => 'Título de investigación', 'type' => 'textarea', 'required' => true, 'full' => true],
                        ['name' => 'licensure_thesis_url', 'label' => 'URL del informe de tesis', 'type' => 'url', 'required' => true, 'full' => true],
                    ],
                ],
            ],
            'master-degrees' => $this->memberLinkedSchema('maestría', [
                ['name' => 'record_number', 'label' => 'Número de registro', 'type' => 'text', 'required' => true],
                ['name' => 'work_center_id', 'label' => 'Centro laboral', 'type' => 'select', 'required' => true, 'options' => 'work_centers'],
                ['name' => 'new_work_center_name', 'label' => 'Nuevo centro laboral', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'academic_institution_id', 'label' => 'Universidad de maestría', 'type' => 'select', 'required' => true, 'options' => 'academic_institutions'],
                ['name' => 'new_academic_institution_name', 'label' => 'Nueva universidad', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'sunedu_code', 'label' => 'Código SUNEDU', 'type' => 'text', 'required' => true],
                ['name' => 'graduation_year', 'label' => 'Año de obtención', 'type' => 'number', 'required' => true],
                ['name' => 'research_title', 'label' => 'Título de investigación', 'type' => 'textarea', 'required' => true, 'full' => true],
                ['name' => 'thesis_url', 'label' => 'URL del informe de tesis', 'type' => 'url', 'required' => true, 'full' => true],
            ]),
            'doctorates' => $this->memberLinkedSchema('doctorado', [
                ['name' => 'record_number', 'label' => 'Número de registro', 'type' => 'text', 'required' => true],
                ['name' => 'work_center_id', 'label' => 'Centro laboral', 'type' => 'select', 'required' => true, 'options' => 'work_centers'],
                ['name' => 'new_work_center_name', 'label' => 'Nuevo centro laboral', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'academic_institution_id', 'label' => 'Universidad del doctorado', 'type' => 'select', 'required' => true, 'options' => 'academic_institutions'],
                ['name' => 'new_academic_institution_name', 'label' => 'Nueva universidad', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'sunedu_code', 'label' => 'Código SUNEDU', 'type' => 'text', 'required' => true],
                ['name' => 'graduation_year', 'label' => 'Año de obtención', 'type' => 'number', 'required' => true],
                ['name' => 'research_title', 'label' => 'Título de investigación', 'type' => 'textarea', 'required' => true, 'full' => true],
                ['name' => 'thesis_url', 'label' => 'URL del informe de tesis', 'type' => 'url', 'required' => true, 'full' => true],
            ]),
            'second-specialties' => $this->memberLinkedSchema('segunda especialidad', [
                ['name' => 'work_center_id', 'label' => 'Centro laboral', 'type' => 'select', 'required' => true, 'options' => 'work_centers'],
                ['name' => 'new_work_center_name', 'label' => 'Nuevo centro laboral', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'specialty_name', 'label' => 'Nombre de la especialidad', 'type' => 'text', 'required' => true],
                ['name' => 'sunedu_code', 'label' => 'Código SUNEDU', 'type' => 'text', 'required' => true],
                ['name' => 'academic_institution_id', 'label' => 'Universidad de egreso', 'type' => 'select', 'required' => true, 'options' => 'academic_institutions'],
                ['name' => 'new_academic_institution_name', 'label' => 'Nueva universidad', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'research_title', 'label' => 'Título de investigación', 'type' => 'textarea', 'required' => true, 'full' => true],
                ['name' => 'thesis_url', 'label' => 'URL del informe de tesis', 'type' => 'url', 'required' => true, 'full' => true],
            ]),
            'auditors' => $this->memberLinkedSchema('auditoría', [
                ['name' => 'work_center_id', 'label' => 'Centro laboral', 'type' => 'select', 'required' => true, 'options' => 'work_centers'],
                ['name' => 'new_work_center_name', 'label' => 'Nuevo centro laboral', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'diploma_granter_id', 'label' => 'Entidad que otorgó el diplomado', 'type' => 'select', 'required' => true, 'options' => 'partner_organizations'],
                ['name' => 'new_diploma_granter_name', 'label' => 'Nueva entidad', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'record_number', 'label' => 'Número de registro', 'type' => 'text', 'required' => true],
            ]),
            'scientific-associations' => [
                [
                    'title' => 'Datos generales',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Nombre de la asociación', 'type' => 'text', 'required' => true, 'full' => true],
                        ['name' => 'public_registry_certificate', 'label' => 'Constancia de registros públicos', 'type' => 'textarea', 'required' => true, 'full' => true],
                        ['name' => 'objective', 'label' => 'Objetivo', 'type' => 'textarea', 'required' => true, 'full' => true],
                        ['name' => 'tax_id', 'label' => 'Número de RUC', 'type' => 'text', 'required' => true],
                        ['name' => 'legal_address', 'label' => 'Domicilio legal', 'type' => 'text', 'required' => true],
                    ],
                ],
                [
                    'title' => 'Integrantes',
                    'fields' => [
                        [
                            'name' => 'members',
                            'label' => 'Integrantes',
                            'type' => 'repeater',
                            'add_label' => 'Agregar integrante',
                            'fields' => [
                                ['name' => 'member_profile_id', 'label' => 'Colegiada vinculada', 'type' => 'select', 'required' => false, 'options' => 'members'],
                                ['name' => 'member_name', 'label' => 'Nombre y apellidos', 'type' => 'text', 'required' => true],
                                ['name' => 'responsibility', 'label' => 'Rol', 'type' => 'text', 'required' => false],
                            ],
                        ],
                    ],
                ],
            ],
            'sponsorships' => [
                [
                    'title' => 'Auspicio',
                    'fields' => [
                        ['name' => 'requester_organization_id', 'label' => 'Institución solicitante', 'type' => 'select', 'required' => false, 'options' => 'partner_organizations'],
                        ['name' => 'requester_name', 'label' => 'Nombre de la institución', 'type' => 'text', 'required' => true],
                        ['name' => 'new_requester_organization_name', 'label' => 'Nueva institución', 'type' => 'text', 'required' => false, 'full' => true],
                        ['name' => 'credits_awarded', 'label' => 'Créditos otorgados', 'type' => 'number', 'required' => true],
                        ['name' => 'resolution_number', 'label' => 'Número de resolución', 'type' => 'text', 'required' => true],
                        ['name' => 'starts_at', 'label' => 'Fecha de inicio', 'type' => 'date', 'required' => true],
                        ['name' => 'ends_at', 'label' => 'Fecha de término', 'type' => 'date', 'required' => true],
                        ['name' => 'status', 'label' => 'Estado', 'type' => 'select', 'required' => true, 'options' => 'sponsorship_statuses'],
                    ],
                ],
            ],
            'disciplinary-processes' => [
                [
                    'title' => 'Proceso',
                    'fields' => [
                        ['name' => 'member_profile_id', 'label' => 'Colegiada vinculada', 'type' => 'select', 'required' => false, 'options' => 'members'],
                        ['name' => 'reported_name', 'label' => 'Nombres y apellidos', 'type' => 'text', 'required' => true],
                        ['name' => 'reason', 'label' => 'Motivo del proceso', 'type' => 'textarea', 'required' => true, 'full' => true],
                        ['name' => 'work_center_id', 'label' => 'Centro laboral', 'type' => 'select', 'required' => false, 'options' => 'work_centers'],
                        ['name' => 'new_work_center_name', 'label' => 'Nuevo centro laboral', 'type' => 'text', 'required' => false, 'full' => true],
                        ['name' => 'resolution', 'label' => 'Resolución', 'type' => 'text', 'required' => true],
                        ['name' => 'sanction', 'label' => 'Sanción', 'type' => 'textarea', 'required' => true, 'full' => true],
                        ['name' => 'status', 'label' => 'Estado', 'type' => 'select', 'required' => true, 'options' => 'disciplinary_statuses'],
                    ],
                ],
            ],
            'illegal-practice-reports' => [
                [
                    'title' => 'Denuncia',
                    'fields' => [
                        ['name' => 'reported_name', 'label' => 'Nombres y apellidos', 'type' => 'text', 'required' => true],
                        ['name' => 'subject', 'label' => 'Asunto', 'type' => 'textarea', 'required' => true, 'full' => true],
                        ['name' => 'result', 'label' => 'Resultado', 'type' => 'textarea', 'required' => true, 'full' => true],
                        ['name' => 'status', 'label' => 'Estado', 'type' => 'select', 'required' => true, 'options' => 'report_statuses'],
                    ],
                ],
            ],
            'governing-periods' => [
                [
                    'title' => 'Periodo',
                    'fields' => [
                        ['name' => 'period_name', 'label' => 'Periodo de la junta directiva', 'type' => 'text', 'required' => true],
                        ['name' => 'starts_at', 'label' => 'Inicio', 'type' => 'date', 'required' => false],
                        ['name' => 'ends_at', 'label' => 'Fin', 'type' => 'date', 'required' => false],
                        ['name' => 'resolution_number', 'label' => 'Resolución', 'type' => 'text', 'required' => false],
                        ['name' => 'status', 'label' => 'Estado', 'type' => 'select', 'required' => true, 'options' => 'period_statuses'],
                    ],
                ],
                [
                    'title' => 'Miembros de junta',
                    'fields' => [
                        [
                            'name' => 'members',
                            'label' => 'Miembros',
                            'type' => 'repeater',
                            'add_label' => 'Agregar miembro',
                            'fields' => [
                                ['name' => 'member_profile_id', 'label' => 'Colegiada vinculada', 'type' => 'select', 'required' => false, 'options' => 'members'],
                                ['name' => 'member_name', 'label' => 'Nombre y apellidos', 'type' => 'text', 'required' => true],
                                ['name' => 'position', 'label' => 'Cargo', 'type' => 'text', 'required' => false],
                            ],
                        ],
                    ],
                ],
            ],
            'honorary-distinctions' => $this->memberLinkedSchema('distinción', [
                ['name' => 'work_center_id', 'label' => 'Centro laboral', 'type' => 'select', 'required' => true, 'options' => 'work_centers'],
                ['name' => 'new_work_center_name', 'label' => 'Nuevo centro laboral', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'reason', 'label' => 'Motivo de distinción', 'type' => 'textarea', 'required' => true, 'full' => true],
                ['name' => 'resolution', 'label' => 'Resolución', 'type' => 'text', 'required' => true],
                ['name' => 'awarded_at', 'label' => 'Fecha de distinción', 'type' => 'date', 'required' => false],
            ]),
            'member-deaths' => $this->memberLinkedSchema('defunción', [
                ['name' => 'work_center_id', 'label' => 'Centro laboral', 'type' => 'select', 'required' => true, 'options' => 'work_centers'],
                ['name' => 'new_work_center_name', 'label' => 'Nuevo centro laboral', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'date_of_death', 'label' => 'Fecha de defunción', 'type' => 'date', 'required' => true],
            ]),
            'fam-benefits' => $this->memberLinkedSchema('beneficio FAM', [
                ['name' => 'member_death_id', 'label' => 'Registro de defunción', 'type' => 'select', 'required' => false, 'options' => 'death_records'],
                ['name' => 'beneficiary_name', 'label' => 'Nombre del beneficiario', 'type' => 'text', 'required' => true],
                ['name' => 'benefit_delivered_at', 'label' => 'Fecha de entrega', 'type' => 'date', 'required' => true],
                ['name' => 'resolution', 'label' => 'Resolución', 'type' => 'text', 'required' => true],
                ['name' => 'amount', 'label' => 'Importe', 'type' => 'number', 'required' => true],
                ['name' => 'contribution_years', 'label' => 'Años de aportación', 'type' => 'number', 'required' => true],
            ]),
            'conciliators' => $this->memberLinkedSchema('conciliadora', [
                ['name' => 'registration_number', 'label' => 'N. de registro de conciliadores', 'type' => 'text', 'required' => true],
            ]),
            'retirements' => $this->memberLinkedSchema('cese o jubilación', [
                ['name' => 'retirement_type', 'label' => 'Tipo', 'type' => 'select', 'required' => true, 'options' => 'retirement_types'],
                ['name' => 'cessation_date', 'label' => 'Fecha de cese', 'type' => 'date', 'required' => true],
                ['name' => 'work_center_id', 'label' => 'Institución donde laboró', 'type' => 'select', 'required' => false, 'options' => 'work_centers'],
                ['name' => 'former_institution_name', 'label' => 'Institución donde laboró', 'type' => 'text', 'required' => true, 'full' => true],
            ]),
            'research-activities' => $this->memberLinkedSchema('actividad investigativa', [
                ['name' => 'work_center_id', 'label' => 'Centro laboral', 'type' => 'select', 'required' => true, 'options' => 'work_centers'],
                ['name' => 'new_work_center_name', 'label' => 'Nuevo centro laboral', 'type' => 'text', 'required' => false, 'full' => true],
                ['name' => 'activity_year', 'label' => 'Año de referencia', 'type' => 'number', 'required' => false],
                [
                    'name' => 'items',
                    'label' => 'Producción científica',
                    'type' => 'repeater',
                    'add_label' => 'Agregar producción',
                    'fields' => [
                        ['name' => 'item_type', 'label' => 'Tipo', 'type' => 'select', 'required' => true, 'options' => 'research_item_types'],
                        ['name' => 'title', 'label' => 'Título', 'type' => 'text', 'required' => true],
                        ['name' => 'published_at', 'label' => 'Fecha', 'type' => 'date', 'required' => false],
                        ['name' => 'url', 'label' => 'URL', 'type' => 'url', 'required' => false],
                    ],
                ],
            ]),
            'agreements' => [
                [
                    'title' => 'Convenio',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Nombre del convenio', 'type' => 'text', 'required' => true, 'full' => true],
                        ['name' => 'partner_organization_id', 'label' => 'Contraparte', 'type' => 'select', 'required' => false, 'options' => 'partner_organizations'],
                        ['name' => 'new_partner_organization_name', 'label' => 'Nueva contraparte', 'type' => 'text', 'required' => false, 'full' => true],
                        ['name' => 'legal_representative_name', 'label' => 'Representante legal', 'type' => 'text', 'required' => true],
                        ['name' => 'legal_representative_address', 'label' => 'Dirección del representante legal', 'type' => 'text', 'required' => true],
                        ['name' => 'legal_representative_phone', 'label' => 'Celular del representante legal', 'type' => 'text', 'required' => true],
                        ['name' => 'address', 'label' => 'Dirección', 'type' => 'text', 'required' => true],
                        ['name' => 'benefit', 'label' => 'Beneficio', 'type' => 'textarea', 'required' => true, 'full' => true],
                        ['name' => 'starts_at', 'label' => 'Fecha de inicio', 'type' => 'date', 'required' => true],
                        ['name' => 'ends_at', 'label' => 'Fecha de término', 'type' => 'date', 'required' => true],
                        ['name' => 'duration_months', 'label' => 'Duración en meses', 'type' => 'number', 'required' => true],
                        ['name' => 'renewed_at', 'label' => 'Fecha de renovación', 'type' => 'date', 'required' => false],
                        ['name' => 'status', 'label' => 'Estado', 'type' => 'select', 'required' => true, 'options' => 'agreement_statuses'],
                    ],
                ],
            ],
            default => [],
        };
    }

    public function formState(string $slug, ?Model $record = null): array
    {
        if (! $record) {
            return match ($slug) {
                'scientific-associations' => ['members' => [$this->blankRow('members')]],
                'governing-periods' => ['status' => 'active', 'members' => [$this->blankRow('members')]],
                'research-activities' => ['items' => [$this->blankRow('items')]],
                'sponsorships' => ['status' => 'approved'],
                'disciplinary-processes' => ['status' => 'open'],
                'illegal-practice-reports' => ['status' => 'reported'],
                'retirements' => ['retirement_type' => 'jubilada'],
                'agreements' => ['status' => 'active'],
                default => [],
            };
        }

        return match ($slug) {
            'member-profiles' => Arr::only($record->toArray(), [
                'first_names', 'last_names', 'date_of_birth', 'sex', 'cellphone', 'email', 'college_number',
                'base_university_id', 'licensure_research_title', 'licensure_thesis_url',
            ]),
            'master-degrees', 'doctorates' => Arr::only($record->toArray(), [
                'member_profile_id', 'record_number', 'work_center_id', 'academic_institution_id', 'sunedu_code',
                'graduation_year', 'research_title', 'thesis_url',
            ]),
            'second-specialties' => Arr::only($record->toArray(), [
                'member_profile_id', 'work_center_id', 'specialty_name', 'sunedu_code',
                'academic_institution_id', 'research_title', 'thesis_url',
            ]),
            'auditors' => Arr::only($record->toArray(), [
                'member_profile_id', 'work_center_id', 'diploma_granter_id', 'record_number',
            ]),
            'scientific-associations' => [
                'name' => $record->name,
                'public_registry_certificate' => $record->public_registry_certificate,
                'objective' => $record->objective,
                'tax_id' => $record->tax_id,
                'legal_address' => $record->legal_address,
                'members' => $record->members->map(fn ($item) => [
                    'member_profile_id' => $item->member_profile_id,
                    'member_name' => $item->member_name,
                    'responsibility' => $item->responsibility,
                ])->all(),
            ],
            'sponsorships' => Arr::only($record->toArray(), [
                'requester_organization_id', 'requester_name', 'credits_awarded', 'resolution_number',
                'starts_at', 'ends_at', 'status',
            ]),
            'disciplinary-processes' => Arr::only($record->toArray(), [
                'member_profile_id', 'reported_name', 'reason', 'work_center_id', 'resolution', 'sanction', 'status',
            ]),
            'illegal-practice-reports' => Arr::only($record->toArray(), [
                'reported_name', 'subject', 'result', 'status',
            ]),
            'governing-periods' => [
                'period_name' => $record->period_name,
                'starts_at' => optional($record->starts_at)->format('Y-m-d'),
                'ends_at' => optional($record->ends_at)->format('Y-m-d'),
                'resolution_number' => $record->resolution_number,
                'status' => $record->status,
                'members' => $record->members->map(fn ($item) => [
                    'member_profile_id' => $item->member_profile_id,
                    'member_name' => $item->member_name,
                    'position' => $item->position,
                ])->all(),
            ],
            'honorary-distinctions' => Arr::only($record->toArray(), [
                'member_profile_id', 'work_center_id', 'reason', 'resolution', 'awarded_at',
            ]),
            'member-deaths' => Arr::only($record->toArray(), [
                'member_profile_id', 'work_center_id', 'date_of_death',
            ]),
            'fam-benefits' => Arr::only($record->toArray(), [
                'member_profile_id', 'member_death_id', 'beneficiary_name', 'benefit_delivered_at',
                'resolution', 'amount', 'contribution_years',
            ]),
            'conciliators' => Arr::only($record->toArray(), ['member_profile_id', 'registration_number']),
            'retirements' => Arr::only($record->toArray(), [
                'member_profile_id', 'retirement_type', 'cessation_date', 'work_center_id', 'former_institution_name',
            ]),
            'research-activities' => [
                'member_profile_id' => $record->member_profile_id,
                'work_center_id' => $record->work_center_id,
                'activity_year' => $record->activity_year,
                'items' => $record->items->map(fn ($item) => [
                    'item_type' => $item->item_type,
                    'title' => $item->title,
                    'published_at' => optional($item->published_at)->format('Y-m-d'),
                    'url' => $item->url,
                ])->all(),
            ],
            'agreements' => Arr::only($record->toArray(), [
                'name', 'partner_organization_id', 'legal_representative_name',
                'legal_representative_address', 'legal_representative_phone', 'address', 'benefit',
                'starts_at', 'ends_at', 'duration_months', 'renewed_at', 'status',
            ]),
            default => [],
        };
    }

    public function rules(string $slug, Institution $institution, ?int $recordId = null): array
    {
        $tenantUnique = fn (string $table, string $column) => [
            Rule::unique($table, $column)
                ->ignore($recordId)
                ->where(fn ($query) => $query
                    ->where('institution_id', $institution->getKey())
                    ->whereNull('deleted_at')),
        ];

        return match ($slug) {
            'member-profiles' => [
                'form.first_names' => ['required', 'string', 'max:255'],
                'form.last_names' => ['required', 'string', 'max:255'],
                'form.date_of_birth' => ['required', 'date'],
                'form.sex' => ['required', Rule::in(array_keys($this->optionSet('sexes')))],
                'form.cellphone' => ['required', 'string', 'max:32'],
                'form.email' => ['required', 'email', 'max:255'],
                'form.college_number' => array_merge(['required', 'string', 'max:32'], $tenantUnique('member_profiles', 'college_number')),
                'form.base_university_id' => ['nullable', 'integer', 'exists:academic_institutions,id'],
                'form.new_base_university_name' => ['nullable', 'string', 'max:255'],
                'form.licensure_research_title' => ['required', 'string'],
                'form.licensure_thesis_url' => ['required', 'url', 'max:2048'],
            ],
            'master-degrees' => $this->rulesForDegree('master_degree_records', $institution, $recordId),
            'doctorates' => $this->rulesForDegree('doctorate_records', $institution, $recordId),
            'second-specialties' => [
                'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
                'form.work_center_id' => ['nullable', 'integer', 'exists:work_centers,id'],
                'form.new_work_center_name' => ['nullable', 'string', 'max:255'],
                'form.specialty_name' => ['required', 'string', 'max:255'],
                'form.sunedu_code' => array_merge(['required', 'string', 'max:64'], $tenantUnique('second_specialty_records', 'sunedu_code')),
                'form.academic_institution_id' => ['nullable', 'integer', 'exists:academic_institutions,id'],
                'form.new_academic_institution_name' => ['nullable', 'string', 'max:255'],
                'form.research_title' => ['required', 'string'],
                'form.thesis_url' => ['required', 'url', 'max:2048'],
            ],
            'auditors' => [
                'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
                'form.work_center_id' => ['nullable', 'integer', 'exists:work_centers,id'],
                'form.new_work_center_name' => ['nullable', 'string', 'max:255'],
                'form.diploma_granter_id' => ['nullable', 'integer', 'exists:partner_organizations,id'],
                'form.new_diploma_granter_name' => ['nullable', 'string', 'max:255'],
                'form.record_number' => array_merge(['required', 'string', 'max:64'], $tenantUnique('auditor_records', 'record_number')),
            ],
            'scientific-associations' => [
                'form.name' => array_merge(['required', 'string', 'max:255'], $tenantUnique('scientific_associations', 'name')),
                'form.public_registry_certificate' => ['required', 'string'],
                'form.objective' => ['required', 'string'],
                'form.tax_id' => ['required', 'string', 'max:32'],
                'form.legal_address' => ['required', 'string', 'max:255'],
                'form.members' => ['required', 'array', 'min:1'],
                'form.members.*.member_profile_id' => ['nullable', 'integer', 'exists:member_profiles,id'],
                'form.members.*.member_name' => ['required', 'string', 'max:255'],
                'form.members.*.responsibility' => ['nullable', 'string', 'max:255'],
            ],
            'sponsorships' => [
                'form.requester_organization_id' => ['nullable', 'integer', 'exists:partner_organizations,id'],
                'form.requester_name' => ['required', 'string', 'max:255'],
                'form.new_requester_organization_name' => ['nullable', 'string', 'max:255'],
                'form.credits_awarded' => ['required', 'numeric', 'min:0'],
                'form.resolution_number' => ['required', 'string', 'max:255'],
                'form.starts_at' => ['required', 'date'],
                'form.ends_at' => ['required', 'date', 'after_or_equal:form.starts_at'],
                'form.status' => ['required', Rule::in(array_keys($this->optionSet('sponsorship_statuses')))],
            ],
            'disciplinary-processes' => [
                'form.member_profile_id' => ['nullable', 'integer', 'exists:member_profiles,id'],
                'form.reported_name' => ['required', 'string', 'max:255'],
                'form.reason' => ['required', 'string'],
                'form.work_center_id' => ['nullable', 'integer', 'exists:work_centers,id'],
                'form.new_work_center_name' => ['nullable', 'string', 'max:255'],
                'form.resolution' => ['required', 'string', 'max:255'],
                'form.sanction' => ['required', 'string'],
                'form.status' => ['required', Rule::in(array_keys($this->optionSet('disciplinary_statuses')))],
            ],
            'illegal-practice-reports' => [
                'form.reported_name' => ['required', 'string', 'max:255'],
                'form.subject' => ['required', 'string'],
                'form.result' => ['required', 'string'],
                'form.status' => ['required', Rule::in(array_keys($this->optionSet('report_statuses')))],
            ],
            'governing-periods' => [
                'form.period_name' => array_merge(['required', 'string', 'max:255'], $tenantUnique('governing_periods', 'period_name')),
                'form.starts_at' => ['nullable', 'date'],
                'form.ends_at' => ['nullable', 'date', 'after_or_equal:form.starts_at'],
                'form.resolution_number' => ['nullable', 'string', 'max:255'],
                'form.status' => ['required', Rule::in(array_keys($this->optionSet('period_statuses')))],
                'form.members' => ['required', 'array', 'min:1'],
                'form.members.*.member_profile_id' => ['nullable', 'integer', 'exists:member_profiles,id'],
                'form.members.*.member_name' => ['required', 'string', 'max:255'],
                'form.members.*.position' => ['nullable', 'string', 'max:255'],
            ],
            'honorary-distinctions' => [
                'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
                'form.work_center_id' => ['nullable', 'integer', 'exists:work_centers,id'],
                'form.new_work_center_name' => ['nullable', 'string', 'max:255'],
                'form.reason' => ['required', 'string'],
                'form.resolution' => ['required', 'string', 'max:255'],
                'form.awarded_at' => ['nullable', 'date'],
            ],
            'member-deaths' => [
                'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
                'form.work_center_id' => ['nullable', 'integer', 'exists:work_centers,id'],
                'form.new_work_center_name' => ['nullable', 'string', 'max:255'],
                'form.date_of_death' => ['required', 'date'],
            ],
            'fam-benefits' => [
                'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
                'form.member_death_id' => ['nullable', 'integer', 'exists:member_deaths,id'],
                'form.beneficiary_name' => ['required', 'string', 'max:255'],
                'form.benefit_delivered_at' => ['required', 'date'],
                'form.resolution' => ['required', 'string', 'max:255'],
                'form.amount' => ['required', 'numeric', 'min:0'],
                'form.contribution_years' => ['required', 'integer', 'min:0', 'max:80'],
            ],
            'conciliators' => [
                'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
                'form.registration_number' => array_merge(['required', 'string', 'max:64'], $tenantUnique('conciliators', 'registration_number')),
            ],
            'retirements' => [
                'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
                'form.retirement_type' => ['required', Rule::in(array_keys($this->optionSet('retirement_types')))],
                'form.cessation_date' => ['required', 'date'],
                'form.work_center_id' => ['nullable', 'integer', 'exists:work_centers,id'],
                'form.former_institution_name' => ['required', 'string', 'max:255'],
            ],
            'research-activities' => [
                'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
                'form.work_center_id' => ['nullable', 'integer', 'exists:work_centers,id'],
                'form.new_work_center_name' => ['nullable', 'string', 'max:255'],
                'form.activity_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
                'form.items' => ['required', 'array', 'min:1'],
                'form.items.*.item_type' => ['required', Rule::in(array_keys($this->optionSet('research_item_types')))],
                'form.items.*.title' => ['required', 'string', 'max:255'],
                'form.items.*.published_at' => ['nullable', 'date'],
                'form.items.*.url' => ['nullable', 'url', 'max:2048'],
            ],
            'agreements' => [
                'form.name' => ['required', 'string', 'max:255'],
                'form.partner_organization_id' => ['nullable', 'integer', 'exists:partner_organizations,id'],
                'form.new_partner_organization_name' => ['nullable', 'string', 'max:255'],
                'form.legal_representative_name' => ['required', 'string', 'max:255'],
                'form.legal_representative_address' => ['required', 'string', 'max:255'],
                'form.legal_representative_phone' => ['required', 'string', 'max:32'],
                'form.address' => ['required', 'string', 'max:255'],
                'form.benefit' => ['required', 'string'],
                'form.starts_at' => ['required', 'date'],
                'form.ends_at' => ['required', 'date', 'after_or_equal:form.starts_at'],
                'form.duration_months' => ['required', 'integer', 'min:1'],
                'form.renewed_at' => ['nullable', 'date'],
                'form.status' => ['required', Rule::in(array_keys($this->optionSet('agreement_statuses')))],
            ],
            default => [],
        };
    }

    public function save(string $slug, Institution $institution, User $user, array $form, ?int $recordId = null): Model
    {
        return DB::transaction(function () use ($slug, $institution, $user, $form, $recordId) {
            return match ($slug) {
                'member-profiles' => $this->saveMemberProfile($institution, $user, $form, $recordId),
                'master-degrees' => $this->saveDegree(new MasterDegreeRecord(), $institution, $user, $form, $recordId),
                'doctorates' => $this->saveDegree(new DoctorateRecord(), $institution, $user, $form, $recordId),
                'second-specialties' => $this->saveSecondSpecialty($institution, $user, $form, $recordId),
                'auditors' => $this->saveAuditor($institution, $user, $form, $recordId),
                'scientific-associations' => $this->saveAssociation($institution, $user, $form, $recordId),
                'sponsorships' => $this->saveSponsorship($institution, $user, $form, $recordId),
                'disciplinary-processes' => $this->saveDisciplinaryProcess($institution, $user, $form, $recordId),
                'illegal-practice-reports' => $this->saveSimpleModel(new IllegalPracticeReport(), $institution, $user, $form, $recordId),
                'governing-periods' => $this->saveGoverningPeriod($institution, $user, $form, $recordId),
                'honorary-distinctions' => $this->saveSimpleModel(new HonoraryDistinction(), $institution, $user, $this->withResolvedRelations($institution, $user, $form, ['work_center_id' => 'new_work_center_name']), $recordId),
                'member-deaths' => $this->saveSimpleModel(new MemberDeath(), $institution, $user, $this->withResolvedRelations($institution, $user, $form, ['work_center_id' => 'new_work_center_name']), $recordId),
                'fam-benefits' => $this->saveSimpleModel(new FamBenefit(), $institution, $user, $form, $recordId),
                'conciliators' => $this->saveSimpleModel(new Conciliator(), $institution, $user, $form, $recordId),
                'retirements' => $this->saveSimpleModel(new Retirement(), $institution, $user, $form, $recordId),
                'research-activities' => $this->saveResearchActivity($institution, $user, $form, $recordId),
                'agreements' => $this->saveAgreement($institution, $user, $form, $recordId),
                default => throw new \InvalidArgumentException("Módulo no soportado: {$slug}"),
            };
        });
    }

    public function delete(string $slug, Institution $institution, int $id): void
    {
        $record = $this->query($slug, $institution)->findOrFail($id);
        $record->delete();
    }

    public function options(Institution $institution): array
    {
        return [
            'sexes' => $this->optionSet('sexes'),
            'sponsorship_statuses' => $this->optionSet('sponsorship_statuses'),
            'disciplinary_statuses' => $this->optionSet('disciplinary_statuses'),
            'report_statuses' => $this->optionSet('report_statuses'),
            'period_statuses' => $this->optionSet('period_statuses'),
            'retirement_types' => $this->optionSet('retirement_types'),
            'research_item_types' => $this->optionSet('research_item_types'),
            'agreement_statuses' => $this->optionSet('agreement_statuses'),
            'members' => MemberProfile::query()
                ->where('institution_id', $institution->getKey())
                ->orderBy('last_names')
                ->orderBy('first_names')
                ->get()
                ->mapWithKeys(fn (MemberProfile $member) => [
                    $member->getKey() => "{$member->full_name} - {$member->college_number}",
                ])->all(),
            'academic_institutions' => AcademicInstitution::query()
                ->where('institution_id', $institution->getKey())
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all(),
            'work_centers' => WorkCenter::query()
                ->where('institution_id', $institution->getKey())
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all(),
            'partner_organizations' => PartnerOrganization::query()
                ->where('institution_id', $institution->getKey())
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all(),
            'death_records' => MemberDeath::query()
                ->where('institution_id', $institution->getKey())
                ->with('member')
                ->orderByDesc('date_of_death')
                ->get()
                ->mapWithKeys(fn (MemberDeath $item) => [
                    $item->getKey() => "{$item->member?->full_name} - {$item->date_of_death?->format('d/m/Y')}",
                ])->all(),
        ];
    }

    public function detailRows(string $slug, Model $record): array
    {
        return match ($slug) {
            'member-profiles' => [
                'Nombres' => $record->first_names,
                'Apellidos' => $record->last_names,
                'Fecha de nacimiento' => $this->dateValue($record->date_of_birth),
                'Sexo' => $this->labelFromSet('sexes', $record->sex),
                'Celular' => $record->cellphone,
                'Correo' => $record->email,
                'Número de colegio' => $record->college_number,
                'Universidad de egreso' => $record->baseUniversity?->name,
                'Título de investigación' => $record->licensure_research_title,
                'URL de tesis' => $record->licensure_thesis_url,
            ],
            'master-degrees', 'doctorates' => [
                'Enfermera' => $record->member?->full_name,
                'Número de registro' => $record->record_number,
                'Centro laboral' => $record->workCenter?->name,
                'Universidad' => $record->academicInstitution?->name,
                'Código SUNEDU' => $record->sunedu_code,
                'Año de obtención' => $record->graduation_year,
                'Título de investigación' => $record->research_title,
                'URL de tesis' => $record->thesis_url,
            ],
            'second-specialties' => [
                'Enfermera' => $record->member?->full_name,
                'Centro laboral' => $record->workCenter?->name,
                'Especialidad' => $record->specialty_name,
                'Código SUNEDU' => $record->sunedu_code,
                'Universidad' => $record->academicInstitution?->name,
                'Título de investigación' => $record->research_title,
                'URL de tesis' => $record->thesis_url,
            ],
            'auditors' => [
                'Enfermera' => $record->member?->full_name,
                'Centro laboral' => $record->workCenter?->name,
                'Entidad otorgante' => $record->diplomaGranter?->name,
                'Número de registro' => $record->record_number,
            ],
            'scientific-associations' => [
                'Nombre' => $record->name,
                'Constancia' => $record->public_registry_certificate,
                'Objetivo' => $record->objective,
                'RUC' => $record->tax_id,
                'Domicilio legal' => $record->legal_address,
            ],
            'sponsorships' => [
                'Institución solicitante' => $record->requester_name,
                'Créditos otorgados' => $record->credits_awarded,
                'Resolución' => $record->resolution_number,
                'Inicio' => $this->dateValue($record->starts_at),
                'Fin' => $this->dateValue($record->ends_at),
                'Estado' => $this->labelFromSet('sponsorship_statuses', $record->status),
            ],
            'disciplinary-processes' => [
                'Nombre' => $record->reported_name,
                'Motivo' => $record->reason,
                'Centro laboral' => $record->workCenter?->name,
                'Resolución' => $record->resolution,
                'Sanción' => $record->sanction,
                'Estado' => $this->labelFromSet('disciplinary_statuses', $record->status),
            ],
            'illegal-practice-reports' => [
                'Nombre' => $record->reported_name,
                'Asunto' => $record->subject,
                'Resultado' => $record->result,
                'Estado' => $this->labelFromSet('report_statuses', $record->status),
            ],
            'governing-periods' => [
                'Periodo' => $record->period_name,
                'Inicio' => $this->dateValue($record->starts_at),
                'Fin' => $this->dateValue($record->ends_at),
                'Resolución' => $record->resolution_number,
                'Estado' => $this->labelFromSet('period_statuses', $record->status),
            ],
            'honorary-distinctions' => [
                'Enfermera' => $record->member?->full_name,
                'Centro laboral' => $record->workCenter?->name,
                'Motivo' => $record->reason,
                'Resolución' => $record->resolution,
                'Fecha' => $this->dateValue($record->awarded_at),
            ],
            'member-deaths' => [
                'Enfermera' => $record->member?->full_name,
                'Centro laboral' => $record->workCenter?->name,
                'Número de colegio' => $record->member?->college_number,
                'Fecha de defunción' => $this->dateValue($record->date_of_death),
            ],
            'fam-benefits' => [
                'Enfermera' => $record->member?->full_name,
                'Beneficiario' => $record->beneficiary_name,
                'Fecha de fallecimiento' => $this->dateValue($record->deathRecord?->date_of_death),
                'Fecha de entrega' => $this->dateValue($record->benefit_delivered_at),
                'Resolución' => $record->resolution,
                'Importe' => $record->amount,
                'Años de aportación' => $record->contribution_years,
            ],
            'conciliators' => [
                'Enfermera' => $record->member?->full_name,
                'Registro' => $record->registration_number,
                'Correo' => $record->member?->email,
                'Celular' => $record->member?->cellphone,
            ],
            'retirements' => [
                'Enfermera' => $record->member?->full_name,
                'Tipo' => $this->labelFromSet('retirement_types', $record->retirement_type),
                'Fecha de cese' => $this->dateValue($record->cessation_date),
                'Institución donde laboró' => $record->former_institution_name ?: $record->workCenter?->name,
            ],
            'research-activities' => [
                'Enfermera' => $record->member?->full_name,
                'Centro laboral' => $record->workCenter?->name,
                'Año de referencia' => $record->activity_year,
                'Producciones' => $record->items->count(),
            ],
            'agreements' => [
                'Nombre del convenio' => $record->name,
                'Representante legal' => $record->legal_representative_name,
                'Dirección del representante' => $record->legal_representative_address,
                'Celular del representante' => $record->legal_representative_phone,
                'Dirección' => $record->address,
                'Beneficio' => $record->benefit,
                'Inicio' => $this->dateValue($record->starts_at),
                'Fin' => $this->dateValue($record->ends_at),
                'Duración (meses)' => $record->duration_months,
                'Renovación' => $this->dateValue($record->renewed_at),
                'Estado' => $this->labelFromSet('agreement_statuses', $record->status),
            ],
            default => [],
        };
    }

    public function relatedCollections(string $slug, Model $record): array
    {
        return match ($slug) {
            'scientific-associations' => [
                'Integrantes' => $record->members->map(fn ($item) => [
                    'Nombre' => $item->member_name,
                    'Rol' => $item->responsibility,
                    'Vinculada' => $item->memberProfile?->full_name,
                ])->all(),
            ],
            'governing-periods' => [
                'Miembros de junta' => $record->members->map(fn ($item) => [
                    'Nombre' => $item->member_name,
                    'Cargo' => $item->position,
                    'Vinculada' => $item->memberProfile?->full_name,
                ])->all(),
            ],
            'research-activities' => [
                'Producción científica' => $record->items->map(fn ($item) => [
                    'Tipo' => $this->labelFromSet('research_item_types', $item->item_type),
                    'Título' => $item->title,
                    'Fecha' => $this->dateValue($item->published_at),
                    'URL' => $item->url,
                ])->all(),
            ],
            default => [],
        };
    }

    public function blankRow(string $name): array
    {
        return match ($name) {
            'members' => ['member_profile_id' => null, 'member_name' => '', 'responsibility' => '', 'position' => ''],
            'items' => ['item_type' => 'article', 'title' => '', 'published_at' => null, 'url' => ''],
            default => [],
        };
    }

    private function memberLinkedSchema(string $label, array $fields): array
    {
        return [
            [
                'title' => 'Vinculación',
                'fields' => [
                    ['name' => 'member_profile_id', 'label' => 'Enfermera colegiada', 'type' => 'select', 'required' => true, 'options' => 'members'],
                ],
            ],
            [
                'title' => Str::title($label),
                'fields' => $fields,
            ],
        ];
    }

    private function rulesForDegree(string $table, Institution $institution, ?int $recordId = null): array
    {
        $tenantUnique = fn (string $column) => [
            Rule::unique($table, $column)
                ->ignore($recordId)
                ->where(fn ($query) => $query
                    ->where('institution_id', $institution->getKey())
                    ->whereNull('deleted_at')),
        ];

        return [
            'form.member_profile_id' => ['required', 'integer', 'exists:member_profiles,id'],
            'form.record_number' => array_merge(['required', 'string', 'max:64'], $tenantUnique('record_number')),
            'form.work_center_id' => ['nullable', 'integer', 'exists:work_centers,id'],
            'form.new_work_center_name' => ['nullable', 'string', 'max:255'],
            'form.academic_institution_id' => ['nullable', 'integer', 'exists:academic_institutions,id'],
            'form.new_academic_institution_name' => ['nullable', 'string', 'max:255'],
            'form.sunedu_code' => array_merge(['required', 'string', 'max:64'], $tenantUnique('sunedu_code')),
            'form.graduation_year' => ['required', 'integer', 'min:1900', 'max:2100'],
            'form.research_title' => ['required', 'string'],
            'form.thesis_url' => ['required', 'url', 'max:2048'],
        ];
    }

    private function saveMemberProfile(Institution $institution, User $user, array $form, ?int $recordId = null): MemberProfile
    {
        $form['base_university_id'] = $this->resolveAcademicInstitution($institution, $user, $form['base_university_id'] ?? null, $form['new_base_university_name'] ?? null);
        return $this->saveSimpleModel(new MemberProfile(), $institution, $user, Arr::except($form, ['new_base_university_name']), $recordId);
    }

    private function saveDegree(TenantScopedModel $prototype, Institution $institution, User $user, array $form, ?int $recordId = null): Model
    {
        $form = $this->withResolvedRelations($institution, $user, $form, [
            'work_center_id' => 'new_work_center_name',
            'academic_institution_id' => 'new_academic_institution_name',
        ]);

        return $this->saveSimpleModel($prototype, $institution, $user, Arr::except($form, ['new_work_center_name', 'new_academic_institution_name']), $recordId);
    }

    private function saveSecondSpecialty(Institution $institution, User $user, array $form, ?int $recordId = null): Model
    {
        $form = $this->withResolvedRelations($institution, $user, $form, [
            'work_center_id' => 'new_work_center_name',
            'academic_institution_id' => 'new_academic_institution_name',
        ]);

        return $this->saveSimpleModel(new SecondSpecialtyRecord(), $institution, $user, Arr::except($form, ['new_work_center_name', 'new_academic_institution_name']), $recordId);
    }

    private function saveAuditor(Institution $institution, User $user, array $form, ?int $recordId = null): Model
    {
        $form = $this->withResolvedRelations($institution, $user, $form, [
            'work_center_id' => 'new_work_center_name',
            'diploma_granter_id' => 'new_diploma_granter_name',
        ]);

        return $this->saveSimpleModel(new AuditorRecord(), $institution, $user, Arr::except($form, ['new_work_center_name', 'new_diploma_granter_name']), $recordId);
    }

    private function saveAssociation(Institution $institution, User $user, array $form, ?int $recordId = null): ScientificAssociation
    {
        $record = $this->saveSimpleModel(new ScientificAssociation(), $institution, $user, Arr::except($form, ['members']), $recordId);
        $record->members()->delete();

        foreach ($form['members'] ?? [] as $index => $member) {
            $record->members()->create([
                'member_profile_id' => $member['member_profile_id'] ?: null,
                'member_name' => $member['member_name'],
                'responsibility' => $member['responsibility'] ?: null,
                'display_order' => $index + 1,
            ]);
        }

        return $record->fresh(['members.memberProfile']);
    }

    private function saveSponsorship(Institution $institution, User $user, array $form, ?int $recordId = null): Model
    {
        if (! empty($form['new_requester_organization_name']) && empty($form['requester_organization_id'])) {
            $form['requester_organization_id'] = $this->resolvePartnerOrganization($institution, $user, null, $form['new_requester_organization_name'], 'sponsor_requester');
            $form['requester_name'] = $form['new_requester_organization_name'];
        }

        return $this->saveSimpleModel(new Sponsorship(), $institution, $user, Arr::except($form, ['new_requester_organization_name']), $recordId);
    }

    private function saveDisciplinaryProcess(Institution $institution, User $user, array $form, ?int $recordId = null): Model
    {
        $form = $this->withResolvedRelations($institution, $user, $form, ['work_center_id' => 'new_work_center_name']);

        if (empty($form['reported_name']) && ! empty($form['member_profile_id'])) {
            $form['reported_name'] = MemberProfile::find($form['member_profile_id'])?->full_name;
        }

        return $this->saveSimpleModel(new DisciplinaryProcess(), $institution, $user, Arr::except($form, ['new_work_center_name']), $recordId);
    }

    private function saveGoverningPeriod(Institution $institution, User $user, array $form, ?int $recordId = null): GoverningPeriod
    {
        $record = $this->saveSimpleModel(new GoverningPeriod(), $institution, $user, Arr::except($form, ['members']), $recordId);
        $record->members()->delete();

        foreach ($form['members'] ?? [] as $index => $member) {
            $record->members()->create([
                'member_profile_id' => $member['member_profile_id'] ?: null,
                'member_name' => $member['member_name'],
                'position' => $member['position'] ?: null,
                'display_order' => $index + 1,
            ]);
        }

        return $record->fresh(['members.memberProfile']);
    }

    private function saveResearchActivity(Institution $institution, User $user, array $form, ?int $recordId = null): ResearchActivity
    {
        $form = $this->withResolvedRelations($institution, $user, $form, ['work_center_id' => 'new_work_center_name']);
        $record = $this->saveSimpleModel(new ResearchActivity(), $institution, $user, Arr::except($form, ['items', 'new_work_center_name']), $recordId);
        $record->items()->delete();

        foreach ($form['items'] ?? [] as $index => $item) {
            $record->items()->create([
                'item_type' => $item['item_type'],
                'title' => $item['title'],
                'published_at' => $item['published_at'] ?: null,
                'url' => $item['url'] ?: null,
                'display_order' => $index + 1,
            ]);
        }

        return $record->fresh(['member', 'workCenter', 'items']);
    }

    private function saveAgreement(Institution $institution, User $user, array $form, ?int $recordId = null): Model
    {
        if (! empty($form['new_partner_organization_name']) && empty($form['partner_organization_id'])) {
            $form['partner_organization_id'] = $this->resolvePartnerOrganization($institution, $user, null, $form['new_partner_organization_name'], 'agreement_counterparty');
        }

        return $this->saveSimpleModel(new Agreement(), $institution, $user, Arr::except($form, ['new_partner_organization_name']), $recordId);
    }

    private function saveSimpleModel(TenantScopedModel $prototype, Institution $institution, User $user, array $form, ?int $recordId = null): Model
    {
        $model = $recordId
            ? $prototype->newQuery()->where('institution_id', $institution->getKey())->findOrFail($recordId)
            : $prototype->newInstance();

        $model->fill(Arr::except($form, [
            'new_work_center_name',
            'new_academic_institution_name',
            'new_base_university_name',
            'new_diploma_granter_name',
            'new_requester_organization_name',
            'new_partner_organization_name',
        ]));

        $model->institution_id = $institution->getKey();
        $model->updated_by = $user->getKey();
        $model->created_by ??= $user->getKey();
        $model->save();

        return $model;
    }

    private function withResolvedRelations(Institution $institution, User $user, array $form, array $map): array
    {
        foreach ($map as $relationField => $newNameField) {
            if (! empty($form[$relationField]) || blank($form[$newNameField] ?? null)) {
                continue;
            }

            $form[$relationField] = match ($relationField) {
                'work_center_id' => $this->resolveWorkCenter($institution, $user, null, $form[$newNameField]),
                'academic_institution_id', 'base_university_id' => $this->resolveAcademicInstitution($institution, $user, null, $form[$newNameField]),
                'diploma_granter_id', 'requester_organization_id', 'partner_organization_id'
                    => $this->resolvePartnerOrganization($institution, $user, null, $form[$newNameField], 'general'),
                default => $form[$relationField] ?? null,
            };
        }

        return $form;
    }

    private function resolveAcademicInstitution(Institution $institution, User $user, ?int $id, ?string $name): ?int
    {
        if ($id || blank($name)) {
            return $id;
        }

        return AcademicInstitution::firstOrCreate(
            ['institution_id' => $institution->getKey(), 'name' => trim($name)],
            ['created_by' => $user->getKey(), 'updated_by' => $user->getKey()]
        )->getKey();
    }

    private function resolveWorkCenter(Institution $institution, User $user, ?int $id, ?string $name): ?int
    {
        if ($id || blank($name)) {
            return $id;
        }

        return WorkCenter::firstOrCreate(
            ['institution_id' => $institution->getKey(), 'name' => trim($name)],
            ['created_by' => $user->getKey(), 'updated_by' => $user->getKey()]
        )->getKey();
    }

    private function resolvePartnerOrganization(Institution $institution, User $user, ?int $id, ?string $name, string $category = 'general'): ?int
    {
        if ($id || blank($name)) {
            return $id;
        }

        return PartnerOrganization::firstOrCreate(
            ['institution_id' => $institution->getKey(), 'name' => trim($name)],
            ['category' => $category, 'created_by' => $user->getKey(), 'updated_by' => $user->getKey()]
        )->getKey();
    }

    private function optionSet(string $key): array
    {
        return match ($key) {
            'sexes' => ['female' => 'Femenino', 'male' => 'Masculino', 'non_binary' => 'No binario', 'prefer_not_to_say' => 'Prefiere no indicar'],
            'sponsorship_statuses' => ['pending' => 'Pendiente', 'approved' => 'Aprobado', 'completed' => 'Completado', 'canceled' => 'Cancelado'],
            'disciplinary_statuses' => ['open' => 'Abierto', 'resolved' => 'Resuelto', 'archived' => 'Archivado'],
            'report_statuses' => ['reported' => 'Reportado', 'investigating' => 'En investigación', 'closed' => 'Cerrado'],
            'period_statuses' => ['planned' => 'Planificado', 'active' => 'Activo', 'closed' => 'Cerrado'],
            'retirement_types' => ['cesante' => 'Cesante', 'jubilada' => 'Jubilada'],
            'research_item_types' => ['article' => 'Artículo', 'research' => 'Investigación', 'book_or_chapter' => 'Libro o capítulo'],
            'agreement_statuses' => ['draft' => 'Borrador', 'active' => 'Activo', 'expired' => 'Vencido', 'closed' => 'Cerrado'],
            default => [],
        };
    }

    private function labelFromSet(string $set, ?string $value): ?string
    {
        return $this->optionSet($set)[$value] ?? $value;
    }

    private function dateValue($value): ?string
    {
        return $value ? $value->format('d/m/Y') : null;
    }
}
