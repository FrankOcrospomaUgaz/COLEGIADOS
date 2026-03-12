@php
    $user = auth()->user();
    $institution = $user?->currentInstitution;
    $activeMembership = $user?->activeMembership();
    $service = app(\App\Services\RegistryModuleService::class);
    $groupedModules = $service->groupedModules();
    $categoryMeta = $service->categoryMeta();
    $currentModuleSlug = request()->route('module');
    $currentModule = is_string($currentModuleSlug) ? rescue(fn () => $service->module($currentModuleSlug), null, false) : null;
    $currentCategory = $currentModule['category'] ?? null;

    $activeSection = match (true) {
        request()->routeIs('dashboard') => 'Principal',
        request()->routeIs('registries.*') => $currentModule['category'] ?? 'Registros',
        request()->routeIs('institution.profile') => 'Institución',
        request()->routeIs('profile') => 'Perfil',
        default => 'Principal',
    };

    $centerLinks = collect($categoryMeta)
        ->map(function (array $meta, string $category) use ($groupedModules, $currentCategory) {
            $firstModule = $groupedModules[$category][0] ?? null;

            return [
                'label' => $meta['title'] ?? $category,
                'icon' => $meta['icon'] ?? 'fas fa-folder',
                'route' => $firstModule ? route('registries.index', $firstModule['slug']) : route('registries.catalog'),
                'active' => $currentCategory === $category,
            ];
        })
        ->prepend([
            'label' => 'Panel',
            'icon' => 'fas fa-table-cells-large',
            'route' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
        ])
        ->values();

    $userRole = $user?->job_title ?: \Illuminate\Support\Str::headline($activeMembership?->role ?? 'usuario');
    $userDocument = $institution?->tax_id ?: 'No registrado';
    $userAddress = $user?->location ?: $institution?->address ?: 'Sin dirección registrada';
@endphp

<header class="app-topbar-fixed">
    <div class="app-topbar-inner">
        <div class="app-topbar-left">
            <button type="button" class="app-topbar-menu" data-sidebar-toggle aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </button>

            <a href="{{ route('dashboard') }}" wire:navigate class="app-topbar-brand">
                {{ config('app.name', 'COLEGIADOS') }}
            </a>
        </div>

        <nav class="app-topbar-center" aria-label="Accesos rápidos">
            @foreach ($centerLinks as $link)
                <a
                    href="{{ $link['route'] }}"
                    wire:navigate
                    class="app-topbar-icon {{ $link['active'] ? 'app-topbar-icon-active' : '' }}"
                    title="{{ $link['label'] }}"
                >
                    <i class="{{ $link['icon'] }}"></i>
                </a>
            @endforeach
        </nav>

        <div class="app-topbar-right">
       

            

            <a href="{{ route('registries.catalog') }}" wire:navigate class="app-topbar-utility" title="Directorio de módulos">
                <i class="fas fa-question"></i>
            </a>

            <button type="button" onclick="history.back()" class="app-topbar-utility" title="Volver">
                <i class="fas fa-reply"></i>
            </button>

            <button type="button" class="app-topbar-user" data-user-modal-toggle aria-label="Abrir menú de usuario">
                <span class="app-topbar-user-avatar">
                    <i class="fas fa-user"></i>
                </span>
                <span class="app-topbar-user-text">{{ $user?->name ?? 'Usuario' }}</span>
            </button>
        </div>
    </div>
</header>

<div class="app-user-modal-backdrop" data-user-modal-dismiss>
    <div class="app-user-modal-panel" role="dialog" aria-modal="true" aria-labelledby="user-account-title">
        <button type="button" class="app-user-modal-close" data-user-modal-dismiss aria-label="Cerrar ventana de cuenta">
            <i class="fas fa-xmark"></i>
        </button>

        <div class="app-user-modal-icon">
            <i class="fas fa-circle-info"></i>
        </div>

        <h3 id="user-account-title" class="app-user-modal-title">{{ strtoupper($user?->name ?? 'USUARIO') }}</h3>

        <dl class="app-user-modal-details">
            <div class="app-user-modal-row">
                <dt>Documento:</dt>
                <dd>{{ $userDocument }}</dd>
            </div>
            <div class="app-user-modal-row">
                <dt>Cargo:</dt>
                <dd>{{ $userRole }}</dd>
            </div>
            <div class="app-user-modal-row">
                <dt>Institución:</dt>
                <dd>{{ $institution?->name ?? 'Sin institución activa' }}</dd>
            </div>
            <div class="app-user-modal-row">
                <dt>Dirección:</dt>
                <dd>{{ $userAddress }}</dd>
            </div>
            <div class="app-user-modal-row">
                <dt>E-mail:</dt>
                <dd>{{ $user?->email ?? 'Sin correo' }}</dd>
            </div>
        </dl>

        <div class="app-user-modal-actions">
            <a href="{{ route('profile') }}" wire:navigate class="app-user-action app-user-action-profile">Mi perfil</a>
            <a href="{{ route('password.change') }}" wire:navigate class="app-user-action app-user-action-password">Cambiar contraseña</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="app-user-action app-user-action-logout">Cerrar sesión</button>
            </form>
        </div>
    </div>
</div>
