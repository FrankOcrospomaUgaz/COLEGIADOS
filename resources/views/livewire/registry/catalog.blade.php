<div class="space-y-6">
    <section class="app-page-header">
        <div class="app-page-strip">
            <div class="app-page-strip-head">
                <span class="app-page-strip-icon">
                    <i class="fas fa-table-cells-large"></i>
                </span>
                <div>
                    <p class="app-page-kicker">Mapa de registros</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Módulos del sistema</h2>
                    <p class="mt-3 max-w-3xl app-page-strip-copy">
                    Los módulos se organizan por áreas de trabajo y cada uno ofrece accesos directos a su listado y
                    al formulario de nuevo registro.
                    </p>
                </div>
            </div>

            <div class="space-y-4 text-right">
                <div class="app-page-strip-breadcrumbs justify-end">
                    <a href="{{ route('dashboard') }}" wire:navigate class="app-breadcrumb-link">Inicio</a>
                    <i class="fas fa-angle-right text-xs text-slate-400"></i>
                    <span class="app-breadcrumb-current">Registros</span>
                    <i class="fas fa-angle-right text-xs text-slate-400"></i>
                    <span class="app-breadcrumb-current">Directorio</span>
                </div>
                <a href="{{ route('dashboard') }}" wire:navigate class="app-btn app-btn-secondary">Volver al panel</a>
            </div>
        </div>
    </section>

    @foreach ($groupedModules as $group => $modules)
        @php $meta = $categoryMeta[$group] ?? null; @endphp
        <section class="app-panel p-6">
            <div class="mb-6 flex items-start gap-3">
                <span class="app-section-icon">
                    <i class="{{ $meta['icon'] ?? 'fas fa-folder' }}"></i>
                </span>
                <div>
                    <h3 class="app-card-title">{{ $meta['title'] ?? $group }}</h3>
                    <p class="app-card-subtitle">
                        {{ $meta['description'] ?? $group }}. {{ count($modules) }} módulos activos para {{ $institution->name }}.
                    </p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($modules as $module)
                    <article class="app-module-directory-card">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div>
                                <p class="mb-1 text-base font-bold text-slate-900">{{ $module['title'] }}</p>
                                <p class="mb-0 text-sm text-slate-500">{{ $module['description'] }}</p>
                            </div>
                            <span class="status-pill whitespace-nowrap">{{ $module['count'] }} registros</span>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <p class="mb-1 text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Ruta operativa</p>
                            <p class="mb-0 text-sm text-slate-700">{{ $meta['title'] ?? $group }} / {{ $module['title'] }}</p>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('registries.index', $module['slug']) }}" wire:navigate class="app-btn app-btn-primary">Abrir listado</a>
                            <a href="{{ route('registries.create', $module['slug']) }}" wire:navigate class="app-btn app-btn-secondary">Nuevo registro</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endforeach
</div>
