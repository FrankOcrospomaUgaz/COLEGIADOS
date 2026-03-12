<div class="space-y-6">
    <section class="app-page-header">
        <div class="app-page-strip">
            <div class="app-page-strip-head">
                <span class="app-page-strip-icon">
                    <i class="fas fa-chart-line"></i>
                </span>
                <div>
                    <p class="app-page-kicker">Operación diaria</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Panel de control</h2>
                    <p class="mt-3 max-w-3xl app-page-strip-copy">
                    Supervisa el padrón base, el avance de registros especializados y los procesos institucionales
                    desde una sola bandeja de trabajo para {{ $institution->name }}.
                    </p>
                </div>
            </div>

            <div class="space-y-4 text-right">
                <div class="app-page-strip-breadcrumbs justify-end">
                    <a href="{{ route('dashboard') }}" wire:navigate class="app-breadcrumb-link">Inicio</a>
                    <i class="fas fa-angle-right text-xs text-slate-400"></i>
                    <span class="app-breadcrumb-current">Panel principal</span>
                </div>

                <div class="flex flex-wrap justify-end gap-3">
                    <a href="{{ route('registries.catalog') }}" wire:navigate class="app-pill-action app-pill-action-primary">
                        <i class="fas fa-layer-group"></i>
                        <span>Módulos</span>
                    </a>
                    <a href="{{ route('institution.profile') }}" wire:navigate class="app-btn app-btn-secondary">Institución</a>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard-executive-grid">
        <article class="dashboard-hero-card brand-hero">
            <div class="dashboard-hero-head">
                <div>
                    <span class="dashboard-chip">Vista ejecutiva</span>
                    <h3 class="dashboard-hero-title">Indicadores institucionales en una sola pantalla</h3>
                    <p class="dashboard-hero-copy">
                        Sigue cobertura de módulos, carga acumulada y actividad reciente con una lectura más ejecutiva
                        del trabajo operativo en {{ $institution->name }}.
                    </p>
                </div>
            </div>

            <div class="dashboard-highlight-grid">
                <article class="dashboard-highlight-card">
                    <span class="dashboard-highlight-label">Registros consolidados</span>
                    <strong class="dashboard-highlight-value">{{ $totalRecords }}</strong>
                    <span class="dashboard-highlight-copy">Carga total entre los 17 módulos institucionales.</span>
                </article>
                <article class="dashboard-highlight-card">
                    <span class="dashboard-highlight-label">Cobertura de módulos</span>
                    <strong class="dashboard-highlight-value">{{ $modulesCoverage }}%</strong>
                    <span class="dashboard-highlight-copy">{{ $activeModules }} de {{ $totalModules }} módulos ya tienen datos.</span>
                </article>
                <article class="dashboard-highlight-card">
                    <span class="dashboard-highlight-label">Áreas activas</span>
                    <strong class="dashboard-highlight-value">{{ $activeCategories }}/{{ $totalCategories }}</strong>
                    <span class="dashboard-highlight-copy">Frentes funcionales con registros en operación.</span>
                </article>
            </div>
        </article>

        <article class="app-panel dashboard-pulse-card">
            <div class="dashboard-pulse-head">
                <div>
                    <h3 class="app-card-title">Pulso operativo</h3>
                    <p class="app-card-subtitle">Cobertura y carga viva del sistema.</p>
                </div>
            </div>

            <div class="dashboard-gauge-grid">
                <div class="dashboard-gauge-card">
                    <div class="dashboard-gauge-ring" style="--progress: {{ $modulesCoverage }}; --gauge-color: #14b8a6;">
                        <div class="dashboard-gauge-core">
                            <strong>{{ $modulesCoverage }}%</strong>
                            <span>Módulos</span>
                        </div>
                    </div>
                    <p class="dashboard-gauge-copy">{{ $activeModules }} módulos con actividad registrada.</p>
                </div>

                <div class="dashboard-gauge-card">
                    <div class="dashboard-gauge-ring" style="--progress: {{ $categoryCoverage }}; --gauge-color: #38bdf8;">
                        <div class="dashboard-gauge-core">
                            <strong>{{ $categoryCoverage }}%</strong>
                            <span>Áreas</span>
                        </div>
                    </div>
                    <p class="dashboard-gauge-copy">{{ $activeCategories }} áreas con movimiento institucional.</p>
                </div>
            </div>

            <div class="dashboard-kpi-list">
                <article class="dashboard-kpi-item">
                    <span class="dashboard-kpi-label">Colegiadas registradas</span>
                    <strong class="dashboard-kpi-value">{{ $summary['members'] }}</strong>
                </article>
                <article class="dashboard-kpi-item">
                    <span class="dashboard-kpi-label">Usuarios activos</span>
                    <strong class="dashboard-kpi-value">{{ $summary['active_users'] }}</strong>
                </article>
                <article class="dashboard-kpi-item">
                    <span class="dashboard-kpi-label">Convenios vigentes</span>
                    <strong class="dashboard-kpi-value">{{ $summary['active_agreements'] }}/{{ $summary['agreements_total'] }}</strong>
                </article>
                <article class="dashboard-kpi-item">
                    <span class="dashboard-kpi-label">Procesos abiertos</span>
                    <strong class="dashboard-kpi-value">{{ $summary['open_processes'] }}/{{ $summary['processes_total'] }}</strong>
                </article>
            </div>
        </article>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
        <article class="app-panel p-6">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h3 class="app-card-title">Distribución por áreas</h3>
                    <p class="app-card-subtitle">Qué frentes concentran hoy la mayor parte de la carga institucional.</p>
                </div>
                <a href="{{ route('registries.catalog') }}" wire:navigate class="app-btn app-btn-secondary">Ver directorio</a>
            </div>

            <div class="space-y-4">
                @foreach ($categoryStats as $stat)
                    <article class="dashboard-distribution-item">
                        <div class="dashboard-distribution-head">
                            <div class="dashboard-distribution-title-wrap">
                                <span class="dashboard-distribution-icon">
                                    <i class="{{ $stat['icon'] }}"></i>
                                </span>
                                <div>
                                    <p class="dashboard-distribution-title">{{ $stat['title'] }}</p>
                                    <p class="dashboard-distribution-meta">{{ $stat['modules_active'] }} de {{ $stat['modules_total'] }} módulos activos</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <strong class="dashboard-distribution-value">{{ $stat['records_total'] }}</strong>
                                <p class="dashboard-distribution-meta">{{ $stat['share'] }}% del total</p>
                            </div>
                        </div>

                        <div class="dashboard-progress-track">
                            <span class="dashboard-progress-fill" style="width: {{ max(8, $stat['share']) }}%"></span>
                        </div>
                    </article>
                @endforeach
            </div>
        </article>

        <article class="app-panel p-6">
            <div class="mb-6">
                <h3 class="app-card-title">Actividad reciente</h3>
                <p class="app-card-subtitle">Movimientos de colegiadas, convenios, auspicios y procesos en los últimos 7 días.</p>
            </div>

            <div class="dashboard-activity-chart">
                @foreach ($activityWindow as $day)
                    <div class="dashboard-activity-col">
                        <span class="dashboard-activity-count">{{ $day['count'] }}</span>
                        <div class="dashboard-activity-bar-wrap">
                            <span class="dashboard-activity-bar" style="height: {{ $day['height'] }}%"></span>
                        </div>
                        <span class="dashboard-activity-label">{{ $day['label'] }}</span>
                        <span class="dashboard-activity-date">{{ $day['date'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="dashboard-insight-grid">
                <article class="dashboard-insight-card">
                    <span class="dashboard-insight-label">Auspicios</span>
                    <strong class="dashboard-insight-value">{{ $summary['sponsorships_total'] }}</strong>
                    <span class="dashboard-insight-copy">Trámites registrados a la fecha.</span>
                </article>
                <article class="dashboard-insight-card">
                    <span class="dashboard-insight-label">Convenios</span>
                    <strong class="dashboard-insight-value">{{ $summary['agreements_total'] }}</strong>
                    <span class="dashboard-insight-copy">Instrumentos cargados en la institución.</span>
                </article>
            </div>
        </article>
    </section>

    <section class="grid gap-6 xl:grid-cols-2">
        <div class="app-panel p-6">
            <div class="mb-4">
                <h3 class="app-card-title">Últimas colegiadas</h3>
                <p class="app-card-subtitle">Altas recientes del padrón principal.</p>
            </div>

            @forelse ($recentMembers as $member)
                <div class="app-list-row">
                    <div>
                        <p class="mb-0 text-sm font-semibold text-slate-800">{{ $member->full_name }}</p>
                        <p class="mb-0 text-xs text-slate-500">{{ $member->college_number ?: 'Sin número de colegio' }}</p>
                    </div>
                    <span class="text-xs text-slate-400">{{ $member->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="empty-state">
                    <p class="mb-1 font-semibold text-slate-700">Aún no hay colegiadas registradas</p>
                    <p class="mb-0 text-sm text-slate-500">Comienza por el padrón base para habilitar el resto de flujos.</p>
                </div>
            @endforelse
        </div>

        <div class="app-panel p-6">
            <div class="mb-4">
                <h3 class="app-card-title">Auspicios recientes</h3>
                <p class="app-card-subtitle">Últimos trámites institucionales registrados.</p>
            </div>

            @forelse ($recentSponsorships as $item)
                <div class="app-list-row">
                    <div>
                        <p class="mb-0 text-sm font-semibold text-slate-800">{{ $item->requester_name }}</p>
                        <p class="mb-0 text-xs text-slate-500">Resolución {{ $item->resolution_number ?: 'sin número' }}</p>
                    </div>
                    <span class="status-pill">{{ ucfirst($item->status) }}</span>
                </div>
            @empty
                <div class="empty-state">
                    <p class="mb-1 font-semibold text-slate-700">Sin auspicios registrados</p>
                    <p class="mb-0 text-sm text-slate-500">El módulo está listo para créditos, resoluciones y fechas.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
