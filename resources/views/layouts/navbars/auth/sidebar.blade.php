@php
    $service = app(\App\Services\RegistryModuleService::class);
    $groupedModules = $service->groupedModules();
    $categoryMeta = $service->categoryMeta();
    $currentModuleSlug = request()->route('module');
    $institution = auth()->user()?->currentInstitution;

    $primaryLinks = [
        ['label' => 'Panel principal', 'route' => 'dashboard', 'icon' => 'fas fa-house', 'match' => ['dashboard']],
        ['label' => 'Directorio de módulos', 'route' => 'registries.catalog', 'icon' => 'fas fa-layer-group', 'match' => ['registries.catalog']],
        ['label' => 'Institución', 'route' => 'institution.profile', 'icon' => 'fas fa-building', 'match' => ['institution.profile']],
        ['label' => 'Mi perfil', 'route' => 'profile', 'icon' => 'fas fa-id-card', 'match' => ['profile']],
    ];
@endphp

<div class="app-sidebar-backdrop" data-sidebar-dismiss></div>

<aside class="app-sidebar-panel" aria-label="Menú principal">
    <div class="app-sidebar-surface">
        {{-- <div class="app-sidebar-top">
            <div class="app-sidebar-logo">
                <div class="app-sidebar-logo-mark">
                    <span>CR</span>
                </div>
                <div>
                    <p class="app-sidebar-logo-title">{{ config('app.name', 'COLEGIADOS') }}</p>
                    <p class="app-sidebar-logo-copy">Administra y registra</p>
                </div>
            </div>

            <button type="button" class="app-sidebar-close" data-sidebar-dismiss aria-label="Cerrar menu">
                <i class="fas fa-xmark"></i>
            </button>
        </div> --}}

        <div class="app-sidebar-scroll">
            {{-- <section class="app-sidebar-section">
                <p class="app-sidebar-section-label">Accesos generales</p>

                <div class="app-sidebar-nav-list">
                    @foreach ($primaryLinks as $link)
                        @php
                            $active = collect($link['match'])->contains(fn ($pattern) => request()->routeIs($pattern));
                        @endphp

                        <a
                            href="{{ route($link['route']) }}"
                            wire:navigate
                            class="app-sidebar-entry {{ $active ? 'app-sidebar-entry-active' : '' }}"
                        >
                            <span class="app-sidebar-entry-icon">
                                <i class="{{ $link['icon'] }}"></i>
                            </span>
                            <span>{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </section> --}}

            <section class="app-sidebar-section">
                <p class="app-sidebar-section-label">Módulos operativos</p>

                <div class="space-y-3">
                    @foreach ($groupedModules as $category => $modules)
                        @php
                            $meta = $categoryMeta[$category] ?? null;
                            $categoryActive = collect($modules)->contains(fn (array $module) => $module['slug'] === $currentModuleSlug);
                        @endphp

                        <details class="app-sidebar-group-card {{ $categoryActive ? 'app-sidebar-group-card-active' : '' }}" @if ($categoryActive) open @endif>
                            <summary class="app-sidebar-group-toggle">
                                <div class="app-sidebar-group-heading">
                                    <span class="app-sidebar-group-mark">
                                        <i class="{{ $meta['icon'] ?? 'fas fa-folder' }}"></i>
                                    </span>
                                    <div>
                                        <p class="app-sidebar-group-name">{{ $meta['title'] ?? $category }}</p>
                                    </div>
                                </div>

                                <i class="fas fa-angle-down app-sidebar-group-caret"></i>
                            </summary>

                            <div class="app-sidebar-group-body">
                                @foreach ($modules as $module)
                                    @php $moduleActive = $currentModuleSlug === $module['slug']; @endphp

                                    <a
                                        href="{{ route('registries.index', $module['slug']) }}"
                                        wire:navigate
                                        class="app-sidebar-module-link app-sidebar-module-card {{ $moduleActive ? 'app-sidebar-module-card-active' : '' }}"
                                    >
                                        <div class="min-w-0">
                                            <p class="app-sidebar-module-name">{{ $module['title'] }}</p>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if ($moduleActive)
                                                <span class="app-sidebar-badge">activo</span>
                                            @endif
                                            <i class="fas fa-angle-right app-sidebar-module-arrow"></i>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </details>
                    @endforeach
                </div>
            </section>
        </div>

     
    </div>
</aside>
