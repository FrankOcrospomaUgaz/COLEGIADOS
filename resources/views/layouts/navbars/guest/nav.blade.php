<div class="mx-auto max-w-[1600px] px-4 pb-2 pt-4 sm:px-6 xl:px-8">
    <nav class="app-topbar">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('login') }}" class="flex items-center gap-3">
                <span class="brand-badge">COLEGIADOS</span>
                <span class="text-sm font-semibold text-slate-500">Gestión profesional por instituciones</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="app-btn app-btn-secondary">Ingresar</a>
                <a href="{{ route('register') }}" class="app-btn app-btn-primary">Crear institución</a>
            </div>
        </div>
    </nav>
</div>
