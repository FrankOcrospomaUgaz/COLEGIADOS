<div class="space-y-6">
    <section class="app-page-header">
        <div class="app-page-strip">
            <div class="app-page-strip-head items-center">
                <span class="app-page-strip-icon">
                    <i class="fas fa-file-lines"></i>
                </span>
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">{{ $module['title'] }}</h2>
                </div>
            </div>

            <div class="app-page-strip-breadcrumbs justify-end">
                <a href="{{ route('dashboard') }}" wire:navigate class="app-breadcrumb-link">Inicio</a>
                <i class="fas fa-angle-right text-xs text-slate-400"></i>
                <a href="{{ route('registries.catalog') }}" wire:navigate class="app-breadcrumb-link">Registros</a>
                <i class="fas fa-angle-right text-xs text-slate-400"></i>
                <a href="{{ route('registries.index', $module['slug']) }}" wire:navigate class="app-breadcrumb-link">{{ $module['title'] }}</a>
            </div>
        </div>
    </section>

    <section class="app-panel brand-surface p-6 sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="module-chip">{{ $module['category'] }}</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight">Ficha operativa</h2>
                <p class="mt-3 max-w-3xl text-base text-slate-600">Consulta datos, resoluciones, enlaces y trazabilidad del registro.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('registries.index', $module['slug']) }}" wire:navigate class="app-pill-action app-pill-action-info">
                    <i class="fas fa-table-list"></i>
                    <span>Volver al listado</span>
                </a>
                <a href="{{ route('registries.edit', ['module' => $module['slug'], 'record' => $record->id]) }}" wire:navigate class="app-pill-action app-pill-action-primary">
                    <i class="fas fa-pen"></i>
                    <span>Editar</span>
                </a>
            </div>
        </div>
    </section>

    @if (session('status'))
        <div class="app-panel border border-emerald-200 bg-emerald-50/90 p-4 text-sm font-semibold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="app-panel p-6 sm:p-8">
        <div class="app-form-section-head">
            <span class="app-form-section-badge">
                <i class="fas fa-id-card"></i>
            </span>
            <div>
                <h3 class="app-card-title">Ficha del registro</h3>
                <p class="app-form-section-copy">Consulta los datos persistidos y la trazabilidad principal del módulo.</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($details as $label => $value)
                <article class="app-panel-muted px-4 py-4">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">{{ $label }}</p>
                    <p class="mb-0 text-sm font-semibold text-slate-800">
                        @if (blank($value))
                            <span class="text-slate-300">Sin dato</span>
                        @elseif (\Illuminate\Support\Str::startsWith((string) $value, ['http://', 'https://']))
                            <a href="{{ $value }}" target="_blank" class="text-teal-700 underline underline-offset-4">{{ $value }}</a>
                        @else
                            {{ $value }}
                        @endif
                    </p>
                </article>
            @endforeach
        </div>
    </section>

    @foreach ($collections as $title => $items)
        <section class="app-panel p-6 sm:p-8">
            <div class="app-form-section-head">
                <span class="app-form-section-badge">
                    <i class="fas fa-layer-group"></i>
                </span>
                <div>
                    <h3 class="app-card-title">{{ $title }}</h3>
                    <p class="app-form-section-copy">Colecciones relacionadas con el registro principal.</p>
                </div>
            </div>

            <div class="grid gap-4">
                @foreach ($items as $item)
                    <article class="app-panel-muted grid gap-4 px-4 py-4 md:grid-cols-3">
                        @foreach ($item as $label => $value)
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">{{ $label }}</p>
                                <p class="mb-0 text-sm font-semibold text-slate-800">{{ $value ?: 'Sin dato' }}</p>
                            </div>
                        @endforeach
                    </article>
                @endforeach
            </div>
        </section>
    @endforeach
</div>
